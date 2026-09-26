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
        Schema::table('baskets', function (Blueprint $table) {
            $table->string('torob_clid', 64)->nullable()->after('user_cookie');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('torob_clid', 64)->nullable()->after('user_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->dropColumn('torob_clid');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('torob_clid');
        });
    }
};
