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
        Schema::table('contestants', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::table('criteria', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contestants_and_criteria_tables', function (Blueprint $table) {
            //
        });
    }
};
