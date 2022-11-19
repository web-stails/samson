<?php

namespace Tests\Feature\Coupon;

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Route;
use Tests\TestCase;
use Tests\Feature\TraitRoute;
use Illuminate\Support\Str;

class MyCouponTest extends TestCase
{
    use TraitRoute;

    private $user;

    public function setUp(): void {
        parent::setUp();

        // создаем пользователя
        $this->user = User::factory()->create();

        // авторизуемся под ним
        $this->be($this->user);
    }

    /**
     * Тест открытия страницы получения купона.
     *
     * @return void
     */
    public function test_open_page_get_my_coupon()
    {
        $url = $this->gerRoute('coupon.my_coupon');

        $response = $this->get($url);

        $response->assertStatus(200);
    }

    /**
     * Тест получения купона с проверкой на идентичность при вторичном получении
     * @return void
     */
    public function test_get_generate_coupon()
    {

        $url = $this->gerRoute('coupon.generate_discount');
        $response = $this->get($url);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'coupon',
                    'discount',
                    'start_discount',
                    'stop_discount',
                ]
            ])
        ;

        $json = $response->json();

        $this->assertTrue($json['status'] === 'success');

        $coupon = $json['data']['coupon'];
        $discount = $json['data']['discount'];
        $start_discount = Carbon::parse($json['data']['start_discount']) ?? null;
        $stop_discount = Carbon::parse($json['data']['stop_discount']) ?? null;

        $this->assertTrue(is_string($coupon) && strlen($coupon) > 0);
        $this->assertTrue((is_numeric($discount) or is_float($discount)) && $discount >= 0 && $discount <= 50);

        $this->assertTrue($start_discount instanceof Carbon);
        $this->assertTrue($stop_discount instanceof Carbon);

        $this->assertTrue($start_discount <= now());
        $this->assertTrue($stop_discount >= now());

        // response 2
        $response2 = $this->get($url);

        $response2->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'coupon',
                    'discount',
                    'start_discount',
                    'stop_discount',
                ]
            ])
        ;

        $json2 = $response2->json();

        $this->assertTrue($json2['status'] === 'success');
        $this->assertTrue($json2['data']['coupon'] == $coupon);
        $this->assertTrue($json2['data']['discount'] == $discount);
        $this->assertTrue($json2['data']['start_discount'] == $json['data']['start_discount']);
        $this->assertTrue($json2['data']['stop_discount'] == $json['data']['stop_discount']);
    }

    /**
     *  Тест открытия страницы теста купона
     * @return void
     */
    public function test_open_page_test_coupon()
    {
        $url = $this->gerRoute('coupon.test_coupon');

        $response = $this->get($url);

        $response->assertStatus(200);
    }

    /**
     * Тест теста купона на странице теста купона при запросе купона тем-же пользователем
     * @return void
     */
    public function test_get_test_coupon_this_user()
    {
        $url = $this->gerRoute('coupon.generate_discount');
        $response = $this->get($url);

        $json = $response->json();

        $coupon = $json['data']['coupon'];
        $discount = $json['data']['discount'];

        $url = $this->gerRoute('coupon.get_test_coupon');
        $response2 = $this->get($url . '?coupon=' . $coupon);

        $response2->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'coupon',
                    'discount',
                    'start_discount',
                    'stop_discount',
                ]
            ])
        ;

        $json2 = $response2->json();

        $this->assertTrue($json2['status'] === 'success');
        $this->assertTrue($json2['data']['coupon'] == $coupon);
        $this->assertTrue($json2['data']['discount'] == $discount);
        $this->assertTrue($json2['data']['start_discount'] == $json['data']['start_discount']);
        $this->assertTrue($json2['data']['stop_discount'] == $json['data']['stop_discount']);
    }

    /**
     * Тест теста купона при запросе купона другим пользователем
     * @return void
     */
    public function test_get_test_coupon_other_user()
    {
        $url = $this->gerRoute('coupon.generate_discount');
        $response = $this->get($url);

        $json = $response->json();

        $coupon = $json['data']['coupon'];

        // создаем нового пользователя
        $other_user = User::factory()->create();

        // авторизуемся под ним
        $this->be($other_user);

        // делаем запрос по купону другого пользователя
        $url_get_test_coupon = $this->gerRoute('coupon.get_test_coupon');
        $response2 = $this->get($url_get_test_coupon . '?coupon=' . $coupon);

        $response2->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ])
        ;

        $json2 = $response2->json();

        $this->assertTrue($json2['status'] === 'no coupon');
    }

    /**
     * Тест теста купона при запросе купона фальшивым купоном
     * @return void
     */
    public function test_get_test_coupon_fake_coupon()
    {

        $coupon = Str::random(32, 'alpha');

        $url = $this->gerRoute('coupon.get_test_coupon');
        $response = $this->get($url . '?coupon=' . $coupon);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ])
        ;

        $json = $response->json();

        $this->assertTrue($json['status'] === 'no coupon');
    }

}
