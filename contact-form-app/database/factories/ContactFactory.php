<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => fake()->randomElement(['1', '2', '3']),
            'email' => fake()->email(),
            'tel' => fake()->numerify('0#0#######'),
            'address' => fake()->address(),
            'building' => fake()->text(10),
            'detail' => fake()->text(80),
            'category_id' => fake()->numberBetween(1, 5),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
