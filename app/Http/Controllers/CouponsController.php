<?php

namespace App\Http\Controllers;

use App\Classes\RandomizeStringClass;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//use Illuminate\Support\Str;

class CouponsController extends Controller
{

    /**
     * Страница получения скидки
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function myCoupon() {
        return view('coupons.my_coupon');
    }

    /**
     * Ajax метод генерации купона если он устарел или его нет
     *
     * @param Request $request
     * @return CouponResource
     */
    public function generateDiscount(Request $request)
    {
        $user = $request->user();

        // ленивая загрузка модели купона (скидки)
        $user->load('couponOneHour');

        if(empty($user->couponOneHour)) {
            // если купона (скидки) нет или он устарел, то генерируем новый
            $coupon = new Coupon();
            $coupon->discount = number_format(rand(10, 500) / 10, 2, '.', '');
            $coupon->coupon = RandomizeStringClass::generateCoupon();
            $coupon->user_id = $user->id;
            $coupon->save();

            $user->couponOneHour = $coupon;
        }

        // отдаем пользователю Json данные купона (скидки) со статусом success
        return new CouponResource($user->couponOneHour, 'success');
    }

    /**
     * Страница проверки купона
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function testCoupon() {
        return view('coupons.test_coupon');
    }

    /**
     * Ajax метод проверки купона (скидки)
     *
     * @param Request $request
     * @return CouponResource
     */
    public function getTestCoupon(Request $request) {
        // Получаем пользователя с ленивой загрузкой наличия указанного купона (скидки)
        $user = $request->user()->load([
            // Ленивая загрузка конкретного купона принадлежащему текущего пользователю не старше 3 часов
            'couponThreeHours' => fn($q) => $q->where('coupon', $request->coupon ?? '')
        ]);

        // Проверяем есть ли купон (скидка) у пользователя удовлетворяющий условию
        if(! empty($user->couponThreeHours)) {
            // Передаем Json данные купона (скидки) со статусом success.
            return new CouponResource($user->couponThreeHours, 'success');
        } else {
            // Передает статус no coupon - купона (скидки) с указанными условиями нет.
            return new CouponResource(null, 'no coupon');
        }
    }
}
