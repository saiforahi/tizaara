<?php

namespace App\Http\Resources;

use App\PackagePayment;
use Illuminate\Http\Resources\Json\JsonResource;

class PackagePaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource->toArray();
        return array_merge($resource, [
            'supplier' => [
                'id' => $this->supplier->id,
                'name' => $this->supplier->full_name
            ],
            'package' => [
                'id' => $this->package->id,
                'name' => $this->package->name
            ],
            'status' => PackagePayment::STATUSES[$this->status]
        ]);
    }
}
