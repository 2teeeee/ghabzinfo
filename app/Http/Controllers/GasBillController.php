<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\City;
use App\Models\GasAccount;
use App\Models\GasBillExtra;
use App\Models\GasBillPeriod;
use App\Services\CommonService;
use App\Services\GasBillService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GasBillController extends Controller
{
    protected GasBillService $billService;

    public function __construct(GasBillService $billService)
    {
        $this->billService = $billService;
    }

    public function index(Request $request): View
    {

        $query = GasAccount::query()->whereNull('deleted_at')->with(['latestPeriod', 'latestPeriod.extras', 'center.unit.organ.city']);

        // فیلتر بر اساس شناسه قبض
        if ($request->filled('bill_id')) {
            $query->where('bill_id', 'like', '%' . $request->bill_id . '%');
        }

        // فیلتر دسترسی کاربران بر اساس سطح
        $user = auth()->user();
        if ($user->hasRole('city')) {
            $query->whereHas('center.unit.organ.city', fn($q) => $q->where('id', $user->city_id));
        } elseif ($user->hasRole('organ')) {
            $query->whereHas('center.unit.organ', fn($q) => $q->where('id', $user->organ_id));
        } elseif ($user->hasRole('unit')) {
            $query->whereHas('center.unit', fn($q) => $q->where('id', $user->unit_id));
        } elseif ($user->hasRole('center')) {
            $query->where('center_id', $user->center_id);
        }

        $accounts = $query->latest()->paginate(15);

        return view('admin.gas_bills.index', compact('accounts'));
    }

    public function show(int $accountId): View
    {
        $account = GasAccount::with([
            'periods.extras',
            'center.unit.organ.city',
            'user'
        ])->findOrFail($accountId);

        return view('admin.gas_bills.show', compact('account'));
    }

    public function create(): View
    {
        $user = Auth::user();

        $cities = [];
        $centers = [];

        if ($user->hasRole(['admin','city','organ','unit'])) {
            $cities = City::orderBy('name')->get(['id', 'name']);
            $centers = Center::with('unit.organ.city')->get(['id', 'name', 'unit_id']);
        }

        return view('admin.gas_bills.create', compact('centers', 'cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // اعتبارسنجی
        $rules = ['bill_id' => 'required|string'];
        if ($user->hasRole(['admin','city','organ','unit'])) {
            $rules['center_id'] = 'required|exists:centers,id';
        }
        $request->validate($rules);

        $centerId = $user->hasRole('center') ? $user->center_id : $request->center_id;

        try {
            $data = $this->billService->inquire($request->bill_id);
            $params = $data['Parameters'] ?? [];
            $extraData = !empty($params['ExtraInfo']) ? json_decode($params['ExtraInfo'], true) : [];

            DB::beginTransaction();

            $center = Center::with('unit.organ.city')->findOrFail($centerId);

            // حساب اصلی قبض
            $account = GasAccount::updateOrCreate(
                ['bill_id' => $request->bill_id],
                [
                    'user_id' => $user->id,
                    'center_id' => $centerId,
                    'unit_id' => $center->unit_id,
                    'organ_id' => $center->unit->organ_id,
                    'city_id' => $center->unit->organ->city_id,
                    'full_name' => $params['FullName'] ?? null,
                    'address' => $params['Address'] ?? null,
                ]
            );
            // بررسی وجود دوره بر اساس current_date
            $period = GasBillPeriod::updateOrCreate(
                [
                    'gas_account_id' => $account->id,
                    'current_date' => CommonService::normalizeDate($params['CurrentDate'] ?? null),
                ],
                [
                    'amount' => $params['Amount'] ?? 0,
                    'payment_id' => $params['PaymentID'] ?? null,
                    'previous_date' => CommonService::normalizeDate($params['PreviousDate'] ?? null),
                    'payment_date' => CommonService::normalizeDate($params['PaymentDate'] ?? null),
                    'bill_pdf_url' => $params['BillPdfUrl'] ?? null,
                    'consumption_type' => $params['ConsumptionType'] ?? null,
                    'previous_counter_digit' => $params['PreviousCounterDigit'] ?? null,
                    'current_counter_digit' => $params['CurrentCounterDigit'] ?? null,
                    'abonman' => $params['Abonman'] ?? null,
                    'tax' => $params['Tax'] ?? null,
                    'insurance' => $params['Insurance'] ?? null,
                    'status_code' => $data['Status']['Code'] ?? null,
                    'status_description' => $data['Status']['Description'] ?? null,
                ]
            );

            // اطلاعات اضافی: ابتدا حذف قبلی و سپس ذخیره جدید
            $period->extras()->delete();
            foreach ($extraData as $key => $value) {
                GasBillExtra::create([
                    'gas_bill_period_id' => $period->id,
                    'key' => $key,
                    'value' => $value,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.gas.index')
                ->with('success', 'قبض با موفقیت ثبت یا بروزرسانی شد.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function refresh(GasAccount $account): RedirectResponse
    {
        try {
            // استعلام از سرویس
            $data = $this->billService->inquire($account->bill_id);
            $params = $data['Parameters'] ?? [];

            if (empty($params)) {
                return back()->withErrors(['error' => 'اطلاعات قبض دریافت نشد.']);
            }

            DB::beginTransaction();

            // اطلاعات دوره
            $periodData = [
                'amount' => $params['Amount'] ?? 0,
                'payment_id' => $params['PaymentID'] ?? null,
                'previous_date' => CommonService::normalizeDate($params['PreviousDate'] ?? null),
                'payment_date' => CommonService::normalizeDate($params['PaymentDate'] ?? null),
                'bill_pdf_url' => $params['BillPdfUrl'] ?? null,
                'consumption_type' => $params['ConsumptionType'] ?? null,
                'previous_counter_digit' => $params['PreviousCounterDigit'] ?? null,
                'current_counter_digit' => $params['CurrentCounterDigit'] ?? null,
                'abonman' => $params['Abonman'] ?? null,
                'tax' => $params['Tax'] ?? null,
                'insurance' => $params['Insurance'] ?? null,
                'status_code' => $data['Status']['Code'] ?? null,
                'status_description' => $data['Status']['Description'] ?? null,
            ];

            // بررسی اینکه آیا دوره با current_date موجود است یا خیر
            $period = $account->periods()->updateOrCreate(
                ['current_date' => CommonService::normalizeDate($periodData['current_date'])],
                $periodData
            );

            // ذخیره یا آپدیت Extras
            if (!empty($params['ExtraInfo'])) {
                $extraData = json_decode($params['ExtraInfo'], true);
                if (is_array($extraData)) {
                    foreach ($extraData as $key => $value) {
                        $period->extras()->updateOrCreate(
                            ['key' => $key],
                            ['value' => $value]
                        );
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.gas_bills.show', $account->id)
                ->with('success', 'استعلام جدید قبض با موفقیت انجام شد.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $account = GasAccount::withTrashed()->find($id);

        if (! $account) {
            return redirect()->back()->withErrors(['error' => 'قبض مورد نظر پیدا نشد.']);
        }

        // بررسی دسترسی کاربر بر اساس نقش
        $user = Auth::user();
        if ($user->hasRole('center') && $account->center_id !== $user->center_id) {
            return redirect()->back()->withErrors(['error' => 'شما مجاز به حذف این قبض نیستید.']);
        }

        $account->delete();

        return redirect()
            ->route('admin.gas_bills.index')
            ->with('success', 'قبض با موفقیت حذف شد.');
    }

}
