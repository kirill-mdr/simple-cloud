<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        'id' => new OA\Property(property: 'id', type: 'number'),
        'name' => new OA\Property(property: 'name', type: 'string'),
        'email' => new OA\Property(property: 'email', type: 'string'),
        'home_folder_id' => new OA\Property(property: 'home_folder_id', type: 'number'),
    ]
)]
class UserResource extends JsonResource
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
            'email' => $this->email,
            'home_folder_id' => $this->getHomeFolderId(),
        ];
    }
}
