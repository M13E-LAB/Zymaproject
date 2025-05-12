<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_badges', function (Blueprint $table) {
            // Ajouter les colonnes timestamps manquantes
            if (!Schema::hasColumn('user_badges', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('user_badges', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
        
        // Mise à jour de la colonne created_at pour les badges existants
        DB::statement('UPDATE user_badges SET created_at = earned_at WHERE created_at IS NULL');
        DB::statement('UPDATE user_badges SET updated_at = earned_at WHERE updated_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
