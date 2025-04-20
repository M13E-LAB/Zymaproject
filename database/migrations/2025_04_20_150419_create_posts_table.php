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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->string('store_name');
            $table->json('location')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('post_type')->default('price'); // 'price', 'deal', 'meal', 'review'
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Ajout d'index pour améliorer les performances
            $table->index('product_code');
            $table->index('post_type');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
