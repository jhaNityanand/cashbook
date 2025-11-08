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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashbook_id')->constrained('cashbooks')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('party_name');
            $table->text('remark')->nullable();
            $table->text('document')->nullable();
            $table->decimal('amount', 15, 2);
            $table->dateTime('transaction_datetime')->useCurrent();
            $table->text('description')->nullable();
            $table->json('custom_fields')->nullable();
            $table->enum('type', ['in', 'out']);
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
