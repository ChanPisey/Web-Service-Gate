<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CardList extends Model
{
    protected $table = "guest_card_list";
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
    public function guestList(){
        return $this->hasMany('App\IdentifyCard','guest_card_id','id');
    }

}
