<?php

namespace Database\Factories;

use App\Classes\RandomizeStringClass;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'coupon' => RandomizeStringClass::generateCoupon(),
            'discount' => number_format(rand(10, 500) / 10, 2, '.', ''),
        ];
    }
}
