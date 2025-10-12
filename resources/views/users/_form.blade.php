<div class="row">

    <div class="mb-3 col-md-6">
        <label class="form-label">نام</label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">نام کاربری</label>
        <input type="text" name="username"
               class="form-control @error('username') is-invalid @enderror"
               value="{{ old('username', $user->username ?? '') }}" required>
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">ایمیل</label>
        <input type="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">می‌تواند خالی باشد.</small>
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">موبایل</label>
        <input type="text" name="mobile"
               class="form-control @error('mobile') is-invalid @enderror"
               value="{{ old('mobile', $user->mobile ?? '') }}" required>
        @error('mobile')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">رمز عبور</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
            {{ isset($user) ? '' : 'required' }}>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(isset($user))
            <small class="text-muted">فقط در صورت تغییر رمز عبور، مقدار وارد کنید.</small>
        @endif
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">تکرار رمز عبور</label>
        <input type="password" name="password_confirmation" class="form-control"
            {{ isset($user) ? '' : 'required' }}>
    </div>

    {{-- نقش‌ها به صورت کارت‌های رنگی با چک‌باکس --}}
    <div class="mb-3 col-md-12">
        <label class="form-label">نقش‌ها</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach($roles as $role)
                @php
                    $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
                    $color = $colors[$loop->index % count($colors)];
                    $checked = isset($user) && $user->roles->contains($role->id);
                @endphp
                <div class="card text-white bg-{{ $color }} p-2 rounded-2 shadow-sm" style="min-width:120px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="roles[]"
                               value="{{ $role->id }}"
                               id="role-{{ $role->id }}"
                            {{ $checked ? 'checked' : '' }}>
                        <label class="form-check-label" for="role-{{ $role->id }}">
                            {{ $role->label ?? $role->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
        <div class="mb-3 col-md-6">
            <label class="form-label">حداکثر تعداد قبض</label>
            <input type="number" name="bill_limit" min="1"
                   class="form-control @error('bill_limit') is-invalid @enderror"
                   value="{{ old('bill_limit', $user->bill_limit ?? 10) }}">
            @error('max_bills')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">تعداد قبض‌هایی که این کاربر می‌تواند ثبت کند. کاربر معمولی به طور پیش‌فرض 10 قبض دارد.</small>
        </div>
    @endif

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">بازگشت</a>
        <button type="submit" class="btn btn-success">
            {{ isset($user) ? 'ویرایش کاربر' : 'ایجاد کاربر' }}
        </button>
    </div>
</div>
