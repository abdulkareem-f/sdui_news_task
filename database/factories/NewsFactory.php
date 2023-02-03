<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = Carbon::now()->subDays(rand(0, 30));
        return [
            'title'         =>  $this->faker->words(rand(4, 10), true),
            'content'       =>  $this->faker->paragraphs(rand(2, 6), true),
            'created_at'    =>  $date,
            'updated_at'    =>  $date
        ];
    }
}
