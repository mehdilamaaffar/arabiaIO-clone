<?php

/**
 * Description of PostController
 *
 * @author Hichem MHAMED
 */

use ArabiaIOClone\Repositories\CommunityRepositoryInterface;
use ArabiaIOClone\Repositories\PostRepositoryInterface;
use ArabiaIOClone\Repositories\CommentRepositoryInterface;


class PostController extends BaseController
{
    
   
    
    
    
    public function getTop()
    {
        if(Auth::check())
        {
            $posts = $this->posts->findTopByUserSubscriptions(Auth::user());
        }else{
            $posts = $this->posts->findTop();
        }
        
        $latestComments = $this->comments->findLatestComments();
        
        return View::make('posts.browse')
                 ->with(compact('posts','latestComments'))
                ->render();
    }
    
    public function getMostRecent()
    {
        if(Auth::check())
        {
            $posts = $this->posts->findMostRecentByUserSubscriptions(Auth::user());
        }  
        else
        {
            $posts = $this->posts->findMostRecent();
        }
        $latestComments = $this->comments->findLatestComments();
        
        return View::make('posts.browse')
                 ->with(compact('posts','latestComments'))
                ->render();
    }
    
    public  function getMostPopular()
    {
        if(Auth::check())
        {
            $posts = $this->posts->findMostPopularByUserSubscriptions(Auth::user());
        }  else{
            $posts = $this->posts->findMostPopular();
        }
        
        $latestComments = $this->comments->findLatestComments();
        
        return View::make('posts.browse')
                ->with(compact('posts','latestComments'))
                
                ->render();
    }


    public function getDefault()
    {
        return $this->getMostPopular();
    }
    
    public function getSubmit()
    {
        $communities = $this->communities->findAll('created_at','asc')->lists('name','id');
        return View::make('posts.submit')
                ->with('communities',$communities);
    }
    
    public function postSubmit()
    {
        
        
        $form = $this->posts->getPostSubmitForm();
        if(!$form->isValid())
        {
            return Redirect::route('post-submit')
                    ->withInput()
                    ->withErrors($form->getErrors());
        }
        
        $data = $form->getInputData();
        $data['user_id'] = Auth::user()->id;

        $post = $this->posts->create($data);

        if($post)
        {
            return Redirect::route('default')
                ->with('success',[Lang::get('success.post_submit')]);
        }else
        {
            return Redirect::route('post-submit')
                    ->withInput()
                    ->withErrors(Lang::get('errors.post_submit'));
        }
        
    }
    
    
    public function getView($postId, $postSlug)
    {
        $post = $this->posts->findByIdAndSlug($postId,$postSlug);
        $comments = $this->comments->getSortedByPost($post);
        
        
        
        return View::make('posts.view')
                ->with(compact('post'))
                ->with(compact('comments'))
                ->render();
    }
}

?>
