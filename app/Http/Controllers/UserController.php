<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ){}

    #[OA\Get(
        path: '/api/users/info',
        summary: 'Получение информации о текущем авторизированном пользователе',
        tags: ['users'],
        responses: [new OA\Response(response: 200, description: 'ok',
            content: new OA\JsonContent(ref: '#/components/schemas/UserResource'))]
    )]
    public function userInfo(Request $request): JsonResponse
    {
        $userInfo = $this->userService->getCurrentUserInfo($request);
        return response()->json($userInfo);
    }
}
