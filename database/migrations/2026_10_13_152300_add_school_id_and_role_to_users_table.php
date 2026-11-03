<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->constrained('schools')
                    ->restrictOnDelete();
            });
        }

        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', [
                    'super_admin',
                    'school_admin',
                    'teacher',
                    'student',
                ])->default('student');
            });
        }

        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('school_id');
            });
        }

        $columns = [];

        if (Schema::hasColumn('users', 'role')) {
            $columns[] = 'role';
        }

        if (Schema::hasColumn('users', 'is_active')) {
            $columns[] = 'is_active';
        }

        if (!empty($columns)) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};