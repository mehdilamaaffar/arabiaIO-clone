<?php

use ArabiaIOClone\Repositories\CommentRepositoryInterface;
use ArabiaIOClone\Repositories\CommunityRepositoryInterface;
use ArabiaIOClone\Repositories\PostRepositoryInterface;
use ArabiaIOClone\Repositories\UserRepositoryInterface;
use ArabiaIOClone\Repositories\VoteRepositoryInterface;
use ArabiaIOClone\Repositories\NotificationRepositoryInterface;
use Illuminate\Config\Repository;

class BaseController extends Controller {

    protected $communities;
    protected $comments;
    protected $posts;
    protected $users;
    protected $votes;
    protected $notifications;
    protected $config;
    
    public function __construct(
            CommunityRepositoryInterface $communities,
            CommentRepositoryInterface $comments,
            PostRepositoryInterface $posts,
            UserRepositoryInterface $users,
            VoteRepositoryInterface $votes,
            NotificationRepositoryInterface $notifications,
            Repository $config
                                ) 
    {
        $this->communities = $communities;
        $this->comments = $comments;
        $this->posts = $posts;
        $this->users = $users;
        $this->votes = $votes;
        $this->notifications = $notifications;
        $this->config = $config;
        $this->__init();
        
                
    }
    
    
    protected function __init()
    {
        $navLinks = $this->communities->findMostRecent();
        $notificationsCount = 0;
        $config = $this->config;
        if (Auth::check())
        {
            $notificationsCount = $this->notifications->findUnreadNotificationsCount(Auth::user());
        }
        
        View::share(compact('navLinks','notificationsCount','config'));
    }


    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
            if ( ! is_null($this->layout))
            {
                    $this->layout = View::make($this->layout);
            }
    }

}