<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Internet($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker));

    $hash = \Illuminate\Support\Facades\Hash::make($faker->password);

    return [
        'full_name' => $faker->name,
        'email' => $faker->freeEmail,
        'password' => $hash,
        'cpf' => $faker->cpf,
        'phone_number' => $faker->cellphoneNumber,
    ];
});

$factory->define(App\Consumer::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Internet($faker));

    return [
        'username' => $faker->username,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});

$factory->define(App\Seller::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Internet($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Company($faker));

    return [
        'username'     => $faker->username,
        'fantasy_name' => $faker->company,
        'social_name'  => $faker->company,
        'cnpj'         => $faker->cnpj,
        'user_id'      => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
