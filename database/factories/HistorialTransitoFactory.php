<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\HistorialTransito;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistorialTransito>
 */
class HistorialTransitoFactory extends Factory
{
    protected $model = HistorialTransito::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'placas' => strtoupper($this->faker->bothify('???###')),
            'recibe' => $this->faker->name,
            'fecha_de_entrega' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'quien_entrega' => $this->faker->name,
            'tramite' => $this->faker->randomElement(['Registro Inicial', 'Cambio de Propietario', 'Duplicado de Placas']),
            'observaciones' => $this->faker->optional()->sentence,
            'fecha_de_archivo' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'archivo' => $this->faker->name,
            'fecha_de_importacion' => Carbon::now(),
        ];
    }
}
