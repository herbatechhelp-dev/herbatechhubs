<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shortcuts', function (Blueprint $table): void {
            $table->unsignedInteger('sort_order')->default(0)->after('icon_path');
            $table->index('sort_order');
        });

        DB::table('shortcuts')
            ->orderBy('id')
            ->get(['id'])
            ->values()
            ->each(function (object $shortcut, int $index): void {
                DB::table('shortcuts')
                    ->where('id', $shortcut->id)
                    ->update(['sort_order' => $index + 1]);
            });
    }

    public function down(): void
    {
        Schema::table('shortcuts', function (Blueprint $table): void {
            $table->dropIndex(['sort_order']);
            $table->dropColumn('sort_order');
        });
    }
};