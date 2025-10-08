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
        Schema::create('social_shares', function (Blueprint $table) {
            $table->id();
            $table->text('url'); // Shared page URL
            $table->string('page_title')->nullable(); // Page metadata
            $table->foreignId('social_platform_id')->constrained('social_platforms')->onDelete('cascade');
            $table->string('user_ip', 45)->nullable(); // IPv4/IPv6 support
            $table->text('user_agent')->nullable(); // Browser info
            $table->text('referrer')->nullable(); // Where user came from
            $table->json('metadata')->nullable(); // Flexible field for future data
            $table->timestamps();

            // Indexes for performance
            $table->index('social_platform_id');
            $table->index('created_at'); // For date range queries
            $table->index(['social_platform_id', 'created_at']); // Composite index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_shares');
    }
};
