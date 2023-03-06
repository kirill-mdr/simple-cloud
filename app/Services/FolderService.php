<?php

namespace App\Services;

use App\Http\Resources\FolderWithAttachmentsResource;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderService
{
    public function getFolderInfo(int $folderId): FolderWithAttachmentsResource
    {
        $folder = Folder::findOrFail($folderId);
        return FolderWithAttachmentsResource::make($folder);
    }
    public function uploadFile(int $folderId, UploadedFile $file): void
    {
        $folder = Folder::findOrFail($folderId);
        $fileUniqueName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $folder->getPath();

        Storage::disk('cloud')->putFileAs($path, $file, $fileUniqueName);

        File::create([
            'original_name' => $file->getClientOriginalName(),
            'name' => $fileUniqueName,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'user_id' => Auth::id(),
            'folder_id' => $folder->id
        ]);
    }

    public function storeFolder(int $currentFolderId, string $folderName): void {
        $folder = Folder::findOrFail($currentFolderId);

        if (in_array($folderName, $folder->childrens()->pluck('name')->toArray())) {
            throw new \ValueError("Folder with this name already exist!");
        }

        Folder::create([
            'name' => $folderName,
            'parent_id' => $folder->id,
            'user_id' => Auth::id(),
        ]);
    }

    public function deleteFolder(int $folderId): void {
        $folder = Folder::findOrFail($folderId);
        Storage::disk('cloud')->deleteDirectory($folder->getPath());
        $folder->delete();
    }
}
