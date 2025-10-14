<?php

namespace App\Http\Controllers;

use App\Models\ElectricityBill;
use App\Models\ElectricityBillExtra;
use App\Models\GasBill;
use App\Models\GasBillExtra;
use App\Models\WaterBill;
use App\Models\WaterBillExtra;
use App\Services\BillLimitService;
use App\Services\ElectricityBillService;
use App\Services\GasBillService;
use App\Services\WaterBillService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    protected ElectricityBillService $electricityService;
    protected GasBillService $gasService;
    protected WaterBillService $waterService;
    protected BillLimitService $billLimitService;

    public function __construct(
        ElectricityBillService $electricityService,
        GasBillService $gasService,
        WaterBillService $waterService,
        BillLimitService $billLimitService,
    )
    {
        $this->electricityService = $electricityService;
        $this->gasService = $gasService;
        $this->waterService = $waterService;
        $this->billLimitService = $billLimitService;
    }

    public function electricityBillIndex()
    {
        $bills = ElectricityBill::with(['extras', 'user'])
            ->when(!Auth::user()->hasRole(['admin', 'manager']), function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(15);
        return view('bill.electricity.index', compact('bills'));
    }

    public function electricityBillInquire(Request $request)
    {
        $validated = $request->validate([
            'bill_id' => ['required', 'string', 'max:50'],
        ]);

        $user = Auth::user();

        if (!$this->billLimitService->checkLimit($user, 'electricity')) {
            return back()->withErrors(['limit' => 'سقف مجاز شما برای ثبت قبض پر شده است.']);
        }

        try {
            DB::beginTransaction();

            $data = $this->electricityService->inquire($validated['bill_id']);
            $params = $data['Parameters'] ?? [];

            DB::beginTransaction();

            // جلوگیری از ذخیره قبض تکراری در همان دوره
            $existing = ElectricityBill::where('bill_id', $validated['bill_id'])
                ->where('current_date', $params['CurrentDate'] ?? null)
                ->first();

            if ($existing) {
                throw new \Exception('این قبض قبلاً برای این دوره ثبت شده است.');
            }

            $bill = ElectricityBill::create([
                'user_id' => $user?->id,
                'full_name' => $params['FullName'] ?? null,
                'address' => $params['Address'] ?? null,
                'amount' => $params['Amount'] ?? 0,
                'bill_id' => $params['BillID'] ?? $validated['bill_id'],
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

            // ذخیره اطلاعات اضافی
            $extraInfo = $params['ExtraInfo'] ?? null;
            if ($extraInfo) {
                $extraData = is_string($extraInfo) ? json_decode($extraInfo, true) : $extraInfo;
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

    public function electricityBillShow(ElectricityBill $bill): View
    {
        $bill->load('extras');

        return view('bill.electricity.show', compact('bill'));
    }

    public function gasBillIndex()
    {
        $bills = GasBill::with('extras')->latest()->get();
        return view('bill.gas.index', compact('bills'));
    }

    public function gasBillInquire(Request $request)
    {
        $request->validate([
            'bill_id' => 'nullable|string',
            'participate_code' => 'nullable|string',
        ]);


        if (empty($request->bill_id) && empty($request->participate_code)) {
            return back()->withErrors(['input' => 'حداقل یکی از فیلدهای شناسه قبض یا کد اشتراک باید وارد شود.']);
        }

        if (!$this->billLimitService->checkLimit(Auth::user(), 'gas')) {
            return back()->withErrors(['limit' => 'سقف مجاز شما برای ثبت قبض پر شده است.']);
        }

        try {
            $data = $this->gasService->inquire($request->bill_id, $request->participate_code);
            $params = $data['Parameters'] ?? [];

            DB::beginTransaction();

            // ایجاد رکورد اصلی قبض
            $bill = GasBill::create([
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
                'consumption_type' => $params['CunsumptionType'] ?? null,
                'previous_counter_digit' => $params['PreviousCounterDigit'] ?? null,
                'current_counter_digit' => $params['CurrentCounterDigit'] ?? null,
                'abonman' => $params['Abonman'] ?? null,
                'tax' => $params['Tax'] ?? null,
                'insurance' => $params['Insurance'] ?? null,
                'status_code' => $data['Status']['Code'] ?? null,
                'status_description' => $data['Status']['Description'] ?? null,
            ]);

            // ذخیره اطلاعات اضافی از فیلد ExtraInfo (در JSON)
            if (!empty($params['ExtraInfo'])) {
                $extraData = json_decode($params['ExtraInfo'], true);
                if (is_array($extraData)) {
                    foreach ($extraData as $key => $value) {
                        GasBillExtra::create([
                            'gas_bill_id' => $bill->id,
                            'key' => $key,
                            'value' => $value,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('gas.index')
                ->with('success', 'قبض با موفقیت استعلام و ذخیره شد.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function gasBillShow(GasBill $bill): View
    {
        $bill->load('extras');

        return view('bill.gas.show', compact('bill'));
    }

    public function waterBillIndex()
    {
        $bills = WaterBill::with('extras')->latest()->get();
        return view('bill.water.index', compact('bills'));
    }

    public function waterBillInquire(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|string',
        ]);

        if (!$this->billLimitService->checkLimit(Auth::user(), 'water')) {
            return back()->withErrors(['limit' => 'سقف مجاز شما برای ثبت قبض پر شده است.']);
        }

        try {
            $data = $this->waterService->inquire($request->bill_id);
            $params = $data['Parameters'] ?? [];

            DB::beginTransaction();

            // ایجاد رکورد اصلی قبض
            $bill = WaterBill::create([
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
                'status_code' => $data['Status']['Code'] ?? null,
                'status_description' => $data['Status']['Description'] ?? null,
            ]);

            // ذخیره اطلاعات اضافی از فیلد ExtraInfo (در JSON)
            if (!empty($params['ExtraInfo'])) {
                $extraData = json_decode($params['ExtraInfo'], true);
                if (is_array($extraData)) {
                    foreach ($extraData as $key => $value) {
                        WaterBillExtra::create([
                            'water_bill_id' => $bill->id,
                            'key' => $key,
                            'value' => $value,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('water.index')
                ->with('success', 'قبض با موفقیت استعلام و ذخیره شد.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function waterBillShow(WaterBill $bill): View
    {
        $bill->load('extras');

        return view('bill.water.show', compact('bill'));
    }

    public function apiList()
    {
        $bills = ElectricityBill::with('extras')->where('user_id', Auth::id())->latest()->get();
        return response()->json($bills);
    }
}
