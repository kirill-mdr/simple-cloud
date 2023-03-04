<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthenticatedSessionController extends Controller
{
    #[OA\Post(
        path: '/login',
        summary: 'Аутентификация пользователя (логин)',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'email', type: 'string'),
                        new OA\Property(property: 'password', type: 'string'),
                    ],
                ),
            )]
        ),
        tags: ['auth'],
        responses: [new OA\Response(response: 204, description: 'no content')]
    )]
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }


    #[OA\Post(
        path: '/logout',
        summary: 'Выход из системы',
        tags: ['auth'],
        responses: [new OA\Response(response: 204, description: 'no content')]
    )]
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    #[OA\Get(
        path: '/sanctum/csrf-cookie',
        summary: 'Получение csrf',
        tags: ['auth'],
        responses: [new OA\Response(response: 204, description: 'no content')]
    )]
    private function csrfCookie() {}
}
