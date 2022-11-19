<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CouponsController;
use Symfony\Component\Console\Output\BufferedOutput;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::get('cache', function() {
    $output = new BufferedOutput;
//    Artisan::call('config:cache', [], $output);
    Artisan::call('cache:clear', [], $output);
});

Route::middleware(['auth', 'verified'])->group(function() {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::name('coupon.')->group(function() {
            Route::get('/my-coupon', [CouponsController::class, 'myCoupon'])->name('my_coupon');
            Route::get('/generate-discount', [CouponsController::class, 'generateDiscount'])->name('generate_discount');

            Route::get('/test-coupon', [CouponsController::class, 'testCoupon'])->name('test_coupon');
            Route::get('/get-test-coupon', [CouponsController::class, 'getTestCoupon'])->name('get_test_coupon');

        });
    });
});

require __DIR__.'/auth.php';
