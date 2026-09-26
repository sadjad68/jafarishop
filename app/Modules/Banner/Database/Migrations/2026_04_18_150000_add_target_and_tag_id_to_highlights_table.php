<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('highlights', function (Blueprint $table) {
            $table->string('target', 16)->default('place')->after('type');
            $table->unsignedBigInteger('tag_id')->nullable()->after('target');
        });

        if (Schema::hasTable('tags')) {
            Schema::table('highlights', function (Blueprint $table) {
                $table->foreign('tag_id')->references('id')->on('tags')->nullOnDelete();
            });
        }

        $desktopMap = [
            'top-right' => 'top-first',
            'top-middle' => 'top-second',
            'top-left' => 'top-third',
            'long-middle' => 'middle-first',
            'bottom-right' => 'bottom-first',
            'bottom-left' => 'bottom-second',
        ];
        $mobileMap = [
            'top-right' => 'top-first',
            'top-left' => 'top-second',
            'middle-right' => 'top-third',
            'middle-left' => 'top-fourth',
            'bottom-right' => 'bottom-first',
            'bottom-left' => 'bottom-second',
        ];

        foreach ($desktopMap as $from => $to) {
            DB::table('highlights')->where('type', 'desktop')->where('place', $from)->update(['place' => $to]);
        }
        foreach ($mobileMap as $from => $to) {
            DB::table('highlights')->where('type', 'mobile')->where('place', $from)->update(['place' => $to]);
        }
    }

    public function down(): void
    {
        Schema::table('highlights', function (Blueprint $table) {
            if (Schema::hasColumn('highlights', 'tag_id')) {
                try {
                    $table->dropForeign(['tag_id']);
                } catch (\Throwable $e) {
                    // SQLite / missing FK
                }
            }
            $table->dropColumn(['target', 'tag_id']);
        });
    }
};
