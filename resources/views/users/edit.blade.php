<x-admin-layout title="ویرایش کاربر" header="ویرایش کاربر">
    <div class="container-fluid py-4">

        <x-admin-page-header
            title="ویرایش کاربر"
            icon="bi-person-gear"
            :back-url="route('admin.users.index')"
        />

        <div class="card border-0 shadow-lg rounded-0">
            <div class="card-body px-4 py-5">
                <form id="editUserForm" action="{{ route('admin.users.update', $user) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    @include('users._form', ['editMode' => true])
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
