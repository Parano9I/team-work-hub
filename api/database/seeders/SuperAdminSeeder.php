<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Enums\RolesEnum;
use Modules\Auth\Models\Account;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::findByName(RolesEnum::SUPER_ADMIN->value);

        Account::factory()->create([
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'email'      => 'superadmin@gmail.com',
            'nickname'   => 'superadmin123',
            'password'   => Hash::make('superadmin123')
        ])->assignRole($role);
    }
}
