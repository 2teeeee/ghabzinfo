<x-main-layout>
    <div class="container py-4">
        <h4 class="mb-4">جزئیات قبض آب شماره {{ $bill->bill_id }}</h4>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ $bill->full_name ?? '---' }}</h5>
                <p><strong>آدرس:</strong> {{ $bill->address ?? '---' }}</p>
                <p><strong>مبلغ:</strong> {{ number_format($bill->amount ?? 0) }} ریال</p>
                <p><strong>تاریخ قرائت قبلی:</strong> {{ $bill->previous_date ?? '---' }}</p>
                <p><strong>تاریخ قرائت فعلی:</strong> {{ $bill->current_date ?? '---' }}</p>
                <p><strong>تاریخ پرداخت:</strong> {{ $bill->payment_date ?? '---' }}</p>
                <p><strong>کد وضعیت:</strong> {{ $bill->status_code }}</p>
                <p><strong>توضیح وضعیت:</strong> {{ $bill->status_description }}</p>

                @if ($bill->bill_pdf_url)
                    <a href="{{ $bill->bill_pdf_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                        مشاهده PDF قبض
                    </a>
                @endif
            </div>
        </div>

        <h5 class="mb-3">جزئیات اضافی</h5>

        @if($bill->extras->count())
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>عنوان</th>
                    <th>مقدار</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bill->extras as $extra)
                    <tr>
                        <td>{{ $extra->key }}</td>
                        <td>{{ $extra->value }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>اطلاعات اضافی برای این قبض ثبت نشده است.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('water.index') }}" class="btn btn-secondary">بازگشت به لیست قبض‌ها</a>
        </div>
    </div>
</x-main-layout>
