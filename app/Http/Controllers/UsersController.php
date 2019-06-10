<?php

namespace App\Http\Controllers;

use App\User;
use App\Consumer;
use App\Seller;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\UserTransformer;
use App\Transformers\ConsumerTransformer;
use App\Transformers\SellerTransformer;

class UsersController extends Controller
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
     * GET /users
     * 
     * @return array
     */
    /**
     * @SWG\Get(
     *  path="/users",
     *  tags={"Usuários"},
     *  summary="Realiza uma listagem de usuários baseada em filtros. Se um filtro não for especificado, deve listar os usuários odernados por nome.",
     *  produces={"application/json;charset=UTF-8"},
     *  operationId="listUsersUsingGET",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/User")
     *          )
     *      ),
     *      @SWG\Response(
     *          response="500",
     *          description="Erro interno do servidor",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     * 
     *      @SWG\Parameter(
     *          description="q",
     *          in="query",
     *          name="q",
     *          required=true,
     *          type="string"
     *      )
     * )
     * 
     */
    public function listUsersUsingGET(Request $request)
    {
        $query = $request->query('q', '');

        if ($query !== '/users') {
            $paginator = User::with(['consumer', 'seller'])
                ->where('full_name', 'like', $query . '%')
                ->orWhereHas('consumer', function ($q) use ($query) {
                    $q->orWhere('username', 'like', $query . '%');
                })
                ->orWhereHas('seller', function ($q) use ($query) {
                    $q->orWhere('username', 'like', $query . '%');
                });
        } else {
            $paginator = User::with(['consumer', 'seller']);
        }

        $paginator = $paginator->paginate();
        $users = $paginator->getCollection();

        $resource = new Collection($users, new UserTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @SWG\Get(
     *  path="/users/{user_id}",
     *  tags={"Usuários"},
     *  summary="Retorna dados detalhados de um usuário.",
     *  consumes={"application/json;charset=UTF-8"},
     *  produces={"application/json;charset=UTF-8"},
     *  operationId="getUserUsingGET",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response="404",
     *          description="Usuário não encontrado",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     *      @SWG\Response(
     *          response="500",
     *          description="Erro interno do servidor",
     *          @SWG\Schema(ref="#/definitions/GenericError")
     *      ),
     * 
     *      @SWG\Parameter(
     *          description="user_id",
     *          in="path",
     *          name="user_id",
     *          required=true,
     *          type="string"
     *      )
     * )
     */
    public function getUserUsingGET($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->customResponse('O usuário não foi encontrado.', 404, 'USER_NOT_FOUND');
        }

        $resource = new Item($user, new UserTransformer);

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @SWG\Post(
     *  path="/users",
     *  tags={"Usuários"},
     *  summary="Realiza o cadastro de novos usuários no sistema.",
     *  consumes={"application/json;charset=UTF-8"},
     *  produces={"application/json;charset=UTF-8"},
     *  operationId="createUserUsingPOST",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response="201",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
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
     *              @SWG\Schema(ref="#/definitions/CreateUserRequest")
     *      )
     * )
     */
    public function createUserUsingPOST(Request $request)
    {
        //validate request parameters
        $this->validate($request, [
            'full_name' => 'bail|required',
            'email' => 'bail|required|email|unique:users,email',
            'password' => 'bail|required',
            'cpf' => 'bail|required|unique:users,cpf',
            'phone_number' => 'bail|required',
        ]);

        $user = User::create($request->all());
        $resource = new Item($user, new UserTransformer);

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @SWG\Post(
     *  path="/users/consumers",
     *  tags={"Usuários"},
     *  summary="Realiza o cadastro de um perfil de consumidor para um usuário.",
     *  consumes={"application/json;charset=UTF-8"},
     *  produces={"application/json;charset=UTF-8"},
     *  operationId="createConsumerUsingPOST",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response="201",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response="401",
     *          description="Usuário não encontrado",
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
     *              @SWG\Schema(ref="#/definitions/CreateConsumerRequest")
     *      )
     * )
     */
    public function createConsumerUsingPOST(Request $request)
    {
        //validate request parameters
        $this->validate($request, [
            'user_id' => 'bail|required',
            'username' => 'bail|required|unique:consumers,username',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return $this->customResponse('O usuário não foi encontrado.', 404, 'USER_NOT_FOUND');
        }

        $seller = Seller::where('user_id', $user->id)->count();

        if ($seller) {
            return $this->customResponse('O usuário já possui uma conta de vendedor associada à esse perfil.', 401, 'USER_ACCOUNT_SELLER');
        }

        $consumer = Consumer::create($request->all());
        $resource = new Item($consumer, new ConsumerTransformer);

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @SWG\Post(
     *  path="/users/sellers",
     *  tags={"Usuários"},
     *  summary="Realiza o cadastro de um perfil de lojista para um usuário.",
     *  consumes={"application/json;charset=UTF-8"},
     *  produces={"application/json;charset=UTF-8"},
     *  operationId="createSellerUsingPOST",
     *      @SWG\Response(
     *          response="200",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response="201",
     *          description="OK",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response="401",
     *          description="Usuário não encontrado",
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
     *              description="user_id",
     *              in="path",
     *              name="user_id",
     *              required=true,
     *              type="integer",
     *              format="int64"
     *      ),
     *      @SWG\Parameter(
     *              description="request",
     *              in="body",
     *              name="request",
     *              required=true,
     *              @SWG\Schema(ref="#/definitions/CreateSellerRequest")
     *      )
     * )
     */
    public function createSellerUsingPOST(Request $request)
    {
        //validate request parameters
        $this->validate($request, [
            'user_id' => 'bail|required',
            'username' => 'bail|required|unique:sellers,username',
            'cnpj' => 'bail|required|unique:sellers,cnpj',
            'fantasy_name' => 'bail|required',
            'social_name' => 'bail|required',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return $this->customResponse('O usuário não foi encontrado.', 404, 'USER_NOT_FOUND');
        }

        $consumer = Consumer::where('user_id', $user->id)->count();

        if ($consumer) {
            return $this->customResponse('O usuário já possui uma conta de consumidor associada à esse perfil.', 401, 'USER_ACCOUNT_CONSUMER');
        }

        $seller = Seller::create($request->all());
        $resource = new Item($seller, new SellerTransformer);

        return $this->fractal->createData($resource)->toArray();
    }
}
