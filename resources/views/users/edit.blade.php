<x-admin-layout title="ویرایش کاربر" header="ویرایش کاربر">
    <div class="row justify-content-center mx-0 py-5">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-3">
                    <h5 class="mb-0">ویرایش کاربر</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        @include('users._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
