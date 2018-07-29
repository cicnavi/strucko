<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartOfSpeech extends Model
{
    protected $guarded = ['id'];
    
    public function terms()
    {
        return $this->hasMany('App\Models\Term');
    }
}
