<?php

namespace App\Classes;

use App\Models\Coupon;

class RandomizeStringClass
{
    /** Генерация строки из перечисленных символов
     * @param int $count
     * @param string $chars // по умолчанию в строке генерации нет символов которые можно перепутать: допустим "O" с "0" или "L", "I" с "1"
     * @return null|string
     */
    public static function rand(int $count, string $chars = '1234567890WERTYUPASDFGHJKZXCVBNM') :?string
    {
        if(empty($count)) {
            return null;
        }

        $res = '';

        while($count --) {
            $res .= $chars[rand(0, strlen($chars) -1)];
        }

        return $res;
    }

    /**
     * Генерация уникального купона
     * @return string
     */
    public static function generateCoupon() :string {
        do {
            $coupon = self::rand(6);
        } while(Coupon::where('coupon', $coupon)->first());

        return $coupon;
    }
}
