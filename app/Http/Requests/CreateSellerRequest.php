<?php

namespace App;

/**
 * @SWG\Definition(
 *       type="object",
 *       title="CreateSellerRequest",
 * )
 */
class CreateSellerRequest
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

    /**
     * @SWG\Property
     * @var string
     */
    public $cnpj;

    /**
     * @SWG\Property
     * @var string
     */
    public $fantasy_name;

    /**
     * @SWG\Property
     * @var string
     */
    public $social_name;
}
