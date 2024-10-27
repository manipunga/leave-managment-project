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
        Schema::create('calendar_years', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // For storing the name, e.g., "Year 2024"
            $table->date('start_date');  // Starting date of the calendar year
            $table->date('end_date');    // Ending date of the calendar year
            $table->integer('total_leaves');  // Total leave allocation for the year
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_years');
    }
};
