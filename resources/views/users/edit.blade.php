<x-admin-layout title="ویرایش کاربر" header="ویرایش کاربر">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ویرایش کاربر"
            icon="bi-person-fill"
            :back-url="route('admin.users.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">

            <div class="card-body px-4 py-5">
                <form id="updateUserForm" action="{{ route('admin.users.update', $user) }}" class="needs-validation" novalidate method="POST">
                    @csrf
                    @method('PUT')
                    @include('users._form')
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
