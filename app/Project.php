<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	//turn off mass assign block
    protected $guarded = [];

    public function path()
    {

    	return "/projects/{$this->id}";

    }

    public function owner()
    {
    	return $this->belongsTo(User::class);
    }
}
