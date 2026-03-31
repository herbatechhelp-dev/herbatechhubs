<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hub_settings', function (Blueprint $table): void {
            $table->decimal('logo_zoom', 4, 2)->default(1)->after('logo_path');
            $table->unsignedTinyInteger('logo_position_x')->default(50)->after('logo_zoom');
            $table->unsignedTinyInteger('logo_position_y')->default(50)->after('logo_position_x');
        });
    }

    public function down(): void
    {
        Schema::table('hub_settings', function (Blueprint $table): void {
            $table->dropColumn(['logo_zoom', 'logo_position_x', 'logo_position_y']);
        });
    }
};