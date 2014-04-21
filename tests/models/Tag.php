<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tag';

    protected $primaryKey = 'tag_id';

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes = array(
            'tag' => ''
        );
        parent::__construct($attributes);
    }

    /**
     * @return BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany( 'Post' );
    }

}