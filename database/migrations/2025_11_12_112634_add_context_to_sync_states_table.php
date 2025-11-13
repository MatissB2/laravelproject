<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sync_states', function (Blueprint $table) {
            $table->string('context')->default('default')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('sync_states', function (Blueprint $table) {
            $table->dropColumn('context');
        });
    }
};
