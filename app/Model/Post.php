<?php

namespace FalconBaseServices\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends BaseModel
{

    const CREATED_AT = 'post_date';

    const UPDATED_AT = 'post_modified';

    protected $table = 'posts';

    protected $primaryKey = 'ID';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['ID'];

    public function published()
    {
        return $this->where('post_status', 'publish');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'ID', 'post_author');
    }
}
