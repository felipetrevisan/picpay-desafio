<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal;

class TransactionTransformer extends Fractal\TransformerAbstract
{
    public function transform(Transaction $transaction)
    {
        return [
            'id'               => $transaction->id,
            'payee_id'         => $transaction->payee_id,
            'payer_id'         => $transaction->payer_id,
            'transaction_date' => $transaction->transaction_date->format('d-m-Y'),
            'value'            => $transaction->value,
            'links'        => [
                [
                    'uri' => 'transactions/' . $transaction->id,
                ]
            ],
        ];
    }
}
