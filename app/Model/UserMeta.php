<?php

namespace FalconBaseServices\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class UserMeta extends BaseModel
{

    public $timestamps = false;

    protected $table = 'usermeta';

    protected $primaryKey = 'umeta_id';


    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['umeta_id'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'ID', 'user_id');
    }

}
