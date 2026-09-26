<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_stock_reservations', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->integer('order_item_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('product_variant_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->enum('status', ['reserved', 'confirmed', 'released', 'expired'])
                ->default('reserved');
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_stock_reservations');
    }
};
