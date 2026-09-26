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
        // 1. جدول product_main_specifications
        Schema::table('product_main_specifications', function (Blueprint $table) {
            // اضافه کردن ایندکس بر روی product_id
            $table->index('product_id');

            // اضافه کردن ایندکس بر روی main_specification_id
            $table->index('main_specification_id');
        });

        // 2. جدول image_product_variant
        Schema::table('image_product_variant', function (Blueprint $table) {
            // اضافه کردن ایندکس بر روی product_variant_id
            $table->index('product_variant_id');

            // اضافه کردن ایندکس بر روی image_id
            $table->index('image_id');
        });

        // 3. جدول product_variant_specification
        Schema::table('product_variant_specification', function (Blueprint $table) {
            // اضافه کردن ایندکس بر روی product_variant_id
            $table->index('product_variant_id');

            // اضافه کردن ایندکس بر روی specification_value_id
            $table->index('specification_value_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. حذف ایندکس‌ها از product_main_specifications
        Schema::table('product_main_specifications', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['main_specification_id']);
        });

        // 2. حذف ایندکس‌ها از image_product_variant
        Schema::table('image_product_variant', function (Blueprint $table) {
            $table->dropIndex(['product_variant_id']);
            $table->dropIndex(['image_id']);
        });

        // 3. حذف ایندکس‌ها از product_variant_specification
        Schema::table('product_variant_specification', function (Blueprint $table) {
            $table->dropIndex(['product_variant_id']);
            $table->dropIndex(['specification_value_id']);
        });
    }
};
