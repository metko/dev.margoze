<?php

namespace App\User\Notifications;

use App\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserCreatedAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject('Someone registered to your app')
                    ->line('A new user is member of a the family.')
                    ->action('See the user', url('/users/'.$this->user->id))
                    ->line('Thank you for using our application!');
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'username' => $this->user->username,
    //         'avatar' => $this->user->avatar,
    //         'email' => $this->user->email,
    //         'user_id' => $this->user->id,
    //     ]);
    // }
}
