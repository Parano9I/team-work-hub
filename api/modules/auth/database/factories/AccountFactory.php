<?php

namespace TeamWorkHub\Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use TeamWorkHub\Modules\Auth\Models\Account;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccountFactory extends Factory
{

    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'nickname'          => fake()->unique()->userName(),
            'email'             => fake()->unique()->safeEmail(),
            'date_of_birth'     => fake()->date(),
            'password'          => Hash::make('password'),
        ];
    }
}
