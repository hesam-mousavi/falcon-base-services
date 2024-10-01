<?php

namespace FalconBaseServices\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class PostMeta extends BaseModel
{

    public $timestamps = false;

    protected $table = 'postmeta';

    protected $primaryKey = 'meta_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['meta_id'];


    public function post(): HasOne
    {
        return $this->hasOne(Post::class, 'ID', 'post_id');
    }

}
