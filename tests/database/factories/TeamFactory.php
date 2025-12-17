<?php

namespace LiraUi\Team\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\LiraUi\Team\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = \LiraUi\Team\Models\Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \LiraUi\Team\Tests\Database\Factories\UserFactory::new(),
            'name' => $this->faker->company(),
            'personal_team' => false,
        ];
    }
}
