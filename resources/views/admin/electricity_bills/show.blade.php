<x-admin-layout title="جزئیات قبض برق" header="جزئیات قبض برق">
    <div class="container py-4">

        <h4 class="mb-3">جزئیات قبض برق</h4>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                اطلاعات اصلی قبض
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-3"><strong>کاربر:</strong> {{ $bill->user->name ?? '—' }}</div>
                    <div class="col-md-3"><strong>نام مشترک:</strong> {{ $bill->full_name }}</div>
                    <div class="col-md-3"><strong>شماره قبض:</strong> {{ $bill->bill_id }}</div>
                    <div class="col-md-3"><strong>تاریخ آخرین قبض:</strong> {{ $bill->current_date }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>آدرس:</strong> {{ $bill->address }}</div>
                    <div class="col-md-3"><strong>مبلغ:</strong> {{ number_format($bill->amount) }}</div>
                    <div class="col-md-3"><strong>وضعیت:</strong> {{ $bill->status_description }}</div>
                    <div class="col-md-3"><strong>PDF قبض:</strong>
                        @if($bill->bill_pdf_url)
                            <a href="{{ $bill->bill_pdf_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">دانلود</a>
                        @else
                            ندارد
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>نوع مشترک:</strong> {{ $bill->customer_type ?? '---' }}</div>
                    <div class="col-md-3"><strong>نوع تعرفه:</strong> {{ $bill->tariff_type ?? '---' }}</div>
                    <div class="col-md-3"><strong>دوره:</strong> {{ $bill->cycle ?? '---' }}</div>
                    <div class="col-md-3"><strong>سال فروش:</strong> {{ $bill->sale_year ?? '---' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>میانگین مصرف:</strong> {{ $bill->average_consumption ?? '---' }}</div>
                    <div class="col-md-3"><strong>تاریخ قرائت قبلی:</strong> {{ $bill->previous_date ?? '---' }}</div>
                    <div class="col-md-3"><strong>تاریخ پرداخت:</strong> {{ $bill->payment_date ?? '---' }}</div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>

        @if($bill->extras->isNotEmpty())
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    اطلاعات اضافی قبض
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach($bill->extras as $extra)
                            <div class="col">
                                <div class="card border-info h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $extra->key }}</h6>
                                        <p class="card-text">{{ $extra->value }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <a href="{{ route('admin.electricity_bills.index') }}" class="btn btn-secondary mt-3">بازگشت به لیست</a>

    </div>
</x-admin-layout>
