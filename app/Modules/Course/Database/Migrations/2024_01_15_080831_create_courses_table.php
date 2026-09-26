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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('time')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('discounted_price')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('h1')->nullable();
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
        Schema::dropIfExists('courses');
    }
};
