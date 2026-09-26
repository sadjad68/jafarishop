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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cookie_id');
            $table->dropColumn('comfirm_code');
            $table->dropColumn('mobile_confirm');
            $table->dropColumn('resume');
            $table->dropColumn('expertise');
            $table->dropColumn('social');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cookie_id')->nullable();
            $table->string('comfirm_code')->nullable();
            $table->Integer('mobile_confirm')->nullable();
            $table->text('resume')->nullable();
            $table->string('expertise')->nullable();
            $table->string('social')->nullable();
        });
    }
};
