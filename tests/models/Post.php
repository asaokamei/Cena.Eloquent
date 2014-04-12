<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Eloquent
{
    const STATUS_DRAFT  = 1;
    const STATUS_PUBLIC = 2;
    const STATUS_HIDE   = 9;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post';

    protected $primaryKey = 'post_id';

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany( 'Comment' );
    }

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany( 'Tag' );
    }
}