<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Авто генерация данных
     *
     * @return void
     */
    public function run()
    {
        // создаем 10 пользователей, каждого с одним купоном
        // все пользователи с паролем "password"
         \App\Models\User::factory(10)
             ->has(Coupon::factory())
             ->create()
         ;
    }
}
