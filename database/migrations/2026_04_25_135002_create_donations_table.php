<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('donations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('donor_name');
        $table->string('donor_email');
        $table->string('donor_phone')->nullable();
        $table->decimal('amount', 12, 2);
        $table->string('currency', 3)->default('EGP');
        $table->enum('type', ['one_time', 'recurring'])->default('one_time');
        $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
        $table->string('gateway')->default('stripe'); // stripe | paymob
        $table->string('gateway_transaction_id')->nullable()->unique();
        $table->string('idempotency_key')->unique();
        $table->boolean('is_anonymous')->default(false);
        $table->text('message')->nullable();
        $table->timestamp('donated_at')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
