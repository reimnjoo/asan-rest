<?php

namespace Database\Factories;

use App\Models\Buyers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buyers>
 */
class BuyersFactory extends Factory {

    protected $model = Buyers::class;

    public function definition(): array {
        return [
            'uuid' => $this->faker->uuid,
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'middle_initial' => $this->faker->optional()->randomLetter,
            'date_of_birth' => $this->faker->optional()->date(),
            'company' => $this->faker->company,
            'location' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Hashed fake password
            'subscription_plan' => $this->faker->randomElement(['Basic', 'Premium', 'Pro']),
            'profile_image' => $this->faker->optional()->imageUrl(),
            'id_type' => $this->faker->optional()->randomElement(['Passport', 'Driver License', 'ID Card']),
            'id_image' => $this->faker->optional()->imageUrl(),
            'id_submitted_date' => $this->faker->date,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now')
        ];
    }
}
