<?php

namespace App\Notifications;

use App\Http\Helpers\Alerts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Product extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product, $owner_name = null)
    {
        $this->product = $product;
        $this->type = 'new_product';
        $this->title = __('new_product', ['user' => $owner_name ?? auth()->user()->name]);
        $this->msg = __('new_product_details', ['user' => $owner_name ?? auth()->user()->name, 'product' => $product->name]);

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
            'id'                => auth()->id(),
            'object_id'         => $this->product->id,
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
