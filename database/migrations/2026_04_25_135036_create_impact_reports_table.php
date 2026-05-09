<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('impact_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('summary');
        $table->longText('content')->nullable();
        $table->unsignedInteger('beneficiaries_count')->default(0);
        $table->date('report_date');
        $table->boolean('is_published')->default(false);
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('impact_reports');
    }
};
