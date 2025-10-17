<x-admin-layout title="لیست سازمان‌ها" header="لیست سازمان‌ها">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="لیست سازمان‌ها"
            icon="bi-building"
            :create-url="route('admin.organs.create')"
        />

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>نام سازمان</th>
                        <th>شهرستان</th>
                        <th>تعداد واحدها</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($organs as $organ)
                        <tr class="text-center">
                            <td>{{ $loop->iteration + ($organs->currentPage()-1) * $organs->perPage() }}</td>
                            <td>{{ $organ->name }}</td>
                            <td>{{ $organ->city->name ?? '-' }}</td>
                            <td>{{ $organ->units_count ?? $organ->units()->count() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.organs.edit', $organ->id) }}" class="btn btn-sm btn-warning" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.organs.destroy', $organ->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('آیا مطمئنید می‌خواهید این سازمان را حذف کنید؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                هیچ سازمانی یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $organs->links() }}
        </div>
    </div>

</x-admin-layout>
