<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (! Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->softDeletes('deleted_at', 0);
                $table->timestamp('created_at', 0)->nullable()->useCurrent()->index();
                $table->timestamp('updated_at', 0)->nullable()->useCurrentOnUpdate();
                $table->string('coupon', 20)->default(null)->nullable()->index();
                $table->decimal('discount', 10, 2)->default(0);

                $table->bigInteger('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
