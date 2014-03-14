<?php

namespace ArabiaIOClone\Presenters;

use ArabiaIOClone\Helpers\ArabicDateDiffForHumans;
use Comment;
use McCool\LaravelAutoPresenter\BasePresenter;
use \Auth;
/**
 * Description of CommentPresenter
 *
 * @author Hichem MHAMED
 */
class CommentPresenter extends BasePresenter 
{
    public function __construct(Comment $comment)
    {
        $this->resource = $comment;
    }
    
    public function subcomment()
    {
        return $this->resource->getLevel() > 0 ? 'subcomment' : '';
        
    }
    
    public function getNestedWidth()
    {
        return 940 - (21 * $this->resource->getLevel());
    }
    
    public function getCreationDateDiffForHumans()
    {
        return ArabicDateDiffForHumans::translateFromEnglish($this->resource->created_at->diffForHumans());
    }
    
    public function canEdit()
    {
        if($this->resource->user()->id == Auth::user()->id)
        {
            return true;
        }else
        {
            return false;
        }
    }
}

?>
