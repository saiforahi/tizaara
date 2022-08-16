<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource->toArray();
        return array_merge($resource, [
            'description' => $this->description ?? '',
            'unit_id' => $this->unit,
            'property_options' => json_decode($this->property_options),
            'attribute_options' => json_decode($this->attribute_options),
            'attribute' => json_decode($this->attributes),
            'color' => json_decode($this->colors),
            'color_image' => json_decode($this->color_image),
            'photos' => json_decode($this->photos),
            'featured_img' => $this->featured_img,
            'flash_deal_img' => $this->flash_deal_img,
            'thumbnail_img' => $this->thumbnail_img,
            'meta_img' => $this->meta_img,
            'tierDiscount' => $this->discount_variation_data,
            'tierPrice' => $this->price_variation,
            'priceMenu' => ProductStockResource::collection($this->product_stock),
        ]);
    }
}
