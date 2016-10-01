<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    	'user_id',
    	'name',
    	'description',
    	'status'
    ];

    public function owner()
    {
    	return $this->belongsTo('App\User');
    }
}
