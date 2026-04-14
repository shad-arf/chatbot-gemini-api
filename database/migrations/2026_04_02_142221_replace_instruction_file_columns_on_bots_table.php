<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->json('instruction_files')->nullable()->after('instruction');
            $table->dropColumn(['instruction_file', 'instruction_mime']);
        });
    }

    public function down(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->string('instruction_file')->nullable()->after('instruction');
            $table->string('instruction_mime')->nullable()->after('instruction_file');
            $table->dropColumn('instruction_files');
        });
    }
};
