<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'title' => $this->faker->name(),
            'color'=>$this->faker->unique()->colorName(),
            'textColor'=>$this->faker->unique()->colorName(),
            'start' => $this->faker->unique()->dateTime(),
            'end' => $this->faker->unique()->dateTime(),
            'created_at'=>rand(),
        ];
    }
}
