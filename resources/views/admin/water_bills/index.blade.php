<x-admin-layout title="لیست قبوض آب" header="لیست قبوض آب">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="لیست قبوض آب"
            icon="bi-droplet-fill"
            :back-url="route('admin.index')"
            :create-url="route('admin.water_bills.create')"
            :search="[
                'name' => 'bill_id',
                'placeholder' => 'جستجو بر اساس کد اشتراک'
            ]"
        />

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>کد اشتراک</th>
                            <th>نام مالک</th>
                            <th>آدرس</th>
                            <th>مرکز</th>
                            <th>تاریخ قبض</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th class="text-center">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td>{{ $loop->iteration + ($accounts->currentPage()-1) * $accounts->perPage() }}</td>
                                <td>{{ $account->bill_id }}</td>
                                <td>{{ $account->full_name }}</td>
                                <td>{{ $account->address }}</td>
                                <td>{{ $account->center->name ?? '-' }}</td>
                                <td>{{ $account->latestPeriod->current_date ?? '-' }}</td>
                                <td>{{ number_format($account->latestPeriod->amount ?? 0) }}</td>
                                <td>
                                        <span class="badge
                                            @if($account->latestPeriod?->status_description === 'پرداخت شده') bg-success
                                            @elseif($account->latestPeriod?->status_description === 'در انتظار پرداخت') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $account->latestPeriod->status_description ?? '-' }}
                                        </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.water_bills.show', $account->id) }}"
                                       class="btn btn-sm btn-outline-info me-1" title="مشاهده جزئیات">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.water_bills.refresh', $account->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning me-1" title="استعلام جدید">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.water_bills.destroy', $account->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('آیا مطمئنید که می‌خواهید این قبض را حذف کنید؟')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    قبضی یافت نشد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $accounts->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
