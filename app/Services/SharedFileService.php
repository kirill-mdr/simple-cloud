<?php

namespace App\Services;

use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\SharedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SharedFileService
{
    public function shareFile(int $fileId): FileResource
    {
        $file = File::findOrFail($fileId);
        $sharedFile = SharedFile::where('file_id', $file->id)->first();
        if (!$sharedFile) {
            SharedFile::create([
                'file_id' => $file->id,
                'public_code' => SharedFile::generateCode(),
                'user_id' => Auth::id()
            ]);
        }
        return FileResource::make($file);
    }

    public function destroySharedFile(int $fileId): void
    {
        $sharedFile = SharedFile::query()
            ->where('file_id', $fileId)
            ->firstOrFail();

        $sharedFile->delete();
    }

    public function downloadFile(string $code): StreamedResponse
    {
        $sharedFile = SharedFile::query()
            ->where('public_code', $code)
            ->firstOrFail();

        return Storage::disk('cloud')->download($sharedFile->file->getPath());
    }
}
