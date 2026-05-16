<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {    
        return [           
            'first_name'=>fake()->firstName(),
            'last_name'=>fake()->lastName(),
            'gender'=>fake()->randomElement(['1', '2', '3']),
            'email'=>fake()->email(),
            'tel'=>fake()->numerify('0#0#######'),
            'address'=>fake()->address(),
            'building'=>fake()->text(10),
            'detail'=>fake()->text(80),
            'category_id'=>fake()->numberBetween(1, 5),
        ];
    }
}
