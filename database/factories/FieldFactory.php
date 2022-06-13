<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subscriber_id' => Subscriber::factory(),
            'title' => $this->faker->word,
            'type' => $this->faker->randomElement(Field::VALID_TYPES),
        ];
    }
}
