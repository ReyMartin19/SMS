<?php
# c:\Users\Rey Martin\Projects\Laravel\SMS\database\migrations\2026_05_28_100000_add_track_to_subjects_table.php

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
        Schema::table('subjects', function (Blueprint $table) {
            $table->enum('track', ['stem', 'abm', 'humss', 'sports'])
                ->nullable()
                ->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('track');
        });
    }
};
