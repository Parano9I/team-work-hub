<?php

namespace TeamWorkHub\Modules\Invitation\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use TeamWorkHub\Modules\Auth\Enums\RolesEnum;
use TeamWorkHub\Modules\Invitation\DataTransferObjects\Casts\Payload;

class PayloadCast implements CastsAttributes
{

    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return new Payload(
            $attributes['first_name'],
            $attributes['last_name'],
            RolesEnum::from($attributes['role'])
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (!$value instanceof Payload) {
            throw new \InvalidArgumentException('The given value is not an Payload instance.');
        }

        return [
            'first_name' => $value->firstName,
            'last_name'  => $value->lastName,
            'role'       => $value->role->value
        ];
    }
}
