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
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->string('sender_name')->nullable()->after('description');
            $table->string('sender_company')->nullable()->after('description');
            $table->integer('sender_city_id')->nullable()->after('description');
            $table->string('sender_phone')->nullable()->after('description');
            $table->string('sender_mobile')->nullable()->after('description');
            $table->string('sender_email')->nullable()->after('description');
            $table->text('sender_address')->nullable()->after('description');
            $table->string('sender_postal_code')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            //
        });
    }
};
