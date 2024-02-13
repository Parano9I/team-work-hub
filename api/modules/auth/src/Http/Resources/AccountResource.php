<?php

namespace TeamWorkHub\Modules\Auth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'nickname'      => $this->nickname,
            'email'         => $this->email,
            'date_of_birth' => $this->date_of_birth->toDateString(),
            'avatar'        => is_null($this->avatar) ? null : [
                'file_name' => $this->avatar,
                'url' => Storage::disk('avatars')->url($this->avatar)
            ]
        ];
    }
}
