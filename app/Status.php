<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * Attributes that are allowed to be inserted for the Status model.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'status',
    ];
    
    /**
     * Status may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
