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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama_kategori');
            $table->double('price')->nullable();
            $table->longText('description');
            $table->integer('estimation')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Nullable parent_id for subcategories
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade'); // Self-referencing foreign key
            $table->enum('status_kategori', ['active', 'nonactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
