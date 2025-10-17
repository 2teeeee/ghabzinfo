<x-admin-layout title="لیست مراکز" header="لیست مراکز">
    <div class="container-fluid py-4">
        <x-admin-page-header
            title="لیست مراکز"
            icon="bi-buildings"
            :back-url="route('admin.index')"
            :create-url="route('admin.centers.create')"
        />

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>نام مرکز</th>
                            <th>واحد</th>
                            <th>سازمان</th>
                            <th>شهرستان</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($centers as $center)
                            <tr class="text-center">
                                <td>{{ $loop->iteration + ($centers->currentPage()-1)*$centers->perPage() }}</td>
                                <td>{{ $center->name }}</td>
                                <td>{{ $center->unit->name ?? '-' }}</td>
                                <td>{{ $center->unit->organ->name ?? '-' }}</td>
                                <td>{{ $center->unit->organ->city->name ?? '-' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.centers.edit', $center->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.centers.destroy', $center->id) }}" method="POST"
                                              onsubmit="return confirm('آیا مطمئن هستید؟')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    <p class="m-0">هیچ مرکزی یافت نشد.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $centers->links() }}
        </div>
    </div>
</x-admin-layout>
