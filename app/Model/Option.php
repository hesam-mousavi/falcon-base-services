<?php

namespace FalconBaseServices\Model;

class Option extends BaseModel
{

    public $timestamps = false;

    protected $table = 'options';

    protected $primaryKey = 'option_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['option_id'];

}
