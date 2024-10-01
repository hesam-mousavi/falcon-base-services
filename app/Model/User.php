<?php

namespace FalconBaseServices\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends BaseModel
{

    public $timestamps = false;

    protected $table = 'users';

    protected $primaryKey = 'ID';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['ID'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['user_pass', 'user_activation_key'];

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('comment_approved', 1);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'ID');
    }

    public function publishedPosts(): HasMany
    {
        return $this->posts()->where('post_status', 'publish');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_author', 'ID');
    }

    public function meta(string $meta_key)
    {
        $meta_value = ($this->metas()->where('meta_key', $meta_key)->first())->meta_value;

        return is_serialized($meta_value) ? unserialize($meta_value) : $meta_value;
    }

    public function metas(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'ID');
    }

    public function role(): string|null
    {
        $roles = get_user_meta($this->ID, 'wp_capabilities');

        foreach ($roles as $role) {
            return (array_keys($role)[0]);
        }

        return null;
    }

}
