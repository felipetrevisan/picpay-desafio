<?php

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class SellerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * /users/sellers [POST]
     */
    public function testShouldSuccessOnCreateSeller()
    {
        $seller = factory('App\Seller')->make();

        $parameters = [
            'user_id'      => $seller->user_id,
            'username'     => $seller->username,
            'cnpj'         => $seller->cnpj,
            'fantasy_name' => $seller->fantasy_name,
            'social_name'  => $seller->social_name,
        ];

        $this->post('users/sellers', $parameters, []);

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' =>
                [
                    'id',
                    'user_id',
                    'username',
                    'cnpj',
                    'fantasy_name',
                    'social_name',
                ]
            ]
        );
    }

    /**
     * /users/sellers [POST]
     */
    public function testShouldFailOnCreateConsumerWhenUserIsSeller()
    {
        $seller = factory('App\Seller')->create();
        $consumer = factory('App\Consumer')->make([
            'user_id' => $seller->user_id
        ]);

        $parameters = [
            'user_id'  => $consumer->user_id,
            'username' => $consumer->username,
        ];

        $this->post('users/consumers', $parameters, []);

        $this->seeStatusCode(401);
        $this->seeJsonEquals(
            [
                'code' => 'USER_ACCOUNT_SELLER',
                'message' => 'O usuário já possui uma conta de vendedor associada à esse perfil.'
            ]
        );
    }

    /**
     * /users/sellers [POST]
     */
    public function testShouldFailOnCreateSellerWithInvalidUser()
    {
        $seller = factory('App\Seller')->make();

        $parameters = [
            'user_id'      => '5cfc1f2f4eb2ad076568f4e2',
            'username'     => $seller->username,
            'cnpj'         => $seller->cnpj,
            'fantasy_name' => $seller->fantasy_name,
            'social_name'  => $seller->social_name,
        ];

        $this->post('users/sellers', $parameters, []);

        $this->seeStatusCode(404);
        $this->seeJsonEquals(
            [
                'code' => 'USER_NOT_FOUND',
                'message' => 'O usuário não foi encontrado.'
            ]
        );
    }

    /**
     * /users/sellers [POST]
     */
    public function testShouldFailOnCreateConsumerWithAllMissingData()
    {
        $parameters = [
            'user_id'      => '',
            'username'     => '',
            'cnpj'         => '',
            'fantasy_name' => '',
            'social_name'  => '',
        ];

        $this->post('users/sellers', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'user_id',
                'username',
                'cnpj',
                'fantasy_name',
                'social_name',
            ]
        );
    }

    public function tearDown()
    {
        //Artisan::call('migrate:reset');
        //parent::tearDown();
    }
}
