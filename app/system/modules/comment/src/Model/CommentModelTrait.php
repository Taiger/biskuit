<?php

namespace Biskuit\Comment\Model;

use Biskuit\Database\ORM\ModelTrait;

trait CommentModelTrait
{
    use ModelTrait;

    /**
     * @Deleting
     */
    public static function deleting($event, Comment $comment)
    {
        self::where(['parent_id = :old_parent'], [':old_parent' => $comment->id])->update(['parent_id' => $comment->parent_id]);
    }
}
