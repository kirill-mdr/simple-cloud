<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['original_name', 'name', 'size', 'folder_id', 'mime_type', 'user_id'];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sharedFile(): BelongsTo
    {
        return $this->belongsTo(SharedFile::class);
    }

    public function getPath(): string
    {
        return $this->folder->getPath() . '/' . $this->name;
    }

    public function nameWithoutExt(): string
    {
        return pathinfo($this->original_name, PATHINFO_FILENAME);
    }

    public function extension(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }
}
