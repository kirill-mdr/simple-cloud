<?php

namespace App\Services;


use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService
{
    public function downloadFile(int $fileId): StreamedResponse
    {
        $file = File::findOrFail($fileId);
        return Storage::disk('cloud')->download($file->getPath(), $file->original_name);
    }

    public function destroyFile(int $fileId): void
    {
        $file = File::findOrFail($fileId);
        Storage::disk('cloud')->delete($file->getPath());
        $file->delete();
    }

    public function updateFileName(int $fileId, string $name): void
    {
        $file = File::findOrFail($fileId);
        $file->original_name = $name . '.' . $file->extension();
        $file->save();
    }
}
