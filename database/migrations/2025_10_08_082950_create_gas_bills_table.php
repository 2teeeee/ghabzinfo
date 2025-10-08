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
        Schema::create('gas_bills', function (Blueprint $table) {
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
            $table->string('consumption_type')->nullable();
            $table->string('previous_counter_digit')->nullable();
            $table->string('current_counter_digit')->nullable();
            $table->string('abonman')->nullable();
            $table->string('tax')->nullable();
            $table->string('insurance')->nullable();
            $table->string('status_code')->nullable();
            $table->string('status_description')->nullable();
            $table->timestamps();
        });


        Schema::create('gas_bill_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_bill_id')->constrained()->onDelete('cascade');
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
        Schema::table('gas_bills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::dropIfExists('gas_bills');
        Schema::dropIfExists('gas_bill_extras');

    }
};
