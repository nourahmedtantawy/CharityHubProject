<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void
{
    Schema::create('campaigns', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description');
        $table->longText('content')->nullable();
        $table->decimal('goal_amount', 12, 2);
        $table->decimal('raised_amount', 12, 2)->default(0);
        $table->string('currency', 3)->default('EGP');
        $table->date('deadline');
        $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
        $table->string('featured_image')->nullable();
        $table->string('category')->nullable();
        $table->string('meta_title')->nullable();
        $table->string('meta_description')->nullable();
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->timestamps();
        $table->softDeletes();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
