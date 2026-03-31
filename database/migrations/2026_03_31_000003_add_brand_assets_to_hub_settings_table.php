<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hub_settings', function (Blueprint $table): void {
            $table->string('favicon_path')->nullable()->after('favicon_url');
            $table->string('logo_path')->nullable()->after('favicon_path');
        });
    }

    public function down(): void
    {
        Schema::table('hub_settings', function (Blueprint $table): void {
            $table->dropColumn(['favicon_path', 'logo_path']);
        });
    }
};