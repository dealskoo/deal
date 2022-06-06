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
        return [
            'title' => $this->faker->title,
            'slug' => $this->faker->slug,
            'ship_fee' => $this->faker->numberBetween(0, 20),
            'product_id' => Product::factory(),
            'price' => function ($deal) {
                return $this->faker->numberBetween(0, Product::find($deal['product_id'])->price);
            },
            'seller_id' => function ($deal) {
                return Product::find($deal['product_id'])->seller_id;
            },
            'category_id' => function ($deal) {
                return Product::find($deal['product_id'])->category_id;
            },
            'country_id' => function ($deal) {
                return Product::find($deal['product_id'])->country_id;
            },
            'brand_id' => function ($deal) {
                return Product::find($deal['product_id'])->brand_id;
            },
            'platform_id' => function ($deal) {
                return Product::find($deal['product_id'])->platform_id;
            },
            'recommend' => false,
            'big_discount' => false,
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

    public function bigDiscount()
    {
        return $this->state(function (array $attributes) {
            return [
                'big_discount' => true,
            ];
        });
    }

    public function avaiabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_at' => $this->faker->dateTimeBetween('-1 days'),
                'end_at' => $this->faker->dateTimeBetween('now', '+7 days'),
                'approved_at' => $this->faker->dateTime,
            ];
        });
    }

    public function limitedTime()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_at' => $this->faker->dateTimeBetween('-1 days'),
                'end_at' => $this->faker->dateTimeBetween('now', '+1 days'),
                'approved_at' => $this->faker->dateTime,
            ];
        });
    }
}
