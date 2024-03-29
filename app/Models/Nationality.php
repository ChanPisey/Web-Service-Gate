<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $table = "nationalities";

    public function users(){
        return $this->hasOne('App\User','id','user_id');
    }
}
