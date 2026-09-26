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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->Integer('user_id')->nullable();
            $table->Integer('state_id')->nullable();
            $table->Integer('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('receiptor_full_name')->nullable();
            $table->string('receiptor_mobile')->nullable();
            $table->string('postal_code')->nullable();
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
        Schema::dropIfExists('addresses');
    }
};
