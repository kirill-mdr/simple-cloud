<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSharedFileRequest;
use App\Models\SharedFile;
use App\Services\SharedFileService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use OpenApi\Attributes as OA;

class SharedFileController extends Controller
{
    public function __construct(
        private readonly SharedFileService $sharedFileService
    ){}

    #[OA\Get(
        path: '/shared-files/{code}',
        summary: 'Загрузка публичного файла',
        tags: ['shared-files'],
        parameters: [
            new OA\Parameter(parameter: "code", name: "code", description: "Публичный код файла", in: "path", example: 'string4332'),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function downloadFile(string $code): StreamedResponse
    {
        return $this->sharedFileService->downloadFile($code);
    }

    #[OA\Post(
        path: '/api/shared-files/{fileId}',
        summary: 'Открытие доступа к файлу',
        tags: ['shared-files'],
        parameters: [
            new OA\Parameter(parameter: "fileId", name: "fileId", description: "id файла", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function store(int $fileId): JsonResponse
    {
        $this->sharedFileService->shareFile($fileId);
        return response()->json(['status' => 'success']);
    }

    #[OA\Delete(
        path: '/api/shared-files/{fileId}',
        summary: 'Удаление публичной ссылки у файла',
        tags: ['shared-files'],
        parameters: [
            new OA\Parameter(parameter: "fileId", name: "fileId", description: "id файла", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function destroy(int $fileId): JsonResponse
    {
        $this->sharedFileService->destroySharedFile($fileId);
        return response()->json(['status' => 'success']);
    }
}
