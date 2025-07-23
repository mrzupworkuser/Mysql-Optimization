<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = Address::on($this->db_connection)->forUser($this->id)->latest()->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'address' => AddressResource::collection($address)
        ];
    }
}
