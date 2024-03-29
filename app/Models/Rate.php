<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
	public $table = 'rate';

	protected $fillable = [
		'staff_id',
		'emotion_id',
		'organization',
		'service','child'
	];
}
