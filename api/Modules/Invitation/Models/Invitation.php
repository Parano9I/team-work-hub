<?php

namespace Modules\Invitation\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Invitation\Cast\PayloadCast;
use Modules\Invitation\Database\Factories\InvitationFactory;
use Modules\Invitation\DTO\Casts\Payload;

/**
 * @property int $id
 * @property string $email
 * @property string $token
 * @property Payload $payload
 * @property \Illuminate\Support\Carbon|null $expiration_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Invitation extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'expiration_at',
        'payload'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expiration_at' => 'datetime',
        'payload'       => PayloadCast::class
    ];

    protected static function newFactory(): Factory
    {
        return InvitationFactory::new();
    }
}
