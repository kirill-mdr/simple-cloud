<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SharedFile extends Model
{
    use HasFactory;

    protected $fillable = ['file_id', 'public_code', 'user_id'];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrl(): string
    {
        return url("/shared-files/{$this->public_code}");
    }

    public static function generateCode(): string
    {
        do {
            $code = Str::random();
        } while (self::where('public_code', $code)->exists());

        return $code;
    }
}
