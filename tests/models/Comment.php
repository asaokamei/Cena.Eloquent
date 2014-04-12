<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Eloquent
{
    const STATUS_PUBLIC = 1;
    const STATUS_HIDE   = 9;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comment';

    protected $primaryKey = 'comment_id';

    /**
     * @return BelongsTo
     */
    public function comments()
    {
        return $this->belongsTo( 'Post' );
    }

}