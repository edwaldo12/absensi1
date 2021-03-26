<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

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
            "created_at" => "2021-02-01 00:00:00"
        ];
    }
}
