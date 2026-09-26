<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('banks', function (Blueprint $table) {
            $table->integer('sort')->nullable()->after('gateway_tariff');
        });

        $banks = DB::table('banks')->orderBy('id')->get(['id']);
        foreach ($banks as $index => $bank) {
            DB::table('banks')->where('id', $bank->id)->update(['sort' => $index + 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
};
