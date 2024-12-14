<?php

namespace Database\Factories;

use App\Models\Conta;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContaFactory extends Factory
{
    protected $model = Conta::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'numero' => $this->faker->unique()->numerify('######'),
            'saldo' => $this->faker->numberBetween(0, 10000),
            'limite_credito' => $this->faker->numberBetween(0, 5000),
        ];
    }
}
