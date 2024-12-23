<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Checkout;
use App\Models\Order;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
        ]);

        $users = User::factory(10)->create();
        $products = Product::all();

        foreach ($users as $user) {
            $checkout = Checkout::factory()->create(['client_email' => $user->email]);
            $order = Order::create([
                'user_id' => $user->id,
                'client_email' => $user->email,
                'uuid' => Str::uuid(),
                'checkout_id' => $checkout->id,
                'total_price' => $products->take(3)->sum('price'),
                'status' => 'completed',
            ]);

            foreach ($products->take(3) as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => rand(1, 5),
                    'price' => $product->price,
                ]);
            }
        }
    }
}
