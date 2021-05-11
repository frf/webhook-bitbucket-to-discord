<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            $this->source => $this->source_reference,
        ];
    }
}
