<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'utr' => $this->utr,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'profile_pic_url' => $this->profile_pic_url,
        ];
        // Assume there is no need to return related incomes
    }
}
