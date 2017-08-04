<?php

namespace App;

use Illuminate\Auth\Authenticatable;
// use Illuminate\Foundation\Auth\Access\Authorizable;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'request_data_on_registration'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'token'];
    
    public static function boot()
    {
        parent::boot();
        static::creating(function($user){
            $user->token = str_random(30);
        });
    }
    
    /**
     * A user may have many terms.
     * 
     * @return HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
    
    /**
     * A user may have many definitions.
     * 
     * @return HasMany
     */
    public function definitions()
    {
        return $this->hasMany('App\Definition');
    }
    
    /**
     * A user has a role.
     * 
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }
    
    /**
     * A user may have many synonym merge suggestions.
     * 
     * @return HasMany
     */
    public function mergeSuggestions()
    {
         return $this->hasMany('App\MergeSuggestion');
    }
}
