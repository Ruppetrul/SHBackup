<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Faker\Factory as Faker;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('adminadmin274p1'),
        ]);

        $now = now();
        $shop = Shop::create([
            'name'           => 'Test shop for admin',
            'db_name'        => 'unknown_' . $now->format('YmdHis'),
            'owner_id'       => $user->id,
            'payment_status' => 'trial',
            'state'          => 'not_created',
            'last_used_at'   => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            Product::createProduct($shop->id, [
                'title' => $faker->name() . $i,
                'price' => $faker->randomNumber(),
            ]);
        }
    }
}
