<?php

namespace App\Notifications;

use App\Http\Helpers\Alerts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAlarm extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task = $task;
        $this->type = 'task_alarm';
        $this->title= 'Task Alarm';
        $this->msg  = 'You have a task ' . $this->task->name . ' on ' . date('Y-m-d', strtotime($this->task->start_at));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'id'                => $this->task->farm->id,
        ];

        if($notifiable->is_notifiable)
        Alerts::sendMobileNotification($this->title, $this->msg, $notifiable->fcm, ['id' => $return['id']]);

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
