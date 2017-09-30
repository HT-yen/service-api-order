<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserUpdateRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface as UserRepository;
use App\Http\Requests\Api\UserCreateRequest;
use Illuminate\Http\Response;

class UserController extends ApiController
{
    protected $userRepository;

    /**
     * UserAPIController constructor.
     *
     * @param User $user Dependence injection
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => $this->userRepository->getAllUsers(),'success' => true], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request request store data by admin
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $userRepository = $this->userRepository->create($request->all());
        if (isset($request->is_admin)) {
            if ($request->is_admin == 0) {
                $userRepository->roles()->sync('user');
            } else {
                $userRepository->roles()->sync('admin');
            }
        }
        if (!$userRepository) {
            return response()->json(['success' => false, 'message' => __('Error during create user')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $userRepository, 'success' => true], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request request store data by user
     *
     * @return \Illuminate\Http\Response
     */
    public function register(UserCreateRequest $request)
    {
        $userRepository = $this->userRepository->create($request->except('is_admin'));
        if (!$userRepository) {
            $userRepository->roles()->sync('user');
            return response()->json(['success' => false, 'message' => __('Error during create user')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $userRepository, 'success' => true], Response::HTTP_OK);
    }


    /**
     * Login system and get token for client.
     *
     * @param \Illuminate\Http\Request $request request create
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $http = new Client();
        try {
            $response = $http->post(env('APP_URL').'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('CLIENT_ID'),
                    'client_secret' => env('CLIENT_SECRET'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);
            return response()->json([
                'data' => json_decode((string) $response->getBody(), true),
                'success' => true
            ], Response::HTTP_OK);
        } catch (ClientException $ex) {
            return  response()->json([
                json_decode($ex->getResponse()->getBody(), true)
            ], $ex->getCode());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request request get user information by themselves(admin or user)
     *
     * @return mixed
     */
    public function showProfile(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['success' => false, 'message' => __('Error during get current user')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $request->user(),'success' => true], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @return mixed
     */
    public function show($id)
    {
        $userRepository = $this->userRepository->showUser($id);
        if (!$userRepository) {
            return response()->json(['success' => false, 'message' => __('Error during get current user')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $userRepository,'success' => true], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request request update by themselves(admin or user)
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UserUpdateRequest $request)
    {
        if ($this->userRepository->update($request->all(), $request->user()->id)) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }

        return response()->json(['success' => false, 'message' => __('Error during update current user!')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request request update user by admin
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request)
    {
        $userRepository =$this->userRepository->update($request->all(), $request->id); 

        if (isset($request->is_admin)) {
            if ($request->is_admin == 0) {
                $userRepository->roles()->sync('user');
            } else {
                $userRepository->roles()->sync('admin');
            }
        }

        if ($userRepository) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }

        return response()->json(['success' => false, 'message' => __('Error during update current user!')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id id delete
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->userRepository->find($id)->delete()) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }
        return response()->json(['success' => false, 'message' => __('Error during delete user')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
