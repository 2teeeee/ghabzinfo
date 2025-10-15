<x-admin-layout title="ثبت قبض جدید" header="ثبت قبض جدید">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">ثبت قبض جدید</h5>
                        <i class="bi bi-lightning-charge"></i>
                    </div>

                    <div class="card-body">
                        <form id="createBillForm" action="{{ route('admin.electricity_bills.store') }}" method="POST">
                            @csrf

                            {{-- تعیین نقش کاربر --}}
                            @php
                                $role = auth()->user()->roles()->pluck('name')->first();
                                $isCity   = $role === 'city';
                                $isOrgan  = $role === 'organ';
                                $isUnit   = $role === 'vahed';
                                $isCenter = $role === 'markaz';
                            @endphp

                            <div class="row">

                                {{-- City --}}
                                <div class="mb-3 col-md-6">
                                    <label for="city_id" class="form-label fw-bold">شهرستان</label>
                                    <select class="form-select" id="city_id" name="city_id"
                                            {{ $isCenter || $isUnit || $isOrgan ? 'disabled' : '' }} required>
                                        <option value="">انتخاب شهرستان...</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}"
                                                @selected(old('city_id', auth()->user()->city_id ?? null) == $city->id)>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Organ --}}
                                <div class="mb-3 col-md-6 position-relative">
                                    <label for="organ_id" class="form-label fw-bold">سازمان</label>
                                    <div id="organ-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                                    <select class="form-select" id="organ_id" name="organ_id"
                                            {{ $isCenter || $isUnit ? 'disabled' : '' }} required>
                                        @if($isCenter || $isUnit || $isOrgan)
                                            <option value="{{ auth()->user()->organ_id ?? '' }}">
                                                {{ auth()->user()->organ->name ?? '---' }}
                                            </option>
                                        @else
                                            <option value="">ابتدا شهرستان را انتخاب کنید...</option>
                                        @endif
                                    </select>
                                </div>

                                {{-- Unit --}}
                                <div class="mb-3 col-md-6 position-relative">
                                    <label for="unit_id" class="form-label fw-bold">واحد</label>
                                    <div id="unit-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                                    <select class="form-select" id="unit_id" name="unit_id"
                                            {{ $isCenter ? 'disabled' : '' }} required>
                                        @if($isCenter || $isUnit)
                                            <option value="{{ auth()->user()->unit_id ?? '' }}">
                                                {{ auth()->user()->unit->name ?? '---' }}
                                            </option>
                                        @else
                                            <option value="">ابتدا سازمان را انتخاب کنید...</option>
                                        @endif
                                    </select>
                                </div>

                                {{-- Center --}}
                                <div class="mb-3 col-md-6 position-relative">
                                    <label for="center_id" class="form-label fw-bold">مرکز</label>
                                    <div id="center-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                                    <select class="form-select" id="center_id" name="center_id" required
                                        {{ $isCenter ? 'disabled' : '' }}>
                                        @if($isCenter)
                                            <option value="{{ auth()->user()->center_id ?? '' }}">
                                                {{ auth()->user()->center->name ?? '---' }}
                                            </option>
                                        @else
                                            <option value="">ابتدا واحد را انتخاب کنید...</option>
                                        @endif
                                    </select>
                                </div>

                                {{-- Bill ID --}}
                                <div class="mb-3 col-md-12">
                                    <label for="bill_id" class="form-label fw-bold">شماره قبض</label>
                                    <input type="text" class="form-control" id="bill_id" name="bill_id"
                                           placeholder="مثلاً 1234567890" required>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-lightning-fill"></i> ثبت و استعلام قبض
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @push('scripts')
        <script>
            const apiBase = '/admin/api';

            // اگر کاربر مرکز نباشد، Selectها را وابسته لود می‌کنیم
            const role = "{{ $role }}";
            if (role !== 'markaz') {
                function fetchAndFill(url, targetSelect, loaderId, nextSelects = []) {
                    const loader = document.getElementById(loaderId);
                    const select = document.getElementById(targetSelect);

                    nextSelects.forEach(id => {
                        document.getElementById(id).innerHTML = `<option value="">ابتدا انتخاب قبلی را انجام دهید...</option>`;
                        document.getElementById(id).disabled = true;
                    });

                    loader.classList.remove('d-none');
                    select.disabled = true;

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                select.innerHTML = `<option value="">انتخاب کنید...</option>`;
                                data.data.forEach(item => {
                                    select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                                });
                                select.disabled = false;
                            } else {
                                select.innerHTML = `<option value="">خطا در دریافت داده</option>`;
                            }
                        })
                        .catch(() => {
                            select.innerHTML = `<option value="">خطا در ارتباط با سرور</option>`;
                        })
                        .finally(() => loader.classList.add('d-none'));
                }

                document.getElementById('city_id').addEventListener('change', e => {
                    const cityId = e.target.value;
                    if (!cityId) return;
                    fetchAndFill(`${apiBase}/organs?city_id=${cityId}`, 'organ_id', 'organ-loader', ['unit_id', 'center_id']);
                });

                document.getElementById('organ_id').addEventListener('change', e => {
                    const organId = e.target.value;
                    if (!organId) return;
                    fetchAndFill(`${apiBase}/units?organ_id=${organId}`, 'unit_id', 'unit-loader', ['center_id']);
                });

                document.getElementById('unit_id').addEventListener('change', e => {
                    const unitId = e.target.value;
                    if (!unitId) return;
                    fetchAndFill(`${apiBase}/centers?unit_id=${unitId}`, 'center_id', 'center-loader');
                });
            }
        </script>
    @endpush
</x-admin-layout>
