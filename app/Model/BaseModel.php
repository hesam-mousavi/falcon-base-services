<?php

namespace FalconBaseServices\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


class BaseModel extends Model
{

    protected $with_prefix = true;

    public function __construct()
    {
        Parent::__construct();
    }

    public function getTable(): string
    {
        $this->table = str_replace($this->getPrefix(), '', $this->table);

        if ($this->with_prefix && !empty($this->table)) {
            return $this->getPrefix().$this->table;
        } elseif ($this->with_prefix && empty($this->table)) {
            return $this->getPrefix().Str::snake(Str::pluralStudly(class_basename($this)));
        } elseif (!$this->with_prefix && !empty($this->table)) {
            return $this->table;
        }

        return Str::snake(Str::pluralStudly(class_basename($this)));
    }

    protected function getPrefix(): string
    {
        global $wpdb;
        return $wpdb->prefix;
    }

}
