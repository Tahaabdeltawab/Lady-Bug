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
    private $reactor;// the liker or commenter or poster (following_post)
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
    public function __construct($obj, $type = '')
    {
        $like_model = config('like.like_model');

        if($obj instanceOf \App\Models\Post)
        {
            if($type == 'post_share'){
                $this->type         = $type;
                $this->post         = $obj; // the new post
                $this->reactor      = $this->post->author; // sharer, author of the new post
                $this->title        = __('Post Share');
                $post_substr        = \Str::words($this->post->shared->content, 3, '...');
                $this->msg = $this->reactor->name . ' ' . __('has shared your post') . ' ' . $post_substr;
            }else{
                $this->type         = 'following_post';
                $this->post         = $obj;
                $this->reactor      = $this->post->author;
                $this->title        = __('Following Post');
                $post_substr        = \Str::words($this->post->content, 3, '...');
                $this->msg = $this->reactor->name . ' ' . __('has posted a new post') . ' ' . $post_substr;
            }
        }
        elseif($obj instanceOf \App\Models\Comment){
            $this->type         = 'comment';
            $this->comment      = $obj;
            $this->commenter    = $this->comment->commenter;
            $this->reactor      = $this->commenter;
            $this->post         = $this->comment->post;
            $this->title        = __('Post Comment');
            $post_substr        = \Str::words($this->post->content, 3, '...');

            $this->msg = $this->commenter->name . ' ' . __('has commented on') . ' ' . __($type == 'same_post_comment' ? 'a post you follow' : 'your post') . ' ' . $post_substr;
            // $this->msg = 'timeline_interaction_msg';
            // $data = __($this->msg, ['name' => $this->commenter->name, ''])
        }
        elseif($obj instanceOf  $like_model){
            $this->type         = 'like';
            $this->like         = $obj;
            $this->reactor      = $this->like->liker;
            $liker_name = $this->like->liker->name;
            if($this->like->likeable_type == 'App\Models\Post')
            {
                $this->post         = $this->like->likeable;
                $post_substr        = \Str::words($this->post->content, 3, '...');
                if($this->like->is_like)
                {
                    $this->title        = __('Post Like');
                    $this->msg          = $liker_name . ' ' . __('has liked') . ' ' . __('your post') . ' ' . $post_substr;
                }
                else
                {
                    $this->title        = __('Post Dislike');
                    $this->msg          = $liker_name . ' ' . __('has disliked') . ' ' . __('your post') . ' ' . $post_substr;
                }
            }
            if($this->like->likeable_type == 'App\Models\Comment')
            {
                $this->comment  = $this->like->likeable;
                $this->post     = $this->comment->post;
                $comment_substr        = \Str::words($this->comment->content, 3, '...');
                if($this->like->is_like){
                    $this->title        = __('Comment Like');
                    $this->msg          = $liker_name . ' ' . __('has liked') . ' ' . __('your comment') . ' ' . $comment_substr;
                }else{
                    $this->title        = __('Comment Dislike');
                    $this->msg          = $liker_name . ' ' . __('has disliked') . ' ' . __('your comment') . ' ' . $comment_substr;
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
        if($this->type == 'following_post')
            return ($notifiable->is_notifiable && $notifiable->notification_settings->followings_posts) ? ['database'] : [];
        return ($notifiable->is_notifiable && $notifiable->notification_settings->timeline_interactions) ? ['database'] : [];
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
        $return = mb_convert_encoding($return, 'UTF-8', 'UTF-8');

        if($this->type == 'comment')
            $return['object_id'] = $this->comment->id;
        elseif($this->type == 'like')
            $return['object_id'] = $this->like->id;
        elseif($this->type == 'following_post')
            $return['object_id'] = $this->reactor->id;
        elseif($this->type == 'post_share')
            $return['object_id'] = $return['id'];

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
