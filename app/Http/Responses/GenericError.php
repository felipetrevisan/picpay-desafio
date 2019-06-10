<?php

namespace App;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="GenericError",
 * )
 */
class GenericError
{
    /**
     * @SWG\Property
     * @var string
     */
    public $code;

    /**
     * @SWG\Property
     * @var string
     */
    public $message;
}
