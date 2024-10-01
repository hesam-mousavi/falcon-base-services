<?php

namespace FalconBaseServices\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends BaseModel
{

    public const CREATED_AT = 'comment_date';

    protected $table = 'comments';

    protected $primaryKey = 'comment_ID';

    protected $guarded = ['comment_ID'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'comment_post_ID', 'ID');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this, 'comment_parent', 'comment_ID');
    }

    public function meta(string $meta_key)
    {
        $meta_value = ($this->metas()->where('meta_key', $meta_key)->first())->meta_value;

        return is_serialized($meta_value) ? unserialize($meta_value) : $meta_value;
    }

    public function metas(): HasMany
    {
        return $this->hasMany(CommentMeta::class, 'comment_id', 'comment_ID');
    }

}
