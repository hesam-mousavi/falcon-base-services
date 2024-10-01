<?php

namespace FalconBaseServices\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class CommentMeta extends BaseModel
{

    public $timestamps = false;

    protected $table = 'commentmeta';

    protected $primaryKey = 'meta_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['meta_id'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'ID', 'comment_id');
    }

}
