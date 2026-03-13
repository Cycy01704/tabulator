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
            $table->string('gender')->nullable()->after('address');
            $table->date('dob')->nullable()->after('gender');
            $table->string('occupation')->nullable()->after('dob');
            $table->string('contact_number')->nullable()->after('occupation');
            $table->string('email')->nullable()->after('contact_number');
            $table->text('hobbies')->nullable()->after('email');
            $table->string('motto')->nullable()->after('hobbies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contestants', function (Blueprint $table) {
            //
        });
    }
};
