<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->integer('health_score')->default(0); // Score santé de 0 à 100
            $table->integer('visual_score')->default(0); // Score visuel de 0 à 100
            $table->integer('diversity_score')->default(0); // Score diversité de 0 à 100
            $table->integer('total_score')->default(0); // Score total calculé
            $table->boolean('is_ai_scored')->default(false); // Si le score a été attribué par l'IA
            $table->json('ai_analysis')->nullable(); // Analyse détaillée par l'IA (JSON)
            $table->text('feedback')->nullable(); // Feedback textuel sur le repas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_scores');
    }
}; 