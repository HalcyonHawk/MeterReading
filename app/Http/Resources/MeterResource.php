<?php

namespace App\Http\Resources;

use App\Http\Resources\MeterReadingResource;

use Illuminate\Http\Resources\Json\JsonResource;

class MeterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['readings'] = MeterReadingResource::collection($this->meterReadings->where('deleted_at', null));

        return $data;
    }
}
