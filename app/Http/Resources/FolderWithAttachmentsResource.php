<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'id' => new OA\Property(property: 'id', type: 'number'),
        'name' => new OA\Property(property: 'name', type: 'string'),
        'created_at' => new OA\Property(property: 'created_at', type: 'string'),
        'parent_id' => new OA\Property(property: 'parent_id', type: 'number'),
        'folders' => new OA\Property(property: 'folders', type: 'array', items: new OA\Items(ref: '#/components/schemas/FolderResource')),
        'files' => new OA\Property(property: 'files', type: 'array', items: new OA\Items(ref: '#/components/schemas/FileResource')),
    ]
)]
class FolderWithAttachmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'parent_id' => $this->parent_id,
            'folders' => FolderResource::collection($this->childrens),
            'files' => FileResource::collection($this->files),
        ];
    }
}
