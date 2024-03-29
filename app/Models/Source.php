<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = "sources";

    public function users(){
        return $this->hasOne('App\User','id','user_id');
    }
}
