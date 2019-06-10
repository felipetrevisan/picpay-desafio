<?php

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * /users [GET]
     */
    public function testShouldReturnAllUsers()
    {
        factory('App\User', 10)->create();

        $this->get('users', []);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                '*' =>
                [
                    'id',
                    'full_name',
                    'email',
                    'cpf',
                    'phone_number',
                    'account_type',
                ]
            ],
            'meta' => [
                '*' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ]
            ]
        ]);
    }

    /**
     * /users/id [GET]
     */
    public function testShouldReturnExistingUser()
    {
        $user = factory('App\User')->create();

        $this->get(sprintf('users/%s', $user->id), []);

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' =>
                [
                    'id',
                    'full_name',
                    'email',
                    'cpf',
                    'phone_number',
                    'account_type',
                ]
            ]
        );
    }

    /**
     * /users/id [GET]
     */
    public function testShouldFailWhenUserNotExists()
    {
        $this->get(sprintf('users/%s', '5cfc1f2f4eb2ad076568f4e2'), []);

        $this->seeStatusCode(404);
        $this->seeJsonEquals(
            [
                'code' => 'USER_NOT_FOUND',
                'message' => 'O usuário não foi encontrado.'
            ]
        );
    }

    /**
     * /users [POST]
     */
    public function testShouldSuccessOnCreateUser()
    {
        $user = factory('App\User')->make();

        $parameters = [
            'full_name' => $user->full_name,
            'password' => $user->password,
            'email' => $user->email,
            'cpf' => $user->cpf,
            'phone_number' => $user->phone_number,
        ];

        $this->post('users', $parameters, []);

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' =>
                [
                    'id',
                    'full_name',
                    'email',
                    'cpf',
                    'phone_number',
                    'account_type',
                ]
            ]
        );
    }

    /**
     * /users [POST]
     */
    public function testShouldFailOnCreateUserWithAllMissingData()
    {
        $parameters = [
            'full_name' => '',
            'password' => '',
            'email' => '',
            'cpf' => '',
            'phone_number' => '',
        ];

        $this->post('users', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'full_name',
                'password',
                'email',
                'cpf',
                'phone_number',
            ]
        );
    }

    /**
     * /users [POST]
     */
    public function testShouldFailOnCreateUserWithInvalidEmail()
    {
        $user = factory('App\User')->make([
            'email' => 'picpay@gmail.'
        ]);

        $parameters = [
            'full_name' => $user->full_name,
            'password' => $user->password,
            'email' => $user->email,
            'cpf' => $user->cpf,
            'phone_number' => $user->phone_number,
        ];

        $this->post('users', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'email',
            ]
        );
    }

    /**
     * /users [POST]
     */
    public function testShouldFailOnCreateUserWithDuplicatedEmail()
    {
        $user = factory('App\User')->create();
        $user2 = factory('App\User')->make([
            'email' => $user->email
        ]);

        $parameters = [
            'full_name' => $user2->full_name,
            'password' => $user2->password,
            'email' => $user2->email,
            'cpf' => $user2->cpf,
            'phone_number' => $user2->phone_number,
        ];

        $this->post('users', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'email',
            ]
        );
    }

    /**
     * /users [POST]
     */
    public function testShouldFailOnCreateUserWithDuplicatedCpf()
    {
        $user = factory('App\User')->create();
        $user2 = factory('App\User')->make([
            'cpf' => $user->cpf
        ]);

        $parameters = [
            'full_name' => $user2->full_name,
            'password' => $user2->password,
            'email' => $user2->email,
            'cpf' => $user2->cpf,
            'phone_number' => $user2->phone_number,
        ];

        $this->post('users', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'cpf',
            ]
        );
    }

    /**
     * /users [POST]
     */
    public function testShouldFailOnCreateUserWithDuplicatedEmailAndCpf()
    {
        $user = factory('App\User')->create();
        $user2 = factory('App\User')->make([
            'email' => $user->email,
            'cpf' => $user->cpf
        ]);

        $parameters = [
            'full_name' => $user2->full_name,
            'password' => $user2->password,
            'email' => $user2->email,
            'cpf' => $user2->cpf,
            'phone_number' => $user2->phone_number,
        ];

        $this->post('users', $parameters, []);

        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'email',
                'cpf',
            ]
        );
    }

    public function tearDown()
    {
        //Artisan::call('migrate:reset');
        //parent::tearDown();
    }
}
