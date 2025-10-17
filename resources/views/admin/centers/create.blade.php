<x-admin-layout title="ثبت مرکز جدید" header="ثبت مرکز جدید">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ثبت مرکز جدید"
            icon="bi-buildings"
            :back-url="route('admin.centers.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">
            <div class="card-body px-4 py-5">
                <form action="{{ route('admin.centers.store') }}" method="POST" id="createCenterForm">
                    @csrf

                    @php
                        $role = auth()->user()->roles()->pluck('name')->first();
                        $isCity   = $role === 'city';
                        $isOrgan  = $role === 'organ';
                        $isAdmin  = $role === 'admin';
                    @endphp

                    <div class="row g-4">
                        {{-- شهرستان --}}
                        <div class="col-md-4">
                            <label for="city_id" class="form-label fw-bold">شهرستان</label>
                            <select id="city_id" name="city_id" class="form-select"
                                    {{ $isOrgan ? 'disabled' : '' }} required>
                                <option value="">انتخاب شهرستان...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- سازمان --}}
                        <div class="col-md-4 position-relative">
                            <label for="organ_id" class="form-label fw-bold">سازمان</label>
                            <div id="organ-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                            <select id="organ_id" name="organ_id" class="form-select"
                                    {{ $isOrgan ? 'disabled' : '' }} required>
                                <option value="">ابتدا شهرستان را انتخاب کنید...</option>
                            </select>
                        </div>

                        {{-- واحد --}}
                        <div class="col-md-4 position-relative">
                            <label for="unit_id" class="form-label fw-bold">واحد</label>
                            <div id="unit-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                            <select id="unit_id" name="unit_id" class="form-select" required>
                                <option value="">ابتدا سازمان را انتخاب کنید...</option>
                            </select>
                        </div>

                        {{-- نام مرکز --}}
                        <div class="col-12">
                            <label for="name" class="form-label fw-bold">نام مرکز</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="مثلاً مرکز شماره 1" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-check2-circle me-2"></i>
                            ثبت مرکز
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @push('scripts')
        <script>
            const apiBase = '/admin/api';
            const role = "{{ $role }}";

            if (role !== 'organ') {
                const fetchAndFill = (url, targetSelect, loaderId, nextSelects = []) => {
                    const loader = document.getElementById(loaderId);
                    const select = document.getElementById(targetSelect);

                    nextSelects.forEach(id => {
                        const el = document.getElementById(id);
                        el.innerHTML = `<option value="">ابتدا انتخاب قبلی را انجام دهید...</option>`;
                        el.disabled = true;
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
                        .catch(() => select.innerHTML = `<option value="">خطا در ارتباط با سرور</option>`)
                        .finally(() => loader.classList.add('d-none'));
                };

                document.getElementById('city_id')?.addEventListener('change', e => {
                    const cityId = e.target.value;
                    if (!cityId) return;
                    fetchAndFill(`${apiBase}/organs?city_id=${cityId}`, 'organ_id', 'organ-loader', ['unit_id']);
                });

                document.getElementById('organ_id')?.addEventListener('change', e => {
                    const organId = e.target.value;
                    if (!organId) return;
                    fetchAndFill(`${apiBase}/units?organ_id=${organId}`, 'unit_id', 'unit-loader');
                });
            }
        </script>
    @endpush
</x-admin-layout>
