<?php

namespace App\Contract;

use App\User\User;
use App\Demand\Demand;
use App\Litige\Litige;
use App\Sector\Sector;
use App\Category\Category;
use Illuminate\Support\Str;
use Metko\Galera\GlrMessage;
use App\Evaluation\Evaluation;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use Illuminate\Database\Eloquent;
use Metko\Galera\GlrConversation;
use App\Contract\Exceptions\InvalidatedContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Eloquent\Model
{
    use Eloquent\SoftDeletes;

    /**
     * Guarded fields.
     */
    protected $guarded = [];

    /**
     * Casted fields.
     */
    protected $casts = [
        'wait_for_validate' => 'boolean',
    ];

    /**
     * boot Boot method of eloquent Model class.
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($contract) {
        });
        static::updating(function ($contract) {
        });
        static::created(function ($contract) {
            //dd($contract);
        });
    }

    /**
     * userDemand The user of the demand.
     */
    public function userDemand(): BelongsTo
    {
        return $this->belongsTo(User::class, 'demand_owner_id', 'id');
    }

    /**
     * userCandidature The user of the candidature.
     */
    public function userCandidature(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidature_owner_id', 'id');
    }

    /**
     * demand Get the demand related to the contract.
     */
    public function demand(): BelongsTo
    {
        return $this->belongsTo(Demand::class, 'demand_id', 'id');
    }

    /**
     * conversation Get the conversation related to the contract.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(GlrConversation::class);
    }

    /**
     * candidature. Get the candidature associated to the contract
     * The one who was contracted.
     */
    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class, 'candidature_id', 'id');
    }

    /**
     * category Get the category of a contract
     * Inherit of the demand.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * sector Get the geographic  sector  of the contract.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * sector Get the geographic  sector  of the contract.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(GlrMessage::class);
    }

    /**
     * sector Get the evaluation  of the contract.
     */
    public function litiges(): HasMany
    {
        return $this->hasMany(Litige::class);
    }

    /**
     * sector Get the evaluation  of the contract.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * proposeSettings Propose settings by the user.
     *
     * @param mixed $settings
     * @param mixed $user
     */
    public function proposeSettings(array $settings, User $user): bool
    {
        if ($this->canEditSettings($user, true)) {
            $this->setSettings($settings, $user);

            return true;
        }

        return false;
    }

    /**
     * canEditSettings Check if user can edit the current contract.
     *
     * @param mixed $user       User who can edit
     * @param mixed $withErrors With Erro Exception
     */
    protected function canEditSettings(User $user, Bool $errors = false): bool
    {
        if ($this->isValidated($errors)) {
            return false;
        }

        if (!$user->isInContract($this, $errors)) {
            return false;
        }

        if ($this->isLastProposer($user, $errors)) {
            return false;
        }

        return true;
    }

    public function isLastProposer($user, $errors)
    {
        if ($this->last_propose_by == $user->id) {
            if ($errors) {
                throw Exceptions\SettingsAlreadySubmit::create();
            }

            return true;
        }

        return false;
    }

    /**
     * setDate Propose a date for contract befoe validating.
     *
     * @param mixed $settings Array of setttings we want to save
     * @param mixed $user     User who save the settings
     */
    public function setSettings(array $settings, User $user): Contract
    {
        $this->be_done_at = $settings['be_done_at'];
        $this->wait_for_validate = true;
        $this->last_propose_by = $user->id;
        $this->save();

        event(new Events\SettingsContractProposed($this, $this->getOtherUser($user), $user));

        return $this;
    }

    /**
     * getOtherUser Get the othe user in a contract.
     *
     * @param mixed $user The user who want to compare
     */
    public function getOtherUser(User $user): User
    {
        if ($this->demand_owner_id == $user->id) {
            return $this->userCandidature;
        } elseif ($this->candidature_owner_id == $user->id) {
            return $this->userDemand;
        }
    }

    /**
     * revokeSettings.
     *
     * @param mixed $user
     */
    public function revokeSettings(User $user)
    {
        if ($this->canEditSettings($user, true)) {
            $this->be_done_at = null;
            $this->wait_for_validate = false;
            $this->last_propose_by = null;
            $this->save();
            event(new Events\SettingsContractRevoked($this, $this->getOtherUser($user), $user));

            return $this;
        }

        return false;
    }

    /**
     * canBeValidate If a contract can be validate.
     *
     * @param mixed $user
     */
    public function canBeValidate($errors = false): bool
    {
        if (!$this->isValidated($errors) && $this->isBeDoneAtSup($errors) && $this->isWaitingResponse()) {
            return true;
        }

        return false;
    }

    public function isBeDoneAtSup(Bool $errors = false, $date = null)
    {
        $beDoneAt = Carbon::parse($this->be_done_at);
        if ($beDoneAt->greaterThan($date ?? now())) {
            return true;
        }
        if ($errors) {
            throw InvalidatedContract::create();
        }

        return false;
    }

    public function isValidatedAtSup(Bool $errors = false, $date = null)
    {
        $validatedAt = Carbon::parse($this->validated_at);
        //dump(now());

        if ($validatedAt->gt($date ?? now())) {
            return true;
        }

        if ($errors) {
            throw InvalidatedContract::create();
        }

        return false;
    }

    /**
     * validate. Validate a contract.
     *
     * @param mixed $date DateTime we want to save
     */
    public function validate($date = null, $erros = false): Contract
    {
        if ($this->canBeValidate(true)) {
            $this->validated_at = $date ?? now();
            $this->wait_for_validate = false;
            $this->save();

            return $this;
        }

        return false;
    }

    public function isWaitingResponse()
    {
        if ($this->wait_for_validate) {
            return true;
        }

        return false;
    }

    /**
     * canBeFinish If a contract can be finish.
     *
     * @param mixed $user
     */
    public function canBeFinish($date, $errors = false): bool
    {
        $date = $date ?? now();
        if ($this->isValidated() && !$this->isFinished()
            && $date->greaterThan(Carbon::parse($this->be_done_at))) {
            return true;
        }
        if ($errors) {
            dd('contract cant be finished. be done at superioir at today');
        }

        return false;
    }

    /**
     * validateSettings. Validate the settings.
     *
     * @param mixed $user
     */
    public function validateSettings(User $user)
    {
        if ($this->canBeValidate()) {
            $this->validate();
            event(new Events\ContractValidated($this));

            return $this;
        }

        return false;
    }

    /**
     * validate. Validate a contract.
     *
     * @param mixed $date DateTime we want to save
     */
    public function finish($date = null): Contract
    {
        if ($this->canBeFinish($date, true)) {
            $this->finished_at = $date ?? now();
            $this->save();
        }

        return $this;
    }

    /**
     * cancel Cancel a contract.
     *
     * @param mixed $user The user who cancel the contract
     */
    public function cancel(User $user): bool
    {
        if (!$user->isInContract($this)) {
            if ($withErrors) {
                throw Exceptions\UserDoesntBelongsToContract::create();
            }

            return false;
        }
        $this->cancelled_by = $user->id;
        $this->save();

        return true;
    }

    /**
     * isValidated Check if a contract is validated.
     */
    public function isValidated($errors = false): bool
    {
        if ($this->validated_at) {
            if ($errors) {
                throw Exceptions\ContractAlreadyValidated::create();
            }

            return true;
        }

        return false;
    }

    /**
     * isValidated Check if a contract is validated.
     */
    public function isFinished(): bool
    {
        if ($this->finished_at) {
            return true;
        }

        return false;
    }

    /**
     * isCancelled Check if a contract is cancelled.
     */
    public function isCancelled(): bool
    {
        if ($this->cancelled_by) {
            return true;
        }

        return false;
    }

    /**
     * isConfirmable If a contract is confirmable.
     */
    public function isConfirmable(): bool
    {
        if ($this->be_done_at && $this->last_propose_by && $this->wait_for_validate) {
            return true;
        }

        return false;
    }

    public function isEvaluable()
    {
        if (!$this->isBeDoneAtSup() && !$this->isValidatedAtSup() && $this->last_propose_by) {
            return true;
        }

        return false;
    }

    public function hasLitiges()
    {
        if ($this->litiges->count()) {
            return true;
        }

        return false;
    }

    public function createLitige($attr)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('slug', 'admin');
        })->first();

        $conversation = Galera::participants($attr['causer_id'], $admin)->make();
        $attr['conversation_id'] = $conversation->id;
        $attr['uuid'] = Str::uuid();
        $litige = $this->litiges()->create($attr);

        return $litige;
    }
}
