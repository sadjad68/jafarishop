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
        Schema::create('comments', function (Blueprint $table) {
            $table->id()->BigIncrement();
            $table->integer('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->Integer('user_id')->nullable();
            $table->Integer('reply_id')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('comments');
    }
};
