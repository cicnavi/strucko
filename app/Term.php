<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * Attributes that are allowed to be inserted for the Term model.
     * slug, slug_unique, synonym_id, user_id are defined in the store() method.
     *
     * @var array
     */
    protected $fillable = [
        'term',
        'abbreviation',
        'slug',
        'slug_unique',
        'synonym_id',
        'user_id',
        'language_id',
        'term_status_id',
        'part_of_speech_id',
        'scientific_branch_id'
    ];

    // TODO: implement the scopeApproved using ranks, if possible.
    public function scopeApproved($query)
    {
        $query->where('term_status_id', 3);
    }

    /**
     * Term is owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Term is in a language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    /**
     * Term has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function termStatus()
    {
        return $this->belongsTo('App\TermStatus');
    }

    /**
     * Term belongs to synonym.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function synonym()
    {
        return $this->belongsTo('App\Synonym');
    }

    /**
     * Term belongs to scientific branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificBranch()
    {
        return $this->belongsTo('App\ScientificBranch');
    }

    /**
     * Term belongs to part of speech.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partOfSpeech()
    {
        return $this->belongsTo('App\PartOfSpeech');
    }
}
