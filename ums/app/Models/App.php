<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['app_key' , 'app_name'];
    protected $primaryKey = 'app_key';
    public $incrementing = false;

    /**
     * Users that are registered through this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('App\Models\User', 'app_user', 'user_id', 'app_key');
    }
}
