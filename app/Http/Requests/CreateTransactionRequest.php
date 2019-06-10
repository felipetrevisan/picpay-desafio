<?php

namespace App;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="CreateTransactionRequest",
 * )
 */
class CreateTransactionRequest
{
    /**
     * @SWG\Property
     * @var string
     */
    public $payee_id;

    /**
     * @SWG\Property
     * @var string
     */
    public $payer_id;

    /**
     * @SWG\Property
     * @var number
     */
    public $value;
}
