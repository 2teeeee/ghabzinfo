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
        Schema::create('water_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('organ_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('center_id')->nullable()->constrained()->onDelete('set null');
            $table->string('bill_id')->unique();
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('water_bill_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_account_id')->constrained()->onDelete('cascade');
            $table->integer('amount')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('previous_date')->nullable();
            $table->string('current_date')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('bill_pdf_url')->nullable();
            $table->string('status_code')->nullable();
            $table->string('status_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('water_bill_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_bill_period_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_accounts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::dropIfExists('water_bill_periods');
        Schema::dropIfExists('water_bill_extras');
    }
};
