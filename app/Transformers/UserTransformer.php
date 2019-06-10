<?php

namespace App\Transformers;

use App\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'           => $user->id,
            'full_name'    => $user->full_name,
            'email'        => $user->email,
            'cpf'          => $user->cpf,
            'phone_number' => $user->phone_number,
            'account_type' => $user->getAccountType(),
            'links'        => [
                [
                    'uri' => 'users/' . $user->id,
                ]
            ],
        ];
    }
}
