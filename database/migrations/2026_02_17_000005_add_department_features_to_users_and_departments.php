<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('email')->constrained('departments')->nullOnDelete();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->string('color')->default('dark')->after('name'); // primary, danger, info, etc.
            $table->string('code')->nullable()->after('name'); // ti, ventas, etc. (para el usuario seed)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['color', 'code']);
        });
    }
};
