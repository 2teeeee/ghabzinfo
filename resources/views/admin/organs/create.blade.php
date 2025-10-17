<x-admin-layout title="ایجاد سازمان جدید" header="ایجاد سازمان جدید">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ایجاد سازمان جدید"
            icon="bi-building"
            :back-url="route('admin.organs.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">
            <div class="card-body px-4 py-5">
                <form id="createOrganForm" action="{{ route('admin.organs.store') }}" method="POST">
                    @csrf

                    @php
                        $role = auth()->user()->roles()->pluck('name')->first();
                        $isCity = $role === 'city';
                    @endphp

                    <div class="row g-4">
                        {{-- شهرستان --}}
                        <div class="col-md-6 position-relative">
                            <label for="city_id" class="form-label fw-bold">شهرستان</label>
                            <div id="city-loader" class="spinner-border spinner-border-sm text-primary position-absolute end-0 top-50 me-3 d-none"></div>
                            <select id="city_id" name="city_id" class="form-select" required {{ $isCity ? 'disabled' : '' }}>
                                <option value="">انتخاب شهرستان...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                        @selected(old('city_id', auth()->user()->city_id ?? null) == $city->id)>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- نام سازمان --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">نام سازمان</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="نام سازمان را وارد کنید" required>
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-plus-circle me-2"></i>
                            ایجاد سازمان
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
