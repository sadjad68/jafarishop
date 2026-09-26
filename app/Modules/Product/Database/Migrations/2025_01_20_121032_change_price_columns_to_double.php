<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangePriceColumnsToDouble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE products MODIFY COLUMN final_price DOUBLE');
        DB::statement('ALTER TABLE products MODIFY COLUMN price DOUBLE');
        DB::statement('ALTER TABLE products MODIFY COLUMN discounted_price DOUBLE');
        DB::statement('ALTER TABLE product_variants MODIFY COLUMN final_price DOUBLE');
        DB::statement('ALTER TABLE product_variants MODIFY COLUMN price DOUBLE');
        DB::statement('ALTER TABLE product_variants MODIFY COLUMN discounted_price DOUBLE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE products MODIFY COLUMN final_price VARCHAR(255)');
        DB::statement('ALTER TABLE products MODIFY COLUMN price VARCHAR(255)');
        DB::statement('ALTER TABLE products MODIFY COLUMN discounted_price VARCHAR(255)');
        DB::statement('ALTER TABLE product_variants MODIFY COLUMN final_price VARCHAR(255)');
        DB::statement('ALTER TABLE product_variants MODIFY COLUMN price VARCHAR(255)');
        DB::statement('ALTER TABLE product_variants MODIFY COLUMN discounted_price VARCHAR(255)');
    }
}
