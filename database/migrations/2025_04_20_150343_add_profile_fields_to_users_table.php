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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('avatar')->nullable()->after('username');
            $table->text('bio')->nullable()->after('avatar');
            $table->json('location')->nullable()->after('bio');
            $table->json('favorite_stores')->nullable()->after('location');
            $table->json('preferences')->nullable()->after('favorite_stores');
            $table->integer('points')->default(0)->after('preferences');
            $table->integer('level')->default(1)->after('points');
            $table->timestamp('last_login_at')->nullable()->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'avatar',
                'bio',
                'location',
                'favorite_stores',
                'preferences',
                'points',
                'level',
                'last_login_at'
            ]);
        });
    }
};
