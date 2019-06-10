<?php

namespace App\Transformers;

use App\Consumer;
use League\Fractal;

class ConsumerTransformer extends Fractal\TransformerAbstract
{
    public function transform(Consumer $consumer)
    {
        return [
            'id'           => $consumer->id,
            'user_id'      => $consumer->user_id,
            'username'     => $consumer->username,
            'links'        => [
                [
                    'uri' => 'users/' . $consumer->user_id,
                ]
            ],
        ];
    }
}
