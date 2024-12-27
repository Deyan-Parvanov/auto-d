<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('category');
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('engine_type');
            $table->integer('horsepower');
            $table->integer('total_kilometers');
            $table->string('color');
            $table->string('city');
            $table->integer('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('listings', [
            'category', 'make', 'model', 'year', 'engine_type', 'horsepower', 'total_kilometers', 'color', 'city', 'price'
        ]);
    }
};
