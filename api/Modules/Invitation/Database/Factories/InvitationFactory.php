<?php

namespace Modules\Invitation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Enums\RolesEnum;
use Modules\Invitation\DTO\Casts\Payload;
use Modules\Invitation\Models\Invitation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InvitationFactory extends Factory
{

    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email'         => fake()->unique()->safeEmail(),
            'expiration_at' => fake()->dateTime("+24 hours"),
            'token'         => fake()->regexify('[A-Za-z0-9]{16}'),
            'payload'       => new Payload(
                fake()->firstName(),
                fake()->lastName(),
                fake()->randomElement([RolesEnum::ADMIN, RolesEnum::DEVELOPER])
            ),
        ];
    }
}
