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
    public function __construct($inviter, $role, $farm, $accept_url, $decline_url)
    {
        $this->inviter      = $inviter;
        $this->role         = $role;
        $this->farm         = $farm;
        $this->accept_url   = $accept_url;
        $this->decline_url  = $decline_url;
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
        $accept_url = $this->accept_url;
        $decline_url = $this->decline_url;
        $inviter = $this->inviter->name;
        $role = $this->role->name;
        // the @ sign here because not all farms have farmedtypeclass
        $farm = @$this->farm->farmed_type_class->name.' '.$this->farm->farmed_type->name;

        return (new MailMessage)
                    ->greeting('Hello ' . $notifiable->name)
                    ->subject('Farm Invitation')
                    ->line("$inviter has invited you to join his $farm farm as a/an $role")
                    ->action('Join Farm', $accept_url)
                    ->line("To decline the invitation, visit the link below")
                    ->line($decline_url);
                    // ->line('Thanks!');
    }


    public function toDatabase($notifiable)
    {
        $accept_url        = $this->accept_url;
        parse_str(parse_url($accept_url, PHP_URL_QUERY), $accept_query);
        $accept_expires = $accept_query['expires'];
        $accept_signature = $accept_query['signature'];

        $decline_url        = $this->decline_url;
        parse_str(parse_url($decline_url, PHP_URL_QUERY), $decline_query);
        $decline_expires = $decline_query['expires'];
        $decline_signature = $decline_query['signature'];

        $inviter    = $this->inviter->id;
        $inviter_name    = $this->inviter->name;
        $role       = $this->role->id;
        $role_name       = $this->role->name;
        $farm       = $this->farm->id;
        // the @ sign here because not all farms have farmedtypeclass
        $farm_name = @$this->farm->farmed_type_class->name.' '.$this->farm->farmed_type->name;

        return [
            'title'      => 'Farm Invitation', // if changed, change in noti_resource as well.
            'body'      => "$inviter_name has invited you to join his $farm_name farm as a/an $role_name",
            'inviter'   => $inviter,
            'invitee'   => $notifiable->id,
            'role'      => $role,
            'farm'      => $farm,
            'accepted'  => null,
            'accept_url'       => $accept_url,
            'accept_expires'   => $accept_expires,
            'accept_signature' => $accept_signature,
            'decline_url'       => $decline_url,
            'decline_expires'   => $decline_expires,
            'decline_signature' => $decline_signature,
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
