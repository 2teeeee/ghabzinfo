<x-admin-layout title="افزودن کاربر جدید" header="افزودن کاربر جدید">

    <div class="row justify-content-center mx-0 py-5">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-3">
                    <h5 class="mb-0">ایجاد کاربر جدید</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @include('users._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
