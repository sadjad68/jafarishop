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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->Integer('user_id')->nullable();
            $table->Integer('address_id')->nullable();
            $table->Integer('state_id')->nullable();
            $table->Integer('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('receiptor_full_name')->nullable();
            $table->Integer('shipping_status_id')->nullable();
            $table->string('order_status')->nullable();
            $table->Integer('basket_id')->nullable();
            $table->Integer('bank_id')->nullable();
            $table->Integer('shipping_method_id')->nullable();
            $table->Integer('discount_id')->nullable();
            $table->string('shipping_price')->nullable();
            $table->string('total_price')->nullable();
            $table->string('discount_price')->nullable();
            $table->string('payment_price')->nullable();
            $table->json('transaction_info')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
