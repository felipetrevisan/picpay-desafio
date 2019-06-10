<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="Seller",
 * ),
 * @SWG\Property(type="string", property="cnpj"),
 * @SWG\Property(type="string", property="fantasy_name"),
 * @SWG\Property(type="string", property="social_name"),
 * @SWG\Property(type="string", property="username"),
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="user_id")
 */
class Seller extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sellers';

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
        'user_id', 'username', 'cnpj',
        'fantasy_name', 'social_name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
