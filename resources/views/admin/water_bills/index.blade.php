<x-admin-layout title="لیست قبوض آب" header="لیست قبوض آب">
    <div class="container py-4">

        <h4 class="mb-3">لیست قبوض آب</h4>

        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <select name="user_id" class="form-select">
                    <option value="">انتخاب کاربر</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">فیلتر</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                <tr>
                    <th>کاربر</th>
                    <th>نام مشترک</th>
                    <th>آدرس</th>
                    <th>شماره اشتراک</th>
                    <th>تاریخ آخرین قبض</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bills as $bill)
                    <tr>
                        <td>{{ $bill->user->name ?? '—' }}</td>
                        <td>{{ $bill->full_name }}</td>
                        <td>{{ $bill->address }}</td>
                        <td>{{ $bill->bill_id }}</td>
                        <td>{{ $bill->current_date }}</td>
                        <td>{{ number_format($bill->amount) }}</td>
                        <td>{{ $bill->status_description }}</td>
                        <td>
                            <a href="{{ route('admin.water_bills.show', $bill->id) }}" class="btn btn-sm btn-info">نمایش</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">اطلاعی یافت نشد</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $bills->withQueryString()->links() }}
    </div>
</x-admin-layout>
