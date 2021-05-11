<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SourceCollection extends ResourceCollection
{
    public $collects = SourceResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
