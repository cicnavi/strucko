<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    protected $fillable = [
        'definition',
        'concept_id',
        'language_id',
        'term_id',
        'source',
        'link',
    ];
    
    public function scopeSuggested($query)
    {
        $query->where('status_id', 500);
    }
    
    public function scopeRejected($query)
    {
        $query->where('status_id', 250);
    }
    
    public function scopeApproved($query)
    {
        $query->where('status_id', 1000);
    }
    
    public function scopeLessThanApproved($query)
    {
        $query->where('status_id', '<', 1000);
    }
    
    public function scopeGreaterThanRejected($query)
    {
        $query->where('status_id', '>', 250);
    }
    
    /**
     * Definition belongs to term.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo('App\Models\Term');
    }
    
    /**
     * Definition belongs to concept.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function concept()
    {
        return $this->belongsTo('App\Models\Concept');
    }
    
    /**
     * Definition belongs to language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
    
    /**
     * Definition belongs to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    /**
     * Definition has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
    
    /**
     * Definition can have many votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany('App\Models\DefinitionVote');
    }
}
