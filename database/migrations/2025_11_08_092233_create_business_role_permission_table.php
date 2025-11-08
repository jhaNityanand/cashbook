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
        Schema::create('business_role_permission', function (Blueprint $table) {
            $table->foreignId('business_role_id')->constrained('business_roles')->onDelete('cascade');
            $table->foreignId('business_permission_id')->constrained('business_permissions')->onDelete('cascade');

            $table->primary(['business_role_id', 'business_permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_role_permission');
    }
};
