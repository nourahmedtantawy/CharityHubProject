<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('transaction_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('donation_id')->nullable()->constrained()->nullOnDelete();
        $table->string('gateway');
        $table->string('event_type'); // charge.succeeded, payment.failed, etc.
        $table->string('gateway_event_id')->nullable();
        $table->decimal('amount', 12, 2)->nullable();
        $table->string('currency', 3)->nullable();
        $table->enum('status', ['success', 'failure', 'refund', 'webhook']);
        $table->json('payload')->nullable(); // full gateway response
        $table->string('ip_address')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
