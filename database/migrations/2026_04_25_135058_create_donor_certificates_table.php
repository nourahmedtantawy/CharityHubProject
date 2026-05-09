<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('donor_certificates', function (Blueprint $table) {
        $table->id();
        $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
        $table->string('certificate_number')->unique();
        $table->string('pdf_path')->nullable();
        $table->string('verification_token')->unique();
        $table->timestamp('issued_at')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('donor_certificates');
    }
};
