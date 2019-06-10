<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal;

class SellerTransformer extends Fractal\TransformerAbstract
{
    public function transform(Seller $seller)
    {
        return [
            'id'           => $seller->id,
            'user_id'      => $seller->user_id,
            'username'     => $seller->username,
            'cnpj'         => $seller->cnpj,
            'fantasy_name' => $seller->fantasy_name,
            'social_name'  => $seller->social_name,
            'links'        => [
                [
                    'uri' => 'users/' . $seller->user_id,
                ]
            ],
        ];
    }
}
