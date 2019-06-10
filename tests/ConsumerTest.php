<?php

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ConsumerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * /users/consumers [POST]
     */
    public function testShouldSuccessOnCreateConsumer()
    {
        $consumer = factory('App\Consumer')->make();

        $parameters = [
            'user_id'  => $consumer->user_id,
            'username' => $consumer->username,
        ];

        $this->post('users/consumers', $parameters, []);

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' =>
                [
                    'id',
                    'user_id',
                    'username',
                ]
            ]
        );
    }

    /**
     * /users/consumers [POST]
     */
    public function testShouldFailOnCreateSellerWhenUserIsConsumer()
    {
        $consumer = factory('App\Consumer')->create();
        $seller = factory('App\Seller')->make([
            'user_id' => $consumer->user_id
        ]);

        $parameters = [
            'user_id'      => $seller->user_id,
            'username'     => $seller->username,
            'cnpj'         => $seller->cnpj,
            'fantasy_name' => $seller->fantasy_name,
            'social_name'  => $seller->social_name,
        ];

        $this->post('users/sellers', $parameters, []);

        $this->seeStatusCode(401);
        $this->seeJsonEquals(
            [
                'code' => 'USER_ACCOUNT_CONSUMER',
                'message' => 'O usuário já possui uma conta de consumidor associada à esse perfil.'
            ]
        );
    }

    /**
     * /users/consumers [POST]
     */
    public function testShouldFailOnCreateConsumerWithInvalidUser()
    {
        $consumer = factory('App\Consumer')->make();

        $parameters = [
            'user_id'  => '5cfc1f2f4eb2ad076568f4e2',
            'username' => $consumer->username,
        ];

        $this->post('users/consumers', $parameters, []);

        $this->seeStatusCode(404);
        $this->seeJsonEquals(
            [
                'code' => 'USER_NOT_FOUND',
                'message' => 'O usuário não foi encontrado.'
            ]
        );
    }

    /**
     * /users/consumers [POST]
     */
    public function testShouldFailOnCreateConsumerWithAllMissingData()
    {
        $parameters = [
            'user_id'  => '',
            'username' => '',
        ];

        $this->post('users/consumers', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'user_id',
                'username',
            ]
        );
    }

    public function tearDown()
    {
        //Artisan::call('migrate:reset');
        //parent::tearDown();
    }
}
