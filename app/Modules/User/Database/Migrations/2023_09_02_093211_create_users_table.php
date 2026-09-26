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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->string('full_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('cookie_id')->nullable();
            $table->string('comfirm_code')->nullable();
            $table->Integer('mobile_confirm')->nullable();
            $table->Integer('admin')->nullable();
            $table->text('password')->nullable();
            $table->text('resume')->nullable();
            $table->string('expertise')->nullable();
            $table->string('social')->nullable();
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('users');
    }
};
