<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('volunteer_shifts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('location')->nullable();
        $table->dateTime('starts_at');
        $table->dateTime('ends_at');
        $table->unsignedInteger('max_volunteers')->default(10);
        $table->unsignedInteger('registered_count')->default(0);
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('volunteer_shifts');
    }
};
