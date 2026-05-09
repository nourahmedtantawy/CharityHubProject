<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('volunteers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->json('skills')->nullable();
        $table->text('bio')->nullable();
        $table->enum('status', ['pending', 'approved', 'suspended'])->default('pending');
        $table->decimal('total_hours', 8, 2)->default(0);
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
