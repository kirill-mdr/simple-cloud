<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Services\FolderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class FolderController extends Controller
{
    public function __construct(
        private readonly FolderService $folderService
    ){}

    #[OA\Get(
        path: '/api/folders/{folderId}',
        summary: 'Получение информации о папке и её файлах',
        tags: ['folders'],
        parameters: [
            new OA\Parameter(parameter: "folderId", name: "folderId", description: "id папки", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok',
            content: new OA\JsonContent(ref: '#/components/schemas/FolderWithAttachmentsResource'))]
    )]
    public function get(int $folderId): JsonResponse
    {
        $folderInfo = $this->folderService->getFolderInfo($folderId);
        return response()->json($folderInfo);
    }

    #[OA\Post(
        path: '/api/folders/{folderId}/upload-file',
        summary: 'Добавление файла',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/StoreFileRequest'))]
        ),
        tags: ['folders'],
        parameters: [
            new OA\Parameter(parameter: "folderId", name: "folderId", description: "id папки", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function uploadFile(int $folderId, StoreFileRequest $request): JsonResponse
    {
        try {
            $this->folderService->uploadFile($folderId, $request->file('file'));
            return response()->json(['status' => 'success']);
        } catch (\Throwable $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    #[OA\Post(
        path: '/api/folders/{folderId}',
        summary: 'Создание дочерней папки',
        requestBody: new OA\RequestBody(
            content: [new OA\MediaType(mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/StoreFolderRequest'))]
        ),
        tags: ['folders'],
        parameters: [
            new OA\Parameter(parameter: "folderId", name: "folderId", description: "id папки", in: "path", example: 1),
        ],
        responses: [new OA\Response(response: 200, description: 'ok')]
    )]
    public function store(int $folderId, StoreFolderRequest $request): JsonResponse
    {
        $this->folderService->storeFolder($folderId, $request->get('folder_name'));
        return response()->json(['status' => 'success']);
    }

    #[OA\Delete(
        path: '/api/folders/{folderId}',
        summary: 'Удаление директории вместе с вложенными файлами',
        tags: ['folders'],
        parameters: [
            new OA\Parameter(parameter: "folderId", name: "folderId", description: "id папки", in: "path",
                schema: new OA\Schema(type: 'number'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'ok'),
        ]
    )]
    public function destroy(int $folderId): JsonResponse
    {
        if ($folderId === Auth::user()->getHomeFolderId()) {
            return response()->json('This is home folder!', 403);
        }

        $this->folderService->deleteFolder($folderId);
        return response()->json(['status' => 'success']);
    }
}
