<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('beneficiaries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('impact_report_id')->constrained()->cascadeOnDelete();
        $table->string('name')->nullable();
        $table->string('location_name');
        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
