<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * EventCollectionResource
 *
 * Serializes paginated Event collections for API responses
 */
class EventCollectionResource extends ResourceCollection
{
    /**
     * The resource that this resource collects
     *
     * @var string
     */
    public $collects = EventResource::class;

    /**
     * Transform the resource collection into an array
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
        ];
    }
}
