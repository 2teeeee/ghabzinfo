<x-admin-layout title="جزئیات قبض گاز" header="جزئیات قبض گاز">
    <div class="container py-4">
        <h4 class="mb-4">جزئیات قبض گاز: {{ $account->bill_id }}</h4>

        {{-- اطلاعات کلی حساب --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $account->full_name }}</h5>
                <p class="card-text mb-1"><strong>آدرس:</strong> {{ $account->address }}</p>
                <p class="card-text mb-1">
                    <strong>سلسله‌مراتب:</strong>
                    {{ $account->center->name ?? '-' }} /
                    {{ $account->center->unit->name ?? '-' }} /
                    {{ $account->center->unit->organ->name ?? '-' }} /
                    {{ $account->center->unit->organ->city->name ?? '-' }}
                </p>
                <p class="card-text"><strong>کاربر ثبت‌کننده:</strong> {{ $account->user->name ?? '-' }}</p>
            </div>
        </div>

        {{-- Accordion دوره‌ها --}}
        <div class="accordion" id="periodAccordion">
            @foreach($account->periods as $index => $period)
                <div class="accordion-item mb-2 shadow-sm">
                    <h2 class="accordion-header" id="heading-{{ $period->id }}">
                        <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $period->id }}"
                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $period->id }}">
                            دوره {{ $period->current_date ?? '-' }} - {{ number_format($period->amount) }} تومان
                        </button>
                    </h2>
                    <div id="collapse-{{ $period->id }}"
                         class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                         aria-labelledby="heading-{{ $period->id }}"
                         data-bs-parent="#periodAccordion">
                        <div class="accordion-body">
                            <p class="mb-1"><strong>تاریخ قبلی:</strong> {{ $period->previous_date ?? '-' }}</p>
                            <p class="mb-1"><strong>تاریخ فعلی:</strong> {{ $period->current_date ?? '-' }}</p>
                            <p class="mb-1"><strong>تاریخ پرداخت:</strong> {{ $period->payment_date ?? '-' }}</p>
                            <p class="mb-1"><strong>PDF قبض:</strong>
                                @if($period->bill_pdf_url)
                                    <a href="{{ $period->bill_pdf_url }}" target="_blank" class="link-primary">مشاهده</a>
                                @else
                                    -
                                @endif
                            </p>
                            <p class="mb-1"><strong>نوع مصرف:</strong> {{ $period->consumption_type ?? '-' }}</p>
                            <p class="mb-1"><strong>رقم پیشین شمارشگر:</strong> {{ $period->previous_counter_digit ?? '-' }}</p>
                            <p class="mb-1"><strong>رقم فعلی شمارشگر:</strong> {{ $period->current_counter_digit ?? '-' }}</p>
                            <p class="mb-1"><strong>آبونمان:</strong> {{ $period->abonman ?? '-' }}</p>
                            <p class="mb-1"><strong>مالیات:</strong> {{ $period->tax ?? '-' }}</p>
                            <p class="mb-1"><strong>عوارض:</strong> {{ $period->insurance ?? '-' }}</p>
                            <p class="mb-1"><strong>وضعیت:</strong> <span class="badge bg-info">{{ $period->status_description ?? '-' }}</span></p>

                            {{-- اطلاعات اضافی --}}
                            @if($period->extras->count())
                                <h6 class="mt-3">اطلاعات اضافی</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($period->extras as $extra)
                                        <li class="list-group-item py-1 px-2">
                                            <strong>{{ $extra->key }}:</strong> {{ $extra->value }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('admin.gas_bills.index') }}" class="btn btn-secondary mt-3">بازگشت به لیست قبوض</a>
    </div>
</x-admin-layout>
