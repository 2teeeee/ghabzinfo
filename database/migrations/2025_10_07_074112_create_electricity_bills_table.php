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
        Schema::create('electricity_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();
            $table->integer('amount')->nullable();
            $table->string('bill_id');
            $table->string('payment_id')->nullable();
            $table->string('previous_date')->nullable();
            $table->string('current_date')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('bill_pdf_url')->nullable();
            $table->string('tariff_type')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('sale_year')->nullable();
            $table->string('cycle')->nullable();
            $table->string('average_consumption')->nullable();
            $table->string('insurance_amount')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('paytoll_amount')->nullable();
            $table->string('power_paytoll_amount')->nullable();
            $table->string('total_days')->nullable();
            $table->string('status_code')->nullable();
            $table->string('status_description')->nullable();
            $table->timestamps();
        });

        Schema::create('electricity_bill_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('electricity_bill_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('electricity_bills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::dropIfExists('electricity_bills');
        Schema::dropIfExists('electricity_bill_extras');
    }
};
