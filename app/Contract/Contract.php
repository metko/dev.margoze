<?php

namespace App\Contract;

use App\User\User;
use App\Demand\Demand;
use App\Candidature\Candidature;
use Metko\Galera\GlrConversation;
use Illuminate\Database\Eloquent\Model;
use App\Contract\Events\ContractValidated;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contract\Events\SettingsContractRevoked;
use App\Contract\Events\SettingsContractProposed;
use App\Contract\Exceptions\SettingsAlreadySubmit;
use App\Contract\Exceptions\ContractAlreadyValidated;
use App\Contract\Exceptions\UserDoesntBelongsToContract;

class Contract extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'wait_for_validate' => 'boolean',
    ];

    protected $with = ['demand', 'userDemand', 'userCandidature'];

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

    public function userDemand()
    {
        return $this->belongsTo(User::class, 'demand_owner_id', 'id');
    }

    public function userCandidature()
    {
        return $this->belongsTo(User::class, 'candidature_owner_id', 'id');
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class, 'demand_id', 'id');
    }

    public function conversation()
    {
        return $this->belongsTo(GlrConversation::class);
    }

    public function candidature()
    {
        return $this->belongsTo(Candidature::class, 'candidature_id', 'id');
    }

    public function proposeSettings(array $settings, $user)
    {
        if ($this->canEditSettings($user, true)) {
            $this->setSettings($settings, $user);
        }
    }

    public function canProposeSettings($user)
    {
        if ($this->canEditSettings($user)) {
            return true;
        }

        return false;
    }

    /**
     * canEditSettings Check if user can edit the current contract.
     *
     * @param mixed $user
     * @param mixed $withErrors
     */
    protected function canEditSettings($user, $withErrors = false)
    {
        if ($this->validated_at) {
            if ($withErrors) {
                throw ContractAlreadyValidated::create();
            }
        }

        if (!$user->isInContract($this)) {
            if ($withErrors) {
                throw UserDoesntBelongsToContract::create();
            }

            return false;
        }
        if ($this->last_propose_by == $user->id) {
            if ($withErrors) {
                throw SettingsAlreadySubmit::create();
            }

            return false;
        }

        return true;
    }

    /**
     * setDate Propose a date for contract befoe validating.
     *
     * @param mixed $date
     * @param mixed $user
     */
    public function setSettings($settings, $user)
    {
        $this->be_done_at = $settings['be_done_at'];
        $this->wait_for_validate = true;
        $this->last_propose_by = $user->id;
        $this->save();

        event(new SettingsContractProposed($this, $this->getOtherUser($user), $user));

        return $this;
    }

    public function getOtherUser($user)
    {
        if ($this->userDemand->id != $user->id) {
            return $this->userDemand;
        } elseif ($this->userCandidature->id != $user->id) {
            return $this->userCandidature;
        }
    }

    public function revokeSettings($user)
    {
        if ($this->canEditSettings($user, true)) {
            $this->be_done_at = null;
            $this->wait_for_validate = false;
            $this->last_propose_by = null;
            $this->save();

            event(new SettingsContractRevoked($this, $this->getOtherUser($user), $user));

            return $this;
        }

        return false;
    }

    public function validate($date = null)
    {
        $this->validated_at = $date ?? now();
        $this->wait_for_validate = false;
        $this->save();
    }

    public function canBeValidate($user)
    {
        return $this->canEditSettings($user) && !empty($this->be_done_at) && empty($this->validated_at);
    }

    public function validateSettings($user)
    {
        if ($this->canBeValidate($user)) {
            $this->validate();
            event(new ContractValidated($this));

            return $this;
        }

        return false;
    }

    public function cancel($user)
    {
        if (!$user->isInContract($this)) {
            if ($withErrors) {
                throw UserDoesntBelongsToContract::create();
            }

            return false;
        }
        $this->cancelled_by = $user->id;
        $this->save();

        return $this;
    }
}
