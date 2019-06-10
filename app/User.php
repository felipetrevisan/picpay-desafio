<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="User",
 * ),
 * @SWG\Property(type="string", property="cpf"),
 * @SWG\Property(type="string", property="email"),
 * @SWG\Property(type="string", property="full_name"),
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="password"),
 * @SWG\Property(type="string", property="phone_number"),
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email',
        'cpf', 'phone_number',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function consumer()
    {
        return $this->hasOne('App\Consumer', 'user_id', 'id');
    }

    public function seller()
    {
        return $this->hasOne('App\Seller', 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function getAccountType()
    {
        if ($this->consumer instanceof \App\Consumer) {
            return 'Consumer';
        }

        if ($this->seller instanceof \App\Seller) {
            return 'Seller';
        }

        return 'User';
    }
}
