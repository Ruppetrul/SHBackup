<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('adminadmin'),
            ]);

            for ($i = 1; $i <= 20; $i++) {
                $shopName = 'Shop ' . $i;

                Shop::factory()->create([
                    'name' => $shopName,
                    'owner_id' => $user->id,
                    'payment_status' => 'trial',
                    'state' => 'not_created',
                    'last_used_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
