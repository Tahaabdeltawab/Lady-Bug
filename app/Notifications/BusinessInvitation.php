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
        $accept_url        = $this->accept_url;
        parse_str(parse_url($accept_url, PHP_URL_QUERY), $accept_query);
        $accept_expires = $accept_query['expires'];
        $accept_signature = $accept_query['signature'];
        // $accept_start_date = $accept_query['start_date']; // not queries but params
        // $accept_end_date = $accept_query['end_date'];
        // $accept_period = $accept_query['period'];
        // $accept_plan_id = $accept_query['plan_id'];
        // $accept_permissions = $accept_query['permissions'];

        $decline_url        = $this->decline_url;
        parse_str(parse_url($decline_url, PHP_URL_QUERY), $decline_query);
        $decline_expires = $decline_query['expires'];
        $decline_signature = $decline_query['signature'];

        $inviter    = $this->inviter->id;
        $role       = $this->role->id;
        $business       = $this->business->id;

        $return = [
            'title'             => $this->title,
            'body'              => $this->msg,
            'inviter'           => $inviter,
            'invitee'           => $notifiable->id,
            'role'              => $role,
            'business'          => $business,
            'accepted'          => null,
            'type'              => $this->type,
            'accept_url'        => $accept_url,
            'accept_expires'    => $accept_expires,
            'accept_signature'  => $accept_signature,
            // 'accept_start_date' => $accept_start_date,
            // 'accept_end_date'   => $accept_end_date,
            // 'accept_period'     => $accept_period,
            // 'accept_plan_id'    => $accept_plan_id,
            // 'accept_permissions'=> $accept_permissions,
            'decline_url'       => $decline_url,
            'decline_expires'   => $decline_expires,
            'decline_signature' => $decline_signature,
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
