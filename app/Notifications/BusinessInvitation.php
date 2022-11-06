<?php

namespace App\Notifications;

use App\Http\Helpers\Alerts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BusinessInvitation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($inviter, $role, $business, $accept_url, $decline_url)
    {
        $this->business     = $business;
        $this->inviter      = $inviter;
        $this->role         = $role;
        $this->type         = 'farm_invitation';
        $this->title        = __('Business Invitation');
        $business_name      = @$this->business->com_name;
        $this->msg          = __('business_invitation_msg', ['user' => $this->inviter->name, 'business' => $business_name, 'role' => $this->role->name]);
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
        // return ['mail', 'database'];
        return ($notifiable->is_notifiable) ? ['database'] : [];
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
                    ->greeting(__('Hello', ['name' => $notifiable->name]))
                    ->subject($this->title)
                    ->line($this->msg)
                    ->action(__('Join Business'), $accept_url)
                    ->line(__('To decline the invitation, visit the link below'))
                    ->line($decline_url);
                    // ->line('Thanks!');
    }


    public function toDatabase($notifiable)
    {
        // parse_str(parse_url($this->accept_url, PHP_URL_QUERY), $accept_query);
        // $accept_expires = $accept_query['expires'];
        // parse_str(parse_url($this->decline_url, PHP_URL_QUERY), $decline_query);
        // $decline_expires = $decline_query['expires'];

        $return = [
            'title'             => $this->title,
            'body'              => $this->msg,
            'inviter'           => $this->inviter->id,
            'invitee'           => $notifiable->id,
            'role'              => $this->role->id,
            'business'          => $this->business->id,
            'accepted'          => null,
            'type'              => $this->type,
            'accept_url'        => $this->accept_url,
            'decline_url'       => $this->decline_url,
        ];
        $return = mb_convert_encoding($return, 'UTF-8', 'UTF-8');

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
