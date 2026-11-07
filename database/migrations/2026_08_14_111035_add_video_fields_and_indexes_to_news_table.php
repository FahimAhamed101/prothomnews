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
        Schema::table('news', function (Blueprint $table) {
            $table->string('video_type', 20)->nullable()->after('image');
            $table->string('video_url')->nullable()->after('video_type');
            $table->string('video_path')->nullable()->after('video_url');

            $table->index(['status', 'created_at']);
            $table->index(['category_id', 'status', 'created_at']);
            $table->index(['status', 'view_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['category_id', 'status', 'created_at']);
            $table->dropIndex(['status', 'view_count']);
            $table->dropColumn(['video_type', 'video_url', 'video_path']);
        });
    }
};
