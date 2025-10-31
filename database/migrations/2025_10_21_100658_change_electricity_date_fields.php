<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('electricity_bill_periods', function (Blueprint $table) {
            $table->dateTime('current_date_tmp')->nullable()->after('current_date');
            $table->dateTime('previous_date_tmp')->nullable()->after('previous_date');
            $table->dateTime('payment_date_tmp')->nullable()->after('payment_date');
        });

        // 2️⃣ تبدیل داده‌های موجود از format mm/dd/YYYY H:i:s به Y-m-d H:i:s
        $bills = DB::table('electricity_bill_periods')->get();
        foreach ($bills as $bill) {
            if (!empty($bill->current_date)) {
                $parsedCD = \DateTime::createFromFormat('m/d/Y H:i:s', $bill->current_date);
                $parsedPD = \DateTime::createFromFormat('m/d/Y H:i:s', $bill->previous_date);
                $parsedPA = \DateTime::createFromFormat('m/d/Y H:i:s', $bill->payment_date);
                if ($parsedCD) {
                    DB::table('electricity_bill_periods')
                        ->where('id', $bill->id)
                        ->update(['current_date_tmp' => $parsedCD->format('Y-m-d H:i:s')]);
                }
                if ($parsedPD) {
                    DB::table('electricity_bill_periods')
                        ->where('id', $bill->id)
                        ->update(['previous_date_tmp' => $parsedPD->format('Y-m-d H:i:s')]);
                }
                if ($parsedPA) {
                    DB::table('electricity_bill_periods')
                        ->where('id', $bill->id)
                        ->update(['payment_date_tmp' => $parsedPA->format('Y-m-d H:i:s')]);
                }
            }
        }

        // 3️⃣ حذف ستون قدیمی و تغییر نام ستون موقت به current_date
        Schema::table('electricity_bill_periods', function (Blueprint $table) {
            $table->dropColumn('current_date');
            $table->dropColumn('previous_date');
            $table->dropColumn('payment_date');
            $table->renameColumn('current_date_tmp', 'current_date');
            $table->renameColumn('previous_date_tmp', 'previous_date');
            $table->renameColumn('payment_date_tmp', 'payment_date');
        });
    }

    public function down(): void
    {
        // بازگرداندن ستون به varchar و تبدیل داده‌ها
        Schema::table('electricity_bill_periods', function (Blueprint $table) {
            $table->string('current_date')->nullable()->after('current_date');
            $table->string('previous_date')->nullable()->after('previous_date');
            $table->string('payment_date')->nullable()->after('payment_date');
        });

        $bills = DB::table('electricity_bill_periods')->get();
        foreach ($bills as $bill) {
            if (!empty($bill->current_date)) {
                $formatted = \Carbon\Carbon::parse($bill->current_date)->format('m/d/Y H:i:s');
                DB::table('electricity_bill_periods')
                    ->where('id', $bill->id)
                    ->update(['current_date' => $formatted]);
            }
            if (!empty($bill->previous_date)) {
                $formatted = \Carbon\Carbon::parse($bill->previous_date)->format('m/d/Y H:i:s');
                DB::table('electricity_bill_periods')
                    ->where('id', $bill->id)
                    ->update(['previous_date' => $formatted]);
            }
            if (!empty($bill->payment_date)) {
                $formatted = \Carbon\Carbon::parse($bill->payment_date)->format('m/d/Y H:i:s');
                DB::table('electricity_bill_periods')
                    ->where('id', $bill->id)
                    ->update(['payment_date' => $formatted]);
            }
        }

        Schema::table('electricity_bill_periods', function (Blueprint $table) {
            $table->dropColumn('current_date');
            $table->dropColumn('previous_date');
            $table->dropColumn('payment_date');
        });
    }
};
