<?php
/**
 * Description of CommentObserver
 *
 * @author mhamed
 */

namespace ArabiaIOClone\Observers;

use ArabiaIOClone\Repositories\CommentRepositoryInterface;
use ArabiaIOClone\Repositories\NotificationRepositoryInterface;
use ArabiaIOClone\Repositories\PostRepositoryInterface;
use ArabiaIOClone\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;



class CommentObserver extends AbstractObserver 
{
    
    protected $users;
    protected $posts;
    protected $comments;
    protected $notifications;
    
    public function __construct(
                                UserRepositoryInterface $users,
                                CommentRepositoryInterface $comments,    
                                PostRepositoryInterface $posts,
                                NotificationRepositoryInterface $notifications
                                ) 
    {
        $this->users = $users;
        $this->posts = $posts;
        $this->comments = $comments;
        $this->notifications = $notifications;
        
        Event::listen('eloquent.moved*', function($node){
            $this->moved($node);
        });
        
    }
    
    public function moved($comment)
    {
        
        if($comment->user_id != $comment->parent()->get()->first()->user_id)
        {
            $this->notifications->createCommentOnCommentNotification($comment);
        }
            
        
    }
    
    public function created($comment) 
    {
        parent::created($comment);
        
        
            
        if ($comment->user_id != $comment->post()->user_id)
        {
            $this->notifications->createCommentOnPostNotification($comment);
        }
            
        return $comment;
        

    }
    
    
    
    public  function saved($comment)
    {
        parent::saved($comment);
        
        
        
    }
            
            
          
}