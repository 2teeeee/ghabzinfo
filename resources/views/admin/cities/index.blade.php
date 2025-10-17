<x-admin-layout title="لیست شهرها" header="لیست شهرها">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="لیست شهرها"
            icon="bi-building"
            :back-url="route('admin.index')"
            :create-url="route('admin.cities.create')"
            :search="[
                'name' => 'name',
                'placeholder' => 'جستجو بر اساس نام شهر'
            ]"
        />

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>نام شهر</th>
                            <th>تعداد سازمان‌ها</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($cities as $city)
                            <tr class="text-center">
                                <td>{{ $loop->iteration + ($cities->currentPage()-1) * $cities->perPage() }}</td>
                                <td>{{ $city->name }}</td>
                                <td>{{ $city->organs_count ?? $city->organs()->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.cities.edit', $city->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="ویرایش">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST"
                                              onsubmit="return confirm('آیا مطمئنید که می‌خواهید این شهر را حذف کنید؟')"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    <p class="m-0">هیچ شهری یافت نشد.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- صفحه‌بندی --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $cities->withQueryString()->links() }}
        </div>
    </div>

    @push('styles')
        <style>
            .table th, .table td {
                vertical-align: middle !important;
            }
            .table-hover tbody tr:hover {
                background-color: #f1f3f5;
            }
            .card {
                border-radius: 1rem;
            }
        </style>
    @endpush
</x-admin-layout>
