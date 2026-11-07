<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(8);

        return [
            'category_id' => Category::factory(),
            'admin_id' => Admin::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'image' => 'https://placehold.co/900x540',
            'details' => fake()->paragraphs(3, true),
            'tags' => 'news,featured',
            'breaking_news' => false,
            'top_slider' => false,
            'first_section_three' => false,
            'first_section_nine' => false,
            'post_date' => now()->format('d-m-Y'),
            'post_month' => now()->format('F'),
            'status' => true,
            'view_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
