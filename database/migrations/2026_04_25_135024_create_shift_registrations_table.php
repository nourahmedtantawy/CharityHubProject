<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('shift_registrations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
        $table->foreignId('volunteer_shift_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['registered', 'attended', 'absent', 'cancelled'])->default('registered');
        $table->timestamps();
        $table->unique(['volunteer_id', 'volunteer_shift_id']);
    });
}
    public function down(): void
    {
        Schema::dropIfExists('shift_registrations');
    }
};
