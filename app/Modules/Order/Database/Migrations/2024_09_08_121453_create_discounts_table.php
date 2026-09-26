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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->string('title')->nullable();
            $table->string('amount')->nullable();
            $table->string('type')->nullable();
            $table->Integer('count')->default(1);
            $table->string('basket_minimum_price')->nullable();
            $table->tinyInteger('first_purchase')->default(0);
            $table->tinyInteger('with_discount')->default(0);
            $table->tinyInteger('user_id')->nullable();
            $table->Integer('max_usage_per_user')->default(1);

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
        Schema::dropIfExists('discounts');
    }
};
