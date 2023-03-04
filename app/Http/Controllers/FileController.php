<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function __construct(
        private readonly FileService $fileService
    ){}

    #[OA\Post(
        path: '/api/files/upload/{folderId}',
        summary: 'Добавление файла',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/StoreFileRequest'))]
        ),
        tags: ['files'],
        parameters: [
            new OA\Parameter(parameter: "folderId", name: "folderId", description: "id папки", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function store(int $folderId, StoreFileRequest $request): JsonResponse
    {
        try {
            $this->fileService->storeFile($folderId, $request->file('file'));
            return response()->json(['status' => 'success']);
        } catch (\Throwable $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    #[OA\Post(
        path: '/api/files/{fileId}',
        summary: 'Скачивание файла пользователя',
        tags: ['files'],
        parameters: [
            new OA\Parameter(parameter: "fileId", name: "fileId", description: "id файла", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function get(int $fileId): StreamedResponse|JsonResponse
    {
        return $this->fileService->downloadFile($fileId);
    }


    #[OA\Put(
        path: '/api/files/{fileId}',
        summary: 'Изменение информации о файле',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/UpdateFileRequest'))]
        ),
        tags: ['files'],
        parameters: [
            new OA\Parameter(parameter: "fileId", name: "fileId", description: "id файла", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function update(int $fileId, UpdateFileRequest $request): JsonResponse
    {
        $this->fileService->updateFileName($fileId, $request->get('name'));
        return response()->json(['status' => 'success']);
    }

    #[OA\Delete(
        path: '/api/files/{fileId}',
        summary: 'Удаление файла',
        tags: ['files'],
        parameters: [
            new OA\Parameter(parameter: "candidateId", name: "candidateId", description: "id кандидата", in: "path",
                schema: new OA\Schema(type: 'number'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'ok'),
        ]
    )]
    public function destroy(int $fileId): JsonResponse
    {
        $this->fileService->destroyFile($fileId);
        return response()->json(['status' => 'success']);
    }
}
