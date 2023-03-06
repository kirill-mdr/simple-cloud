<?php

namespace App\Http\Resources;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'id' => new OA\Property(property: 'id', type: 'number'),
        'name' => new OA\Property(property: 'name', type: 'string'),
        'created_at' => new OA\Property(property: 'created_at', type: 'string'),
        'parent_id' => new OA\Property(property: 'parent_id', type: 'number'),
        'parent_name' => new OA\Property(property: 'parent_name', type: 'string'),
    ]
)]
class FolderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Folder $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'parent_id' => $this->parent_id,
            'parent_name' => $this->parent->name
        ];
    }
}
