<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="Accounts",
 * ),
 * @SWG\Property(ref="#/definitions/Consumer", property="consumer"),
 * @SWG\Property(ref="#/definitions/Seller", property="seller")
 */
class Accounts extends Model
{ }
