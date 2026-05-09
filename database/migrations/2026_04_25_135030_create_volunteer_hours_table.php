<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('volunteer_hours', function (Blueprint $table) {
        $table->id();
        $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
        $table->foreignId('volunteer_shift_id')->nullable()->constrained()->nullOnDelete();
        $table->decimal('hours', 5, 2);
        $table->date('date');
        $table->text('notes')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('volunteer_hours');
    }
};
