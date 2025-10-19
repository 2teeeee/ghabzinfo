<x-admin-layout title="گزارش قبوض برق ماه آخر" header="گزارش قبوض برق ماه آخر">
    <div class="container-fluid py-4">
        {{-- هدر صفحه --}}
        <x-admin-page-header
            title="گزارش قبوض برق ماه آخر"
            icon="bi-graph-up-arrow"
            :back-url="route('admin.index')"
        />

        {{-- کارت گزارش --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-primary">
                    📅 گزارش قبوض برق ماه آخر ({{ $lastMonth ?? 'نامشخص' }})
                </h5>

                {{-- جدول داده‌ها --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>مرکز</th>
                            <th>مبلغ کل</th>
                            <th>کم‌باری</th>
                            <th>میان‌باری</th>
                            <th>پرباری</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reports as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->center_name ?? '-' }}</td>
                                <td class="text-success fw-bold">{{ number_format($item->total_amount) }}</td>
                                <td>{{ number_format($item->low_amount) }}</td>
                                <td>{{ number_format($item->mid_amount) }}</td>
                                <td>{{ number_format($item->peak_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    <p class="m-0">هیچ گزارشی یافت نشد.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- نمودارها --}}
        <div class="row mt-4 g-4">
            <div class="col-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-center fw-bold mb-3">💰 مبلغ کل قبوض به تفکیک مرکز</h6>
                        <canvas id="totalChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-center fw-bold mb-3">🔵 مصرف کم‌باری (Low)</h6>
                        <canvas id="lowChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-center fw-bold mb-3">🟢 مصرف میان‌باری (Mid)</h6>
                        <canvas id="midChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-center fw-bold mb-3">🔴 مصرف پرباری (Peak)</h6>
                        <canvas id="peakChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- لود Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const centers = @json($reports->pluck('center_name'));
            const totals = @json($reports->pluck('total_amount'));
            const lows = @json($reports->pluck('low_amount'));
            const mids = @json($reports->pluck('mid_amount'));
            const peaks = @json($reports->pluck('peak_amount'));

            const makeChart = (id, label, data, color) => {
                new Chart(document.getElementById(id), {
                    type: 'bar',
                    data: {
                        labels: centers,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: color,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => value.toLocaleString()
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            makeChart('totalChart', 'مبلغ کل', totals, '#42464d');
            makeChart('lowChart', 'کم‌باری', lows, '#2563eb');
            makeChart('midChart', 'میان‌باری', mids, '#10b981');
            makeChart('peakChart', 'پرباری', peaks, '#ef4444');
        </script>
    </div>
</x-admin-layout>
