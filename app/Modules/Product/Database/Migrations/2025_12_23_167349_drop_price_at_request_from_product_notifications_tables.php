<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('product_notifications', function (Blueprint $table) {

            if (Schema::hasColumn('product_notifications', 'price_at_request')) {
                $table->dropColumn('price_at_request');
            }

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('product_notifications', function (Blueprint $table) {

            if (!Schema::hasColumn('product_notifications', 'price_at_request')) {
                $table->decimal('price_at_request', 12, 2)->nullable();
            }

        });
    }
};
