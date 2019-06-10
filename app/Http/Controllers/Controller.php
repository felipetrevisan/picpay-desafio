<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *      schemes={"http"},
 *      host=SWAGGER_LUME_CONST_HOST,
 *      basePath="/",
 *      @SWG\Info(
 *          version="1.0.0",
 *          description="API Utilizada para gestão do Cadastro de Usuários e Transações do PicPay",
 *          title="Serviço de Cadastro de Usuários e Transações")
 *      ),
 *      @SWG\Tag(name="Pagamentos", description="Transactions Controller"),
 *      @SWG\Tag(name="Usuários", description="User Controller")
 * ),
 * 
 */
class Controller extends BaseController
{
    public function customResponse($message = 'success', $status = 200, $code = '')
    {
        return response(['code' =>  $code, 'message' => $message], $status);
    }
}
