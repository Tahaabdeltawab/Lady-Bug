<?php

namespace App\Notifications;

use App\Http\Helpers\Alerts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsBought extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($price)
    {
        $this->buyer = auth()->user();
        $this->type = 'consultancy_bought';
        $this->title = $this->buyer->name . ' ' . __('has bought a consultancy plan from you');
        $this->msg = $this->buyer->name . ' ' . __('has bought a consultancy plan from you with price ') . $price;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
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
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable)
    {

        $return = [
            'title'             => $this->title,
            'body'              => $this->msg,
            'type'              => $this->type,
            'id'                => $this->buyer->id,
            'object_id'         => $notifiable->id, // the consultant
        ];
        $return = mb_convert_encoding($return, 'UTF-8', 'UTF-8');

        Alerts::sendMobileNotification($this->title, $this->msg, $notifiable->fcm, ['id' => $return['id'], 'type' => $return['type'], 'object_id' => $return['object_id']]);

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
