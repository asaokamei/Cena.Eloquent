<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
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
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $default = array(
            'status' => self::STATUS_PUBLIC
        );
        $attributes += $default;
        parent::__construct($attributes);
    }
    
    /**
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo( 'Post' );
    }

}