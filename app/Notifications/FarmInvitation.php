<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FarmInvitation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($inviter, $role, $farm, $url)
    {
        $this->inviter  = $inviter;
        $this->role     = $role;
        $this->farm     = $farm;
        $this->url      = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->url;
        $inviter = $this->inviter->name;
        $role = $this->role->name;
        // the @ sign here because not all farms have farmedtypeclass
        $farm = @$this->farm->farmed_type_class->name.' '.$this->farm->farmed_type->name;
    
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->subject('Farm Invitation')
                    ->line("$inviter has invited you to join his $farm farm as a/an $role")
                    ->action('Join Farm', $url);
                    // ->line('Thanks!');
    }


    public function toDatabase($notifiable)
    {
        $url        = $this->url;
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $expires = $query['expires']; 
        $signature = $query['signature']; 
        $inviter    = $this->inviter->id;
        $inviter_name    = $this->inviter->name;
        $role       = $this->role->id;
        $role_name       = $this->role->name;
        $farm       = $this->farm->id;
        // the @ sign here because not all farms have farmedtypeclass
        $farm_name = @$this->farm->farmed_type_class->name.' '.$this->farm->farmed_type->name;

        return [
            'title'      => 'Farm Invitation',
            'body'      => "$inviter_name has invited you to join his $farm_name farm as a/an $role_name",
            'inviter'   => $inviter,
            'role'      => $role,
            'farm'      => $farm,
            'url'       => $url,
            'expires'   => $expires,
            'signature' => $signature,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
