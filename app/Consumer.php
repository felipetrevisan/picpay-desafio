<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="Consumer",
 * ),
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="user_id"),
 * @SWG\Property(type="string", property="username")
 */
class Consumer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consumers';

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
        'user_id', 'username',
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
        return $this->hasOne('App\User')->withDefault();
    }
}
