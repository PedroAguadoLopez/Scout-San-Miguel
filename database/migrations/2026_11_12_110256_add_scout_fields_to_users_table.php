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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('username')->unique()->after('name');
            $table->date('birth_date')->nullable()->after('email');
            $table->string('role')->default('minor')->index()->after('birth_date');
            $table->string('scout_section')->default('grupo')->after('role');
            $table->string('phone', 30)->nullable()->after('scout_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['username', 'birth_date', 'role', 'scout_section', 'phone']);
        });
    }
};
