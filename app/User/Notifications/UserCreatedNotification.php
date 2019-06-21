<?php

namespace App\User\Notifications;

use App\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserCreatedNotification extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'username' => $this->user->username,
            'email' => $this->user->email,
            'user_id' => $this->user->id,
            'message' => 'A new user was created',
        ];
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
                    ->subject('Bienvenue sur margoze')
                    ->line('Bienvenue sur margoze, nous somme ravie e blablabla. Merci de valider votre compte dans l\'autre email recu')
                    ->action('Create your firstb demand', url('demands'))
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
