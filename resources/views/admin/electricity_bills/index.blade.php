<x-admin-layout title="لیست قبوض برق" header="لیست قبوض برق">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="لیست قبوض برق"
            icon="bi-lightning-charge-fill"
            :back-url="route('admin.index')"
            :create-url="route('admin.electricity_bills.create')"
            :search="[
                'name' => 'bill_id',
                'placeholder' => 'جستجو بر اساس کد اشتراک'
            ]"
        />

        {{-- جدول قبوض --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>کد اشتراک</th>
                            <th>نام مالک</th>
                            <th>آدرس</th>
                            <th>مرکز</th>
                            <th>تاریخ قبض</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($accounts->currentPage()-1) * $accounts->perPage() }}</td>
                                <td>{{ $account->bill_id }}</td>
                                <td>{{ $account->full_name }}</td>
                                <td>{{ Str::limit($account->address, 30, '...') }}</td>
                                <td>{{ $account->center->name ?? '-' }}</td>
                                <td class="text-nowrap">{{ $account->latestPeriod->current_date ?? '-' }}</td>
                                <td class="text-end">{{ number_format($account->latestPeriod->amount ?? 0) }} ریال</td>
                                <td class="text-center">
                                    @php
                                        $status = $account->latestPeriod->status_description ?? '-';
                                        $badgeClass = match($status) {
                                            'پرداخت‌شده' => 'success',
                                            'در انتظار پرداخت' => 'warning',
                                            'باطل‌شده' => 'secondary',
                                            default => 'light'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.electricity_bills.show', $account->id) }}"
                                           class="btn btn-outline-info btn-sm" title="جزئیات">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <form action="{{ route('admin.electricity_bills.refresh', $account->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm" title="استعلام جدید">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.electricity_bills.destroy', $account->id) }}" method="POST"
                                              onsubmit="return confirm('آیا مطمئنید که می‌خواهید این قبض را حذف کنید؟')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    <p class="m-0">هیچ قبضی یافت نشد.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- صفحه‌بندی --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $accounts->withQueryString()->links() }}
        </div>
    </div>

    @push('styles')
        <style>
            .table th, .table td {
                vertical-align: middle !important;
            }
            .table-hover tbody tr:hover {
                background-color: #f1f3f5;
            }
            .card {
                border-radius: 1rem;
            }
        </style>
    @endpush
</x-admin-layout>
