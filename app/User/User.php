<?php

namespace App\User;

use App\Demand\Demand;
use App\Contract\Contract;
use Metko\Galera\Galerable;
use Metko\Metkontrol\Traits;
use Laravel\Cashier\Billable;
use App\User\Events\UserBanned;
use App\Candidature\Candidature;
use App\User\Events\UserDeleted;
use App\User\Events\UserUpdated;
use App\User\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Candidature\Events\CandidatureCreated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use App\Candidature\Exceptions\CandidatureAlreadySent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Candidature\Exceptions\CandidatureBelongsToOwnerDemand;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable, SoftDeletes,
        Traits\MetkontrolRole,
        Traits\MetkontrolPermission,
        Traits\MetkontrolCacheReset,
        Galerable;

    protected $with = [];

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
        static::updated(function ($user) {
            event(new UserUpdated($user));
        });
        static::deleted(function ($user) {
            event(new UserDeleted($user));
        });
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

        $candidature = $demand->candidatures()->create($candidature);
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
    public function isInContract($contract): bool
    {
        if (!$contract instanceof Contract) {
            $contract = Contract::find($contract);
        }

        return $contract->demand_owner_id == $this->id || $contract->candidature_owner_id == $this->id;
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
}
