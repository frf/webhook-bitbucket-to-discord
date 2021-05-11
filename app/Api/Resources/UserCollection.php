<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
