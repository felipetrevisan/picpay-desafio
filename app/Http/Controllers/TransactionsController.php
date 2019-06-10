<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\TransactionTransformer;

class TransactionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    /**
     * @SWG\Get(
     *  path="/transactions/{transaction_id}",
     *  tags={"Pagamentos"},
     *  summary="Retorna os detalhes de uma transação.",
     *  produces={"*\/*"},
     *  operationId="getTransactionUsingGET",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/Transaction")
     *      ),
     *      @SWG\Response(
     *          response="201",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/Transaction")
     *      ),
     *      @SWG\Response(
     *          response="404",
     *          description="Transação não encontrada",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     *      @SWG\Response(
     *          response="500",
     *          description="Erro interno do servidor",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     * 
     *      @SWG\Parameter(
     *          description="transaction_id",
     *          in="path",
     *          name="transaction_id",
     *          required=true,
     *          type="integer",
     *          format="int64"
     *      )
     * )
     */
    public function getTransactionUsingGET($id)
    {
        $transaction = Transaction::findOrFail($id);

        $resource = new Item($transaction, new TransactionTransformer);

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @SWG\Post(
     *  path="/transactions",
     *  tags={"Pagamentos"},
     *  summary="Cria uma nova transação entre duas contas do PicPay.",
     *  consumes={"application/json;charset=UTF-8"},
     *  produces={"application/json;charset=UTF-8"},
     *  operationId="createTransactionUsingPOST",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/Transaction")
     *      ),
     *      @SWG\Response(
     *          response="201",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/Transaction")
     *      ),
     *      @SWG\Response(
     *          response="401",
     *          description="Transação não autorizada",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     *      @SWG\Response(
     *          response="422",
     *          description="Erro de validação nos campos",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     *      @SWG\Response(
     *          response="500",
     *          description="Erro interno do servidor",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     * 
     *      @SWG\Parameter(
     *              description="request",
     *              in="body",
     *              name="request",
     *              required=true,
     *              @SWG\Schema(ref="#/definitions/CreateTransactionRequest")
     *      )
     * )
     */
    public function createTransactionUsingPOST(Request $request)
    {
        //validate request parameters
        $this->validate($request, [
            'payee_id' => 'bail|required',
            'payer_id' => 'bail|required',
            'value' => 'bail|required|numeric',
        ]);

        $payee = User::find($request->payee_id);
        $payer = User::find($request->payer_id);

        if (!$payee || !$payer) {
            return $this->customResponse('Transação recusada.', 401, 'NO_USER');
        }

        if ($payee == $payer) {
            return $this->customResponse('Transação recusada.', 401, 'TRANSACTION_REFUSED');
        }

        if ($request->value >= 100) {
            return $this->customResponse('Transação recusada.', 401, 'TRANSACTION_REFUSED');
        }

        $transaction = Transaction::create($request->all());
        $resource = new Item($transaction, new TransactionTransformer);

        return $this->fractal->createData($resource)->toArray();
    }
}
