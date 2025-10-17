<x-admin-layout title="لیست واحدها" header="لیست واحدها">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="لیست واحدها"
            icon="bi-diagram-3"
            :create-url="route('admin.units.create')"
        />

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>نام واحد</th>
                            <th>سازمان</th>
                            <th>شهرستان</th>
                            <th>تعداد مراکز</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($units as $unit)
                            <tr class="text-center">
                                <td>{{ $loop->iteration + ($units->currentPage()-1) * $units->perPage() }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->organ->name ?? '-' }}</td>
                                <td>{{ $unit->organ->city->name ?? '-' }}</td>
                                <td>{{ $unit->units_count ?? $unit->centers()->count() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.units.edit', $unit->id) }}"
                                           class="btn btn-outline-warning btn-sm" title="ویرایش">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST"
                                              onsubmit="return confirm('آیا مطمئن هستید؟')">
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
                                    هیچ واحدی یافت نشد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $units->links() }}
        </div>
    </div>
</x-admin-layout>
