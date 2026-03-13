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
            // Drop naming specific unique index if exists, otherwise drop by column
            // In the original migration it was defined as $table->integer('number')->unique();
            // Laravel usually names this 'table_column_unique'
            $table->dropUnique('contestants_number_unique');
            
            // Add composite unique index
            $table->unique(['number', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contestants', function (Blueprint $table) {
            $table->dropUnique(['number', 'event_id']);
            $table->unique('number');
        });
    }
};
