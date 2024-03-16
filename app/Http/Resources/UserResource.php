<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class UserResource extends JsonResource
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
            'created_by_user' => $this->created_by_user->only(['id', 'name', 'email']),
            'deleted_at' => $this->deleted_at ? date('Y-m-d H:i:s a', strtotime($this->deleted_at)) : null
        ];
    }
}
