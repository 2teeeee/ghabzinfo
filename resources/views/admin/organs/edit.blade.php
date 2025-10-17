<x-admin-layout title="ویرایش سازمان" header="ویرایش سازمان">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ویرایش سازمان"
            icon="bi-building"
            :back-url="route('admin.organs.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">
            <div class="card-body px-4 py-5">
                <form id="editOrganForm" action="{{ route('admin.organs.update', $organ->id) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                        @selected(old('city_id', $organ->city_id) == $city->id)>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- نام سازمان --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">نام سازمان</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   value="{{ old('name', $organ->name) }}"
                                   placeholder="نام سازمان را وارد کنید" required>
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-warning btn-lg px-5">
                            <i class="bi bi-pencil-square me-2"></i>
                            بروزرسانی سازمان
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
