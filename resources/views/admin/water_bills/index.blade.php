<x-admin-layout title="لیست قبوض آب" header="لیست قبوض آب">
    <div class="container py-4">
        <h4 class="mb-3">لیست قبوض آب</h4>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="bill_id" class="form-control" placeholder="جستجو بر اساس کد اشتراک"
                       value="{{ request('bill_id') }}">
                <button class="btn btn-primary" type="submit">جستجو</button>
                <a href="{{route('admin.water_bills.create')}}" class="btn btn-info ms-2">قبض جدید</a>
            </div>
        </form>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle">
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
                    <th>عملیات</th>
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
                        <td>{{ $account->latestPeriod->status_description ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.water_bills.show', $account->id) }}" class="btn btn-sm btn-info">جزئیات</a>
                            <form action="{{ route('admin.water_bills.refresh', $account->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">استعلام جدید</button>
                            </form>
                            <form action="{{ route('admin.water_bills.destroy', $bill->id) }}" method="POST" onsubmit="return confirm('آیا مطمئنید که می‌خواهید این قبض را حذف کنید؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">قبضی یافت نشد.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $accounts->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
