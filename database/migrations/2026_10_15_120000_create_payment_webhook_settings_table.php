<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_webhook_settings', function (Blueprint $table) {
            $table->id();
            $table->text('stripe_webhook_secret')->nullable();
            $table->text('paypal_webhook_id')->nullable();
            $table->text('paypal_client_id')->nullable();
            $table->text('paypal_secret')->nullable();
            $table->string('paypal_mode', 32)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_settings');
    }
};
