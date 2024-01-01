<?php

namespace Database\Seeders;

use App\Http\Enum\CountryIsoCode;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '5550358124',
            'country_iso_code' => CountryIsoCode::Turkey->value,
            'email' => 'superadmin@admin.com',
            'password' => 'Test123!',
        ]);
    }
}
