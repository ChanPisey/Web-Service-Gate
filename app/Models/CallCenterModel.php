<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CallCenterModel extends Model
{
    //
    protected $table="tblcallcenter";
    protected $fillable = ['callid','callfrom','callsrc','callto','destnum','starttime','duration','callStatus','callType','pinCode','trunkName','recording','save_date'];
}
