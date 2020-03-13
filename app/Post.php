<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Create a relationship with users table
    public function user(){
        return $this->belongsTo('App\User');
    }
}
