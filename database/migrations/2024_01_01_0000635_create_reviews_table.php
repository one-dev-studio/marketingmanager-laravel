<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->text('content');
            $table->string('platform');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('author')->nullable();
            $table->string('author_email')->nullable();
            $table->timestamp('date')->nullable();
            $table->enum('sentiment', ['positive', 'negative', 'neutral'])->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('organization_id');
            $table->index('brand_id');
            $table->index('platform');
            $table->index('sentiment');
            $table->index('status');
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
