<?php

namespace App\Notifications;

use App\Http\Helpers\Alerts;
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
        $farm_name          = @$this->farm->farmed_type_class->name.' '.$this->farm->farmed_type->name;
        $this->title        = 'Farm Invitation';// if changed, change in noti_resource as well.
        $this->msg          = $this->inviter->name . " has invited you to join his $farm_name farm as a/an " . $this->role->name;
        $this->type         = 'farm_invitation';
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

        return (new MailMessage)
                    ->greeting('Hello ' . $notifiable->name)
                    ->subject('Farm Invitation')
                    ->line($this->msg)
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
        $role       = $this->role->id;
        $farm       = $this->farm->id;

        $return = [
            'title'             => $this->title,
            'body'              => $this->msg,
            'inviter'           => $inviter,
            'invitee'           => $notifiable->id,
            'role'              => $role,
            'farm'              => $farm,
            'accepted'          => null,
            'type'              => $this->type,
            'accept_url'        => $accept_url,
            'accept_expires'    => $accept_expires,
            'accept_signature'  => $accept_signature,
            'decline_url'       => $decline_url,
            'decline_expires'   => $decline_expires,
            'decline_signature' => $decline_signature,
        ];

        if($notifiable->is_notifiable)
        Alerts::sendMobileNotification($this->title, $this->msg, $notifiable->fcm, $return);

        return $return;
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
