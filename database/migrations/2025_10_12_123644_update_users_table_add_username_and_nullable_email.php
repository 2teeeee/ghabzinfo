<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile')->unique()->after('username');
                $table->timestamp('mobile_verified_at')->nullable()->after('mobile');
            }
            $table->unsignedInteger('bill_limit')->default(10);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['username']);
        });
    }
};
