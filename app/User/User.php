<?php

namespace App\User;

use Metko\Metkontrol\Traits;
use Laravel\Cashier\Billable;
use App\User\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable, Notifiable,
        Traits\MetkontrolRole,
        Traits\MetkontrolPermission,
        Traits\MetkontrolCacheReset;

    public $with = ['roles'];

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
        'trusted' => 'boolean',
        'profesionnal' => 'boolean',
        'subscriber' => 'boolean',
    ];

    public function getAvatarAttribute()
    {
        if (!empty($this->avatar)) {
            return $this->avatar;
        }

        return '/img/default_avatar.jpg';
    }
}
