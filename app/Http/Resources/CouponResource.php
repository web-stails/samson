<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{

    /**
     * @param $request
     * @param string $status
     */
    public function __construct($resource, private string $status = 'error') {
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        JsonResource::withoutWrapping();

        return [
            'status' => $this->status,
            'data' => is_null($this->resource) ? null : [
                'coupon' => $this->coupon ?? '',
                'discount' => $this->discount ?? 0,
                'start_discount' => $this->created_at?->format('H:i') ?? '',
                'stop_discount' => $this->stop_discount?->format('H:i') ?? '',
            ]
        ];
    }
}
