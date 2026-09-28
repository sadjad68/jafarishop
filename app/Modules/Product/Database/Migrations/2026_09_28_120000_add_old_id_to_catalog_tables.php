<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('old_id')->nullable()->index()->after('id');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('old_id')->nullable()->index()->after('id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['old_id']);
            $table->dropColumn('old_id');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex(['old_id']);
            $table->dropColumn('old_id');
        });
    }
};
