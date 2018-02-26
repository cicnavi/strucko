<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'term_id',
        'translation_id',
        'user_id',
    ];
    
    public function scopeGreaterThanRejected($query)
    {
        $query->where('status_id', '>', 250);
    }
    
    /**
     * Translation is owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    /**
     * Translation belongs by term.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo('App\Models\Term');
    }
    
    /**
     * Translation is owned by translation term.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function translation()
    {
        return $this->belongsTo('App\Models\Term');
    }
    
    /**
     * Translation has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
    
    /**
     * Translation can have many votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany('App\Models\TranslationVote');
    }
}
