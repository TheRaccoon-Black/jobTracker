<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->date('next_follow_up')->nullable()->after('notes');
            $table->string('follow_up_notes')->nullable()->after('next_follow_up');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['next_follow_up', 'follow_up_notes']);
        });
    }
};
