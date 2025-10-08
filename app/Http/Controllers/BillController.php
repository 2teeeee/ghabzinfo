<?php

namespace App\Http\Controllers;

use App\Models\ElectricityBill;
use App\Models\ElectricityBillExtra;
use App\Services\ElectricityBillService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    protected ElectricityBillService $electricityService;

    public function __construct(ElectricityBillService $electricityService)
    {
        $this->electricityService = $electricityService;
    }

    public function electricityBillIndex()
    {
        $bills = ElectricityBill::with('extras')->latest()->get();
        return view('bill.electricity.index', compact('bills'));
    }

    public function electricityBillInquire(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|string',
        ]);

        try {
            $data = $this->electricityService->inquire($request->bill_id);
            $params = $data['Parameters'] ?? [];

            DB::beginTransaction();

            // ایجاد رکورد اصلی قبض
            $bill = ElectricityBill::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'full_name' => $params['FullName'] ?? null,
                'address' => $params['Address'] ?? null,
                'amount' => $params['Amount'] ?? 0,
                'bill_id' => $params['BillID'] ?? $request->bill_id,
                'payment_id' => $params['PaymentID'] ?? null,
                'previous_date' => $params['PreviousDate'] ?? null,
                'current_date' => $params['CurrentDate'] ?? null,
                'payment_date' => $params['PaymentDate'] ?? null,
                'bill_pdf_url' => $params['BillPdfUrl'] ?? null,
                'tariff_type' => $params['TariffType'] ?? null,
                'customer_type' => $params['CustomerType'] ?? null,
                'sale_year' => $params['SaleYear'] ?? null,
                'cycle' => $params['Cycle'] ?? null,
                'average_consumption' => $params['AverageConsumption'] ?? null,
                'insurance_amount' => $params['InsuranceAmount'] ?? null,
                'tax_amount' => $params['TaxAmount'] ?? null,
                'paytoll_amount' => $params['PaytollAmount'] ?? null,
                'power_paytoll_amount' => $params['PowerPaytollAmount'] ?? null,
                'total_days' => $params['TotalDays'] ?? null,
                'status_code' => $data['Status']['Code'] ?? null,
                'status_description' => $data['Status']['Description'] ?? null,
            ]);

            // ذخیره اطلاعات اضافی از فیلد ExtraInfo (در JSON)
            if (!empty($params['ExtraInfo'])) {
                $extraData = json_decode($params['ExtraInfo'], true);
                if (is_array($extraData)) {
                    foreach ($extraData as $key => $value) {
                        ElectricityBillExtra::create([
                            'electricity_bill_id' => $bill->id,
                            'key' => $key,
                            'value' => $value,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('electricity.index')
                ->with('success', 'قبض با موفقیت استعلام و ذخیره شد.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function apiList()
    {
        $bills = ElectricityBill::with('extras')->where('user_id', Auth::id())->latest()->get();
        return response()->json($bills);
    }

    public function electricityBillShow(ElectricityBill $bill): View
    {
        $bill->load('extras');

        return view('bill.electricity.show', compact('bill'));
    }
}
