<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeRedirectColumnsToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE redirects MODIFY COLUMN new_address LONGTEXT');
        DB::statement('ALTER TABLE redirects MODIFY COLUMN old_address LONGTEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE redirects MODIFY COLUMN new_address VARCHAR(255)');
        DB::statement('ALTER TABLE redirects MODIFY COLUMN old_address VARCHAR(255)');
    }
}
