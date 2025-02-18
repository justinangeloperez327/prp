<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_no' => $this->faker->numerify('CC#####'),
            'title' => $this->faker->randomElement(['Mr', 'Mrs', 'Ms', 'Miss', 'Dr']),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'direct_phone' => $this->faker->phoneNumber,
            'mobile_phone' => $this->faker->optional()->phoneNumber,
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
            'customer_id' => Customer::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
