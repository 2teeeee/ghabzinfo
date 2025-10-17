<x-admin-layout title="ویرایش شهر" header="ویرایش شهر">
    <div class="container-fluid py-4">

        {{-- هدر صفحه --}}
        <x-admin-page-header
            title="ویرایش شهر"
            icon="bi-building"
            :back-url="route('admin.cities.index')"
        />

        {{-- فرم ویرایش شهر --}}
        <div class="card border-0 shadow-lg rounded-0 mt-3">
            <div class="card-body px-4 py-5">
                <form action="{{ route('admin.cities.update', $city->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">نام شهر</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="مثلاً تهران"
                               value="{{ old('name', $city->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left me-1"></i> بازگشت
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i> بروزرسانی شهر
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('styles')
        <style>
            .card {
                border-radius: 1rem;
            }
        </style>
    @endpush>
</x-admin-layout>
