<x-main-layout>
    <div class="container py-4">
        <h4 class="mb-3">استعلام قبض برق</h4>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first('error') ?? $errors->first() }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('electricity.inquire') }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="bill_id">شناسه قبض برق</label>
                <input type="text" name="bill_id" id="bill_id" class="form-control" required>
            </div>
            <button class="btn btn-primary">استعلام و ذخیره</button>
        </form>

        <hr>

        <h5>قبض‌های ذخیره‌شده</h5>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>آدرس</th>
                <th>شناسه قبض</th>
                <th>مبلغ</th>
                <th>تاریخ پرداخت</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($bills as $bill)
                <tr>
                    <td>{{ $bill->id }}</td>
                    <td>{{ $bill->full_name }}</td>
                    <td>{{ $bill->address }}</td>
                    <td>{{ $bill->bill_id }}</td>
                    <td>{{ number_format($bill->amount) }} ریال</td>
                    <td>{{ $bill->payment_date }}</td>
                    <td>
                        <a href="{{ route('electricity.show', $bill) }}" class="btn btn-sm btn-info">
                            مشاهده جزئیات
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-main-layout>
