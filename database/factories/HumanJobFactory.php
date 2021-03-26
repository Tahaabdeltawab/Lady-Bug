<?php

namespace Database\Factories;

use App\Models\HumanJob;
use Illuminate\Database\Eloquent\Factories\Factory;

class HumanJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HumanJob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ar' => [
                'name'       => $this->faker->jobTitle,
            ],
            'en' => [
                'name'       => $this->faker->jobTitle,
            ],
         ];
    }
}
