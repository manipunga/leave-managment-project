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
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_year_id')->nullable(); // Foreign key for calendar year
            $table->foreign('calendar_year_id')->references('id')->on('calendar_years')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropForeign(['calendar_year_id']);
            $table->dropColumn('calendar_year_id');
        });
    }
};
