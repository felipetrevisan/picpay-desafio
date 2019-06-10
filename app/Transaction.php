<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="Transaction",
 * ),
 * @SWG\Property(type="string", property="id"),
 * @SWG\Property(type="string", property="payee_id"),
 * @SWG\Property(type="string", property="payer_id"),
 * @SWG\Property(type="string", property="transaction_date", format="datetime"),
 * @SWG\Property(type="number", property="value")
 */
class Transaction extends Model
{
    const CREATED_AT = 'transaction_date';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

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
        'payee_id', 'payer_id', 'value',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'transaction_date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
