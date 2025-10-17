<x-admin-layout title="ثبت واحد جدید" header="ثبت واحد جدید">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ثبت واحد جدید"
            icon="bi-diagram-3"
            :back-url="route('admin.units.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">
            <div class="card-body px-4 py-5">
                <form action="{{ route('admin.units.store') }}" method="POST">
                    @csrf

                    @php
                        $role = auth()->user()->roles()->pluck('name')->first();
                        $isCity = $role === 'city';
                        $isOrgan = $role === 'organ';
                    @endphp

                    <div class="row g-4">
                        {{-- شهرستان --}}
                        <div class="col-md-6">
                            <label for="city_id" class="form-label fw-bold">شهرستان</label>
                            <select id="city_id" name="city_id" class="form-select" {{ $isCity || $isOrgan ? 'disabled' : '' }} required>
                                <option value="">انتخاب شهرستان...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- سازمان --}}
                        <div class="col-md-6 position-relative">
                            <label for="organ_id" class="form-label fw-bold">سازمان</label>
                            <div id="organ-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                            <select id="organ_id" name="organ_id" class="form-select" {{ $isOrgan ? 'disabled' : '' }} required>
                                @if($isCity || $isOrgan)
                                    <option value="{{ auth()->user()->organ_id ?? '' }}">{{ auth()->user()->organ->name ?? '---' }}</option>
                                @else
                                    <option value="">ابتدا شهرستان را انتخاب کنید...</option>
                                @endif
                            </select>
                        </div>

                        {{-- نام واحد --}}
                        <div class="col-12">
                            <label for="name" class="form-label fw-bold">نام واحد</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="مثلاً واحد فناوری اطلاعات" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-check2 me-2"></i>
                            ثبت واحد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const apiBase = '/admin/api';
            const role = "{{ $role }}";

            if(role !== 'organ') {
                const fetchAndFill = (url, targetSelect, loaderId) => {
                    const loader = document.getElementById(loaderId);
                    const select = document.getElementById(targetSelect);

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
                    if(cityId) fetchAndFill(`${apiBase}/organs?city_id=${cityId}`, 'organ_id', 'organ-loader');
                });
            }
        </script>
    @endpush
</x-admin-layout>
