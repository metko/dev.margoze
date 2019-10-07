<?php

namespace App\User;

use App\Credit\Credit;
use App\Demand\Demand;
use App\Commune\Commune;
use App\Contract\Contract;
use App\District\District;
use Metko\Galera\Galerable;
use Metko\Metkontrol\Traits;
use Laravel\Cashier\Billable;
use App\Evaluation\Evaluation;
use Illuminate\Support\Carbon;
use App\User\Events\UserBanned;
use App\Candidature\Candidature;
use App\User\Events\UserDeleted;
use App\User\Events\UserUpdated;
use App\User\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Credit\Exceptions\NoCreditsAvailable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Candidature\Events\CandidatureCreated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contract\Exceptions\InvalidatedContract;
use App\Contract\Exceptions\ContractUnrealizedYet;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Evaluation\Exceptions\UserAlreadyEvaluated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Candidature\Exceptions\CandidatureAlreadySent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Contract\Exceptions\UserDoesntBelongsToContract;
use App\Candidature\Exceptions\CandidatureBelongsToOwnerDemand;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable, SoftDeletes,
        Traits\MetkontrolRole,
        Traits\MetkontrolPermission,
        Traits\MetkontrolCacheReset,
        Galerable;

    protected $with = ['evaluations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'banned',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'vehiculable' => 'boolean',
        'suspended' => 'boolean',
        'banned' => 'boolean',
        'profesionnal' => 'boolean',
        'subscriber' => 'boolean',
    ];

    /**
     * boot.
     */
    public static function boot()
    {
        parent::boot();
        static::created(function ($user) {
            $user->credits()->create();
        });
        static::updated(function ($user) {
            event(new UserUpdated($user));
        });
        static::deleted(function ($user) {
            event(new UserDeleted($user));
        });
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Commune of the demand.
     *
     * @return BelongsTo
     */
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_id');
    }

    /**
     * Disctrict of the demand.
     *
     * @return BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Credits of the user.
     *
     * @return hasOne
     */
    public function credits(): HasOne
    {
        return $this->hasOne(Credit::class, 'owner_id');
    }

    /**
     * Channel for broadcast notificatiion users.
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    /**
     * Send a email with verification link.
     */
    public function sendEmailVerificationNotification()
    {
        $when = now()->addSeconds(3);
        $this->notify((new VerifyEmail())->delay($when));
    }

    /**
     * getAvatarAttribute.
     */
    public function getAvatar(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return '/img/default_avatar.jpg';
    }

    /**
     * Check ifg the current user has a suspended account.
     */
    public function isSuspended(): bool
    {
        return $this->suspended;
    }

    /**
     * Suspend the acount of the user.
     */
    public function suspendAccount()
    {
        return $this->update(['suspended' => true]);
    }

    /**
     * Ban a user.
     */
    public function ban()
    {
        event(new UserBanned($this));

        return $this->update(['banned' => true]);
    }

    /**
     * Check if a user is banned.
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->update(['banned' => true]);
    }

    /**
     * Create a candidaturte for the givven demand.
     *
     * @param mixed $demand
     * @param mixed $candidature
     *
     * @return Candidature
     */
    public function apply(Demand $demand, array $candidature): Candidature
    {
        if ($this->hasApply($demand)) {
            throw CandidatureAlreadySent::create($demand->id);
        }

        if ($this->isOwnerDemand($demand)) {
            throw CandidatureBelongsToOwnerDemand::create($demand->id);
        }

        if (!$demand->isValid()) {
            throw DemandNoLongerAvailable::create($demand->id);
        }

        if ($demand->isContracted()) {
            throw DemandAlreadyContracted::create($demand->id);
        }
        if (!$this->hasCredit('candidatures_count')) {
            throw NoCreditsAvailable::create();
        }

        $candidature = $demand->candidatures()->create($candidature);
        $this->useCredit('candidatures_count');
        event(new CandidatureCreated($demand, $demand->owner, $candidature, $candidature->owner));

        return $candidature;
    }

    /**
     * Check if the givven user is the owner of the demand.
     *
     * @param mixed $demand
     *
     * @return bool
     */
    public function isOwnerDemand(Demand $demand): bool
    {
        return $demand->owner_id == $this->id;
    }

    /**
     * Check if the user has already apply for this demand.
     *
     * @param mixed $demand
     *
     * @return bool
     */
    public function hasApply(Demand $demand): bool
    {
        if ($demand->candidatures->contains('owner_id', $this->id)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the user can apply on the demand.
     *
     * @param mixed $demand
     */
    public function canApply(Demand $demand): bool
    {
        if (!$this->hasApply($demand) &&
             !$this->isOwnerDemand($demand) &&
             $demand->isValid() &&
             !$demand->isContracted()) {
            return true;
        }

        return false;
    }

    /**
     * Check if the user is part of the givven contract.
     *
     * @param mixed $contract
     */
    public function isInContract($contract, $errors = false): bool
    {
        if (!$contract instanceof Contract) {
            $contract = Contract::find($contract);
        }

        if ($contract->demand_owner_id == $this->id || $contract->candidature_owner_id == $this->id) {
            return true;
        }
        if ($errors) {
            throw UserDoesntBelongsToContract::create();
        }

        return false;
    }

    /**
     * Propose settings for the givven contract from current user.
     *
     * @param mixed $contract
     * @param mixed $settings
     */
    public function proposeSettings($contract, array $settings)
    {
        return $contract->proposeSettings($settings, $this);
    }

    /**
     * Revoke the contract setting from the user.
     *
     * @param mixed $contract
     */
    public function revokeSettings($contract)
    {
        return $contract->revokeSettings($this);
    }

    /**
     * Validate the settings of a contract.
     *
     * @param mixed $contract
     */
    public function validateSettings($contract)
    {
        return $contract->validateSettings($this);
    }

    /**
     * if the user can validate the contract.
     *
     * @param mixed $contract
     */
    public function canValidateContract($contract)
    {
        return $contract->canBeValidate($this);
    }

    /**
     * Cancel the contratc from the user.
     *
     * @param mixed $contract
     */
    public function cancelContract($contract)
    {
        return $contract->cancel($this);
    }

    public function evaluate(User $user, Contract $contract, array $evaluation)
    {
        $evaluation['causer_id'] = $this->id;
        $evaluation['contract_id'] = $contract->id;

        if ($this->canEvaluate($contract, $user, true)) {
            $user->evaluations()->create($evaluation);
        }
    }

    public function hasEvaluated(Contract $contract): bool
    {
        if ($contract->evaluations->contains('causer_id', $this->id)) {
            return true;
        }

        return false;
    }

    public function canEvaluate(Contract $contract, User $user, $errors = true)
    {
        if (!$this->isInContract($contract) || !$user->isInContract($contract)) {
            if ($errors) {
                throw UserDoesntBelongsToContract::create();
            }

            return false;
        }

        if (!$contract->isValidated()) {
            if ($errors) {
                throw InvalidatedContract::create();
            }

            return false;
        }
        if ($contract->isBeDoneAtSup() || $contract->isValidatedAtSup()) {
            if ($errors) {
                throw ContractUnrealizedYet::create();
            }

            return false;
        }

        if ($this->hasEvaluated($contract)) {
            if ($errors) {
                throw UserAlreadyEvaluated::create();
            }

            return false;
        }

        return true;
    }

    public function getMemberSinceAttribute()
    {
        $date = Carbon::parse($this->created_at)->locale('fr')->diffForHumans([
            'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS,
        ]);
        $date = str_replace('il y a', '', $date);

        return $date;
    }

    public function getSubscribeSinceAttribute()
    {
        $date = Carbon::parse($this->subscriptions()->first()->created_at)
                ->locale('fr_FR')->isoFormat('D MMMM YYYY');

        return $date;
    }

    public function subscribeSince($subscription)
    {
        $date = Carbon::parse($subscription->created_at)
                ->locale('fr_FR')->isoFormat('D MMMM YYYY');

        return $date;
    }

    public function getUpCommingInvoiceInDaysAttribute()
    {
        $diff = Carbon::parse($this->upcomingInvoice()->created);
        $date = Carbon::now()->diffIndays($diff);

        return $date;
    }

    public function getAverageNote()
    {
        if ($this->evaluations->count()) {
            return $this->evaluations->avg('note');
        }

        return '*';
    }

    public function subscribe()
    {
        $this->subscriber = true;
        $this->save();
    }

    public function credit()
    {
        if ($this->subscribed('main')) {
            $subscription = $this->subscriptions()->first();

            return $this->credits->setType($subscription->stripe_plan)->credit(true);
        } else {
            return $this->credits->credit();
        }
    }

    public function useCredit($credit)
    {
        $this->credits->useCredit($credit);
    }

    public function hasCredit($credit)
    {
        return $this->credits->$credit;
    }

    public function isSubscriber()
    {
        if ($this->subscriber) {
            return true;
        }

        return false;
    }
}
