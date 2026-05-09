<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('donation_subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
        $table->decimal('amount', 12, 2);
        $table->string('currency', 3)->default('EGP');
        $table->enum('frequency', ['weekly', 'monthly', 'yearly'])->default('monthly');
        $table->string('gateway_subscription_id')->unique();
        $table->enum('status', ['active', 'paused', 'cancelled', 'past_due'])->default('active');
        $table->timestamp('next_billing_date')->nullable();
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('donation_subscriptions');
    }
};
