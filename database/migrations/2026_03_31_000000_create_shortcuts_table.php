<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shortcuts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->string('description', 255)->nullable();
            $table->string('category', 100);
            $table->string('icon_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'category']);
            $table->index('title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shortcuts');
    }
};