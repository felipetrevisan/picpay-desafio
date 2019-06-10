<?php

namespace App;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="CreateConsumerRequest",
 * )
 */
class CreateConsumerRequest
{
    /**
     * @SWG\Property
     * @var string
     */
    public $user_id;

    /**
     * @SWG\Property
     * @var string
     */
    public $username;
}
