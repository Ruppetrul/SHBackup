<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition()
    {
        $user = User::factory()->create();

        return [
            'name' => $this->faker->name,
            'owner_id' => $user->id,
            'payment_status' => 'trial',
            'state' => 'not_created',
            'last_used_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
