<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('channel_settings')) {
            return;
        }

        Schema::create('channel_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->json('settings_json')->nullable();
            $table->timestamps();

            $table->unique('channel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_settings');
    }
};
