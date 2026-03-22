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
        Schema::create('architects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->enum('document_type', ['CAU', 'ABD', 'CREA']);
            $table->string('document_number')->unique();
            $table->string('specialty')->nullable();
            $table->decimal('rt_percentage', 5, 2)->default(0);
            $table->json('bank_data')->nullable(); // bank, agency, account, pix
            $table->json('social_links')->nullable(); // instagram, portfolio
            $table->boolean('status')->default(true);
            $table->integer('rating')->default(0); // 1-5
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('architects');
    }
};
