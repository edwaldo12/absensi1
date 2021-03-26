<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "place_of_birth" => $this->faker->city,
            "date_of_birth" => $this->faker->date(),
            "address" => $this->faker->address,
            "gender" => $this->faker->randomElement(['M','F']),
            "phone" => $this->faker->phoneNumber,
        ];
    }
}
