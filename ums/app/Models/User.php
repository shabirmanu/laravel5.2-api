<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
/**
 * @SWG\Definition(
 *      definition="User",
 *      required={},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      )
 * )
 */
class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

     /**
       * Query scope function.
       *
       * @param $query
       */
      public function scopeMatchCredentials($query, $input) {
          $query->where('email', '=', $input['email']);
          $query->where('password', '=', bcrypt($input['password']));
      }

    /**
       * Query scope function.
       *
       * @param $query
       */
      public function scopeGetEmail($query, $email) {
          $query->where('email', '=', $email);
      }

    /**
     * Apps that are authorized to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apps() {
        return $this->belongsToMany('App\Models\App', 'app_user', 'user_id', 'app_key');
    }

    /**
     * Roles that are associated with this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function getRoleListAttribute() {
        return $this->roles()->lists('id')->toArray();
    }

    public function getAppListAttribute() {
        return $this->apps()->lists('apps.app_key')->toArray();
    }

}