<?php

namespace App\User;

use Metko\Metkontrol\Traits;
use Laravel\Cashier\Billable;
use App\User\Events\UserBanned;
use App\User\Events\UserDeleted;
use App\User\Events\UserUpdated;
use App\User\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable, SoftDeletes,
        Traits\MetkontrolRole,
        Traits\MetkontrolPermission,
        Traits\MetkontrolCacheReset;

    public $with = ['roles'];

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
        'password', 'remember_token',
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
        //return 'Users.'.$this->id;
        return 'user-created';
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
}
