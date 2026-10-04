<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('follow_up_notes');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('weekly_target')->default(5)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('weekly_target');
        });
    }
};
