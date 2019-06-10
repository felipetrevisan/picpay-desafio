<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="UserPayload",
 * ),
 * @SWG\Property(ref="#/definitions/Accounts", property="accounts"),
 * @SWG\Property(ref="#/definitions/User", property="user")
 */
class UserPayload extends Model
{ }
