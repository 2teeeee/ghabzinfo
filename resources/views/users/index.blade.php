<x-admin-layout title="لیست کاربران" header="لیست کاربران">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title=" لیست کاربران"
            icon="bi-people-fill"
            :back-url="route('admin.index')"
            :create-url="route('admin.users.create')"
            :search="[
                'name' => 'search',
                'placeholder' => 'جستجو بر اساس نام یا موبایل'
            ]"
        />

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Users Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>شماره موبایل</th>
                            <th>نقش‌ها</th>
                            <th class="text-center">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm btn-outline-warning me-1" title="ویرایش">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if(auth()->user()->hasRole('admin'))
                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('آیا از حذف کاربر اطمینان دارید؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-person-x fs-4 d-block mb-2"></i>
                                    هیچ کاربری یافت نشد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $users->withQueryString()->links() }}
        </div>

    </div>
</x-admin-layout>
