<?php

namespace Database\Factories;

use App\Models\Shortcut;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shortcut>
 */
class ShortcutFactory extends Factory
{
    protected $model = Shortcut::class;

    public function definition(): array
    {
        $title = fake()->unique()->company();

        return [
            'title' => $title,
            'url' => 'https://'.fake()->unique()->domainName(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['Productivity', 'Development', 'Reference', 'Finance']),
            'icon_path' => 'https://www.google.com/s2/favicons?domain='.fake()->domainName().'&sz=128',
            'sort_order' => fake()->numberBetween(1, 999),
            'is_active' => true,
        ];
    }
}