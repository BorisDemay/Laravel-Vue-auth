<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checkout>
 */
class CheckoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_id' => Str::uuid(),
            'products' => json_encode([
                [
                    'id' => $this->faker->randomDigitNotNull(),
                    'name' => $this->faker->word(),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'quantity' => $this->faker->numberBetween(1, 5),
                ],
                [
                    'id' => $this->faker->randomDigitNotNull(),
                    'name' => $this->faker->word(),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'quantity' => $this->faker->numberBetween(1, 5),
                ],
            ]),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'client_email' => $this->faker->safeEmail(),
            'order_reference' => Str::uuid(),
        ];
    }
}
