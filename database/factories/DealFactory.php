<?php

namespace Database\Factories\Dealskoo\Deal\Models;

use Dealskoo\Deal\Models\Deal;
use Dealskoo\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Deal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::factory()->approved()->create();
        return [
            'title' => $this->faker->title,
            'slug' => $this->faker->slug,
            'price' => $this->faker->numberBetween(0, 1000),
            'ship_fee' => $this->faker->numberBetween(0, 20),
            'seller_id' => $product->seller_id,
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'country_id' => $product->country_id,
            'brand_id' => $product->brand_id,
            'platform_id' => $product->platform_id,
            'recommend' => false,
            'start_at' => $this->faker->dateTime,
            'end_at' => $this->faker->dateTime,
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'approved_at' => $this->faker->dateTime,
            ];
        });
    }

    public function recommend()
    {
        return $this->state(function (array $attributes) {
            return [
                'recommend' => true,
            ];
        });
    }
}
