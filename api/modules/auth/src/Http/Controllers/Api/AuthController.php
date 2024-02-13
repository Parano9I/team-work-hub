<?php

namespace TeamWorkHub\Modules\Auth\Http\Controllers\Api;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TeamWorkHub\Modules\Auth\Contracts\AuthService;
use TeamWorkHub\Modules\Auth\Http\Controllers\Controller;
use TeamWorkHub\Modules\Auth\Http\Requests\LoginRequest;
use TeamWorkHub\Modules\Auth\Http\Resources\AccountResource;
use TeamWorkHub\Modules\Auth\Mappers\LoginDtoMapper;

class AuthController extends Controller
{

    private ResponseFactory $response;

    private AuthService $authService;

    public function __construct(AuthService $authService, ResponseFactory $response)
    {
        $this->authService = $authService;
        $this->response = $response;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validatedRequestData = $request->validated();

        $dto = (new LoginDtoMapper())->fromArray($validatedRequestData);

        $account = $this->authService->login($dto);

        return $this->response->json([
            'data' => [
                'account' => new AccountResource($account),
                'token' => [
                    'type' => 'Bearer',
                    'payload' => $account->createToken('account')->plainTextToken
                ]
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $account = $request->user();

        $this->authService->logout($account);

        $this->response->noContent();
    }
}
