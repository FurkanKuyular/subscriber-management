<?php

namespace App\Http\Resources;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    /** @var Subscriber */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'card_number' => $this->resource->card_number,
            'card_owner' => $this->resource->card_number,
            'expire_month' => $this->resource->expire_month,
            'expire_year' => $this->resource->expire_year,
            'cvv' => $this->resource->cvv,
            'unique_hash' => $this->resource->unique_hash,
        ];
    }
}
