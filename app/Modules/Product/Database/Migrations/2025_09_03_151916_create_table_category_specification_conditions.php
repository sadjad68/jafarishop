<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_specification_conditions', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('specification_value_id');

            $table->primary(['category_id', 'specification_value_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_specification_conditions');
    }
};
