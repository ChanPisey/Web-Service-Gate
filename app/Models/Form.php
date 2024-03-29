<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
	protected $table = "port_in_outs";

//    protected $fillable = [
//        'institution_id', 'unit_id', 'nationality_id','user_id'
//    ];
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
	public function source(){

	    return $this->hasOne('App\Source','id','institution_id');
	}
	public function unit(){

	    return $this->hasOne('App\Unit','id','unit_id');
	}
	public function nationality(){

	    return $this->hasOne('App\Nationality','id','nationality_id');
	}

	public function cardimage(){

	    return $this->hasOne('App\CardImage','port_id','id');
	}

	public function realimage(){

	    return $this->hasOne('App\RealImage','port_id','id');
	}

	public function identifycard(){

	    return $this->hasOne('App\IdentifyCard','port_id','id');
	}

	public function users(){
        return $this->hasOne('App\User','id','user_id');
    }
}


