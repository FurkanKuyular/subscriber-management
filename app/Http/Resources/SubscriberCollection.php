<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriberCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return ['data' => $this->collection];
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        unset($default['links']);

        return $default;
    }
}
