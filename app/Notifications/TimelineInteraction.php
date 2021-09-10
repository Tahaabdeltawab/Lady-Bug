<?php

namespace App\Notifications;

use App\Http\Helpers\Alerts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimelineInteraction extends Notification
{
    use Queueable;

    private $type;
    private $comment;
    private $post;
    private $commenter;
    private $reactor;// the liker or commenter
    private $title;
    private $msg;
    private $like;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    /**
     * comment on a post
     * reply to a comment
     * like post
     * like comment
     * do not notify if you commented or liked your post or comment
     */
    public function __construct($obj)
    {
        $like_model = config('like.like_model');

        if($obj instanceOf \App\Models\Comment){
            $this->type         = 'comment';
            $this->comment      = $obj;
            $this->commenter    = $this->comment->commenter;
            $this->reactor      = $this->commenter;
            $this->post         = $this->comment->post;
            $this->title        = 'Post Comment';
            $post_substr        = $this->post->content ? substr($this->post->content, 0, 20).'...' : '';
            $this->msg          = $this->commenter->name . " has commented on your post $post_substr";
        }elseif($obj instanceOf  $like_model){
            $this->type         = 'like';
            $this->like         = $obj;
            $this->reactor      = $this->like->liker;
            $msg                = $this->like->liker->name . " has %s your %s";
            if($this->like->likeable_type == 'App\Models\Post'){
                $this->post         = $this->like->likeable;
                $post_substr        = $this->post->content ? substr($this->post->content, 0, 20).'...' : '';
                if($this->like->is_like){
                    $this->title        = 'Post Like';
                    $this->msg          = sprintf($msg, 'liked', "post $post_substr");
                }else{
                    $this->title        = 'Post Dislike';
                    $this->msg          = sprintf($msg, 'disliked', "post $post_substr");
                }
            }
            if($this->like->likeable_type == 'App\Models\Comment'){
                $this->comment  = $this->like->likeable;
                $this->post     = $this->comment->post;
                $comment_substr        = $this->comment->content ? substr($this->comment->content, 0, 20).'...' : '';
                if($this->like->is_like){
                    $this->title        = 'Comment Like';
                    $this->msg          = sprintf($msg, 'liked', "comment $comment_substr");
                }else{
                    $this->title        = 'Comment Dislike';
                    $this->msg          = sprintf($msg, 'disliked', "comment $comment_substr");
                }
            }
        }
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
        $return =  [
            'title'     => $this->title,
            'body'      => $this->msg,
            'type'      => $this->type,
            'id'        => $this->post->id,
        ];
        // if($this->type == 'comment'){
        //     $return['comment_id'] = $this->comment->id;
        // }elseif($this->type == 'like'){
        //     $return['like_id'] = $this->like->id;
        // }

        if($notifiable->is_notifiable)
        Alerts::sendMobileNotification($this->title, $this->msg, $notifiable->fcm, ['id' => $return['id'], 'type' => $return['type']]);




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
