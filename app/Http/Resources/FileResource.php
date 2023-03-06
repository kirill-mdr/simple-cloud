<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'id' => new OA\Property(property: 'id', type: 'number'),
        'name' => new OA\Property(property: 'name', type: 'string'),
        'name_without_ext' => new OA\Property(property: 'name_without_ext', type: 'string'),
        'created_at' => new OA\Property(property: 'created_at', type: 'string'),
        'size' => new OA\Property(property: 'size', type: 'string'),
        'mime_type' => new OA\Property(property: 'mime_type', type: 'string'),
        'shared_url' => new OA\Property(property: 'shared_url', type: 'string'),
    ]
)]

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var File $this */
        return [
            'id' => $this->id,
            'name' => $this->original_name,
            'name_without_ext' => $this->getNameWithoutExt(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'size' => round($this->size / 1024 / 1024,4) . 'MB',
            'mime_type' => $this->mime_type,
            'shared_url' => $this->sharedFile ? $this->sharedFile->getUrl() : null,
        ];
    }
}
