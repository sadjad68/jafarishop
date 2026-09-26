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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->integer('product_id')->nullable();
            $table->integer('specification_parent_id')->nullable();
            $table->integer('specification_id')->nullable();
            $table->string('price')->nullable();
            $table->string('discounted_price')->nullable();
            $table->string('final_price')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('price_affective')->default(0);
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
        Schema::dropIfExists('product_variants');
    }
};
