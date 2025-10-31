<x-admin-layout title="گزارش مصرف گاز" header="داشبورد گزارش مصرف گاز">
    <div class="container-fluid py-4">

        {{-- 🔹 فیلتر تاریخ (شمسی) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.reports.gas.dashboard') }}" method="GET" class="row g-3 align-items-end">
                    @php
                        $months = [
                            3 => 'فروردین',
                            4 => 'اردیبهشت',
                            5 => 'خرداد',
                            6 => 'تیر',
                            7 => 'مرداد',
                            8 => 'شهریور',
                            9 => 'مهر',
                            10 => 'آبان',
                            11 => 'آذر',
                            12 => 'دی',
                            1 => 'بهمن',
                            2 => 'اسفند',
                        ];
                    @endphp

                    <div class="col-md-3">
                        <label for="month" class="form-label">ماه</label>
                        <select name="month" id="month" class="form-select">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == $month ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="year" class="form-label">سال</label>
                        <select name="year" id="year" class="form-select">
                            @foreach(range(jdate()->getYear() - 5, jdate()->getYear()) as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 🔹 آمار کلی --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-muted mb-1">تعداد کنتورها</h6>
                    <h4 class="mb-0">{{ $counterCount }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-muted mb-1">مجموع مبلغ گاز (ریال)</h6>
                    <h4 class="mb-0">{{ number_format($totalAmount) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-muted mb-1">مصرف گاز (متر مکعب)</h6>
                    <h4 class="mb-0">{{ number_format($totalConsumption) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-muted mb-1">کاهش مصرف نسبت به سال قبل</h6>
                    <h4 class="mb-0">{{ $consumptionDecrease !== null ? $consumptionDecrease . '%' : '-' }}</h4>
                </div>
            </div>
        </div>

        {{-- 🔹 نمودار مبلغ سالانه --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-center mb-3">📊 مقایسه مبلغ قبوض در ۳ سال گذشته</h6>
                <canvas id="yearlyChart" height="120"></canvas>
            </div>
        </div>

        {{-- 🔹 نمودار مصرف فصلی --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold text-center mb-3">⚡ مصرف فصلی گاز (۳ ماه اخیر)</h6>
                <canvas id="seasonChart" height="120"></canvas>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // نمودار سالانه
            const yearlyLabels = @json(array_column($yearlyComparison, 'year'));
            const yearlyData = @json(array_column($yearlyComparison, 'amount'));

            new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: {
                    labels: yearlyLabels,
                    datasets: [{
                        label: 'مجموع مبلغ (ریال)',
                        data: yearlyData,
                        backgroundColor: '#2563eb',
                        borderRadius: 8
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });

            // نمودار فصلی
            const seasonLabels = @json($seasonData->pluck('month'));
            const seasonValues = @json($seasonData->pluck('value'));

            new Chart(document.getElementById('seasonChart'), {
                type: 'line',
                data: {
                    labels: seasonLabels,
                    datasets: [{
                        label: 'مصرف گاز (متر مکعب)',
                        data: seasonValues,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.3)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { responsive: true }
            });
        </script>
    @endpush
</x-admin-layout>
