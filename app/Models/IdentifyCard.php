<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class IdentifyCard extends Model
{
    protected $table = "identify_cards";
    protected $fillable = [
        'card_number', 'checkin_date', 'checkout_date'
    ];
    public function point_inout(){

        return $this->hasOne('App\Form','id','port_id');
    }
    public function cardList(){
        return $this->belongsTo('App\CardList','guest_card_id','id');
    }
    public function checkinBy(){
        return $this->hasOne('App\User','id','checkin_by');
    }
    public function checkoutBy(){
        return $this->hasOne('App\User','id','checkout_by');
    }
    public function gate(){
        return $this->hasOne('App\Gate','id','gate_id');
    }
}
