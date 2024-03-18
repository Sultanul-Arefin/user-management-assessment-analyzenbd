<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            $this->merge(
                Arr::only(parent::toArray($request), [
                    'id',
                    'name',
                    'email',
                ])
            ),
            'address' => $this->addresses->map(function($query){
                return [
                    'address_id' => $query->id,
                    'address' => $query->address
                ];
            })
        ];
    }
}
