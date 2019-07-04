<?php

namespace App\User;

use App\Demand\Demand;
use App\Contract\Contract;
use Metko\Galera\Galerable;
use Metko\Metkontrol\Traits;
use Laravel\Cashier\Billable;
use App\User\Events\UserBanned;
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

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    public function sendEmailVerificationNotification()
    {
        $when = now()->addSeconds(3);
        $this->notify((new VerifyEmail())->delay($when));
    }

    public function getAvatarAttribute()
    {
        if (!empty($this->avatar)) {
            return $this->avatar;
        }

        return '/img/default_avatar.jpg';
    }

    public function isSuspended()
    {
        return $this->suspended;
    }

    public function suspendAccount()
    {
        return $this->update(['suspended' => true]);
    }

    public function ban()
    {
        event(new UserBanned($this));

        return $this->update(['banned' => true]);
    }

    public function isBanned()
    {
        return $this->update(['banned' => true]);
    }

    public function apply(Demand $demand, $candidature)
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

    public function isOwnerDemand(Demand $demand)
    {
        return $demand->owner_id == $this->id;
    }

    public function hasApply(Demand $demand)
    {
        if ($demand->candidatures->contains('owner_id', $this->id)) {
            return true;
        }

        return false;
    }

    public function canApply(Demand $demand)
    {
        if (!$this->hasApply($demand) &&
             !$this->isOwnerDemand($demand) &&
             $demand->isValid() &&
             !$demand->isContracted()) {
            return true;
        }

        return false;
    }

    public function isInContract($contract)
    {
        if (!$contract instanceof Contract) {
            $contract = Contract::find($contract);
        }

        return $contract->demand_owner_id == $this->id || $contract->candidature_owner_id == $this->id;
    }
}
