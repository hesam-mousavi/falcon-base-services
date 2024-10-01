<?php

namespace FalconBaseServices\Model;


class TermTaxonomy extends BaseModel
{

    public $timestamps = false;

    protected $table = 'term_taxonomy';

    protected $primaryKey = 'term_taxonomy_id';

    protected $guarded = ['term_taxonomy_id'];

}
