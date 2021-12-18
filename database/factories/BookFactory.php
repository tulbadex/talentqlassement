<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "isbn" => $this->faker->numerify('###-##########'),
            "authors" => $this->faker->name,
            "number_of_pages" => $this->faker->numerify('###'),
            "publisher" => $this->faker->company,
            "country" => $this->faker->country(20),
            "release_date" => now()->format('Y-m-d')
        ];
    }
}
