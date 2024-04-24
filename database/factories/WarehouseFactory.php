<?php

namespace Database\Factories;


use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Owners; // Add this line at the top of the file

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        // Get a random owner's UUID from the database
        $ownerUuid = Owners::inRandomOrder()->first()->uuid;

        return [
            'warehouse_id' => $this->faker->uuid,
            'warehouse_owner' => $ownerUuid,
            'location' => $this->faker->address,
        ];
    }
}

