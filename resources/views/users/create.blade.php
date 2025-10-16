<x-admin-layout title="ایجاد کاربر جدید" header="ایجاد کاربر جدید">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ایجاد کاربر جدید"
            icon="bi-person-fill"
            :back-url="route('admin.users.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">

            <div class="card-body px-4 py-5">
                <form id="createUserForm" action="{{ route('admin.users.store') }}" class="needs-validation" novalidate method="POST">
                    @csrf
                    @include('users._form')
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
