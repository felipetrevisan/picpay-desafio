<?php

namespace App;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="CreateUserRequest",
 * )
 */
class CreateUserRequest
{
    /**
     * @SWG\Property
     * @var string
     */
    public $cpf;

    /**
     * @SWG\Property
     * @var string
     */
    public $email;

    /**
     * @SWG\Property
     * @var string
     */
    public $full_name;

    /**
     * @SWG\Property
     * @var string
     */
    public $password;

    /**
     * @SWG\Property
     * @var string
     */
    public $phone_number;
}
