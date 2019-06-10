<?php

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * /transactions [POST]
     */
    public function testShouldSuccessOnCreateTransaction()
    {
        $consumer = factory('App\Consumer')->create();
        $seller = factory('App\Seller')->create();

        $parameters = [
            'payee_id' => $consumer->user_id,
            'payer_id' => $seller->user_id,
            'value'    => 99,
        ];

        $this->post('transactions', $parameters, []);

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' =>
                [
                    'id',
                    'payee_id',
                    'payer_id',
                    'value',
                ]
            ]
        );
    }

    /**
     * /transactions [POST]
     */
    public function testShouldFailOnCreateTransactionWithValueGreaterThanOneHundred()
    {
        $consumer = factory('App\Consumer')->create();
        $seller = factory('App\Seller')->create();

        $parameters = [
            'payee_id' => $consumer->user_id,
            'payer_id' => $seller->user_id,
            'value'    => 100,
        ];

        $this->post('transactions', $parameters, []);

        $this->seeStatusCode(401);
        $this->seeJsonEquals(
            [
                'code'    => 'TRANSACTION_REFUSED',
                'message' => 'Transação recusada.',
            ]
        );
    }

    /**
     * /transactions [POST]
     */
    public function testShouldFailOnCreateTransactionWithInvalidPayee()
    {
        $seller = factory('App\Seller')->create();

        $parameters = [
            'payee_id' => '5cfc1f2f4eb2ad076568f4e2',
            'payer_id' => $seller->user_id,
            'value'    => 99,
        ];

        $this->post('transactions', $parameters, []);

        $this->seeStatusCode(401);
        $this->seeJsonEquals(
            [
                'code'    => 'NO_USER',
                'message' => 'Transação recusada.',
            ]
        );
    }

    /**
     * /transactions [POST]
     */
    public function testShouldFailOnCreateTransactionWithInvalidPayer()
    {
        $consumer = factory('App\Consumer')->create();

        $parameters = [
            'payee_id' => $consumer->user_id,
            'payer_id' => '5cfc1f2f4eb2ad076568f4e2',
            'value'    => 99,
        ];

        $this->post('transactions', $parameters, []);

        $this->seeStatusCode(401);
        $this->seeJsonEquals(
            [
                'code'    => 'NO_USER',
                'message' => 'Transação recusada.',
            ]
        );
    }

    /**
     * /transactions [POST]
     */
    public function testShouldFailOnCreateTransactionWithSamePayeeAndPayer()
    {
        $consumer = factory('App\Consumer')->create();

        $parameters = [
            'payee_id' => $consumer->user_id,
            'payer_id' => $consumer->user_id,
            'value'    => 99,
        ];

        $this->post('transactions', $parameters, []);

        $this->seeStatusCode(401);
        $this->seeJsonEquals(
            [
                'code'    => 'TRANSACTION_REFUSED',
                'message' => 'Transação recusada.',
            ]
        );
    }

    public function tearDown()
    {
        //Artisan::call('migrate:reset');
        //parent::tearDown();
    }
}
