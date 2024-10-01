<?php

namespace FalconBaseServices\Model;


class Term extends BaseModel
{

    public $timestamps = false;

    protected $table = 'terms';

    protected $primaryKey = 'term_id';

    protected $guarded = ['term_id'];

}
