<div class="row">

    {{-- نام --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">نام</label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- نام کاربری --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">نام کاربری</label>
        <input type="text" name="username"
               class="form-control @error('username') is-invalid @enderror"
               value="{{ old('username', $user->username ?? '') }}" required>
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ایمیل --}}
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

    {{-- موبایل --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">موبایل</label>
        <input type="text" name="mobile"
               class="form-control @error('mobile') is-invalid @enderror"
               value="{{ old('mobile', $user->mobile ?? '') }}" required>
        @error('mobile')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- رمز عبور --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">رمز عبور</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
            {{ isset($user) ? '' : 'required' }}>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(isset($user))
            <small class="text-muted">در صورت تغییر رمز عبور مقدار جدید وارد کنید.</small>
        @endif
    </div>

    {{-- تکرار رمز عبور --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">تکرار رمز عبور</label>
        <input type="password" name="password_confirmation" class="form-control"
            {{ isset($user) ? '' : 'required' }}>
    </div>

    {{-- انتخاب شهر --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">شهر</label>
        <div class="input-group">
            <select id="city_id" name="city_id"
                    class="form-select @error('city_id') is-invalid @enderror">
                <option value="">انتخاب کنید...</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}"
                        {{ old('city_id', $user->city_id ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            <span class="input-group-text d-none" id="city-loader">
                <div class="spinner-border spinner-border-sm"></div>
            </span>
        </div>
        @error('city_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- انتخاب سازمان --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">سازمان</label>
        <div class="input-group">
            <select id="organ_id" name="organ_id"
                    class="form-select @error('organ_id') is-invalid @enderror">
                <option value="">ابتدا شهر را انتخاب کنید...</option>
                @if(old('organ_id', $user->organ_id ?? false))
                    <option value="{{ old('organ_id', $user->organ_id) }}" selected>
                        {{ $user->organ->name ?? '---' }}
                    </option>
                @endif
            </select>
            <span class="input-group-text d-none" id="organ-loader">
                <div class="spinner-border spinner-border-sm"></div>
            </span>
        </div>
        @error('organ_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- انتخاب واحد --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">واحد</label>
        <div class="input-group">
            <select id="unit_id" name="unit_id"
                    class="form-select @error('unit_id') is-invalid @enderror">
                <option value="">ابتدا سازمان را انتخاب کنید...</option>
                @if(old('unit_id', $user->unit_id ?? false))
                    <option value="{{ old('unit_id', $user->unit_id) }}" selected>
                        {{ $user->unit->name ?? '---' }}
                    </option>
                @endif
            </select>
            <span class="input-group-text d-none" id="unit-loader">
                <div class="spinner-border spinner-border-sm"></div>
            </span>
        </div>
        @error('unit_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- انتخاب مرکز --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">مرکز</label>
        <div class="input-group">
            <select id="center_id" name="center_id"
                    class="form-select @error('center_id') is-invalid @enderror">
                <option value="">ابتدا واحد را انتخاب کنید...</option>
                @if(old('center_id', $user->center_id ?? false))
                    <option value="{{ old('center_id', $user->center_id) }}" selected>
                        {{ $user->center->name ?? '---' }}
                    </option>
                @endif
            </select>
            <span class="input-group-text d-none" id="center-loader">
                <div class="spinner-border spinner-border-sm"></div>
            </span>
        </div>
        @error('center_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- نقش‌ها --}}
    <div class="mb-3 col-md-12">
        <label class="form-label">نقش‌ها</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach($roles as $role)
                @php
                    $colors = ['primary', 'success', 'warning', 'danger', 'info', 'dark'];
                    $color = $colors[$loop->index % count($colors)];
                    $checked = in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : []));
                @endphp
                <div class="card text-white bg-{{ $color }} p-2 rounded-2 shadow-sm" style="min-width:130px;">
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

    {{-- حد مجاز قبض --}}
    @if(auth()->user()->hasRole(['admin','manager']))
        <div class="mb-3 col-md-6">
            <label class="form-label">حداکثر تعداد قبض</label>
            <input type="number" name="bill_limit" min="1"
                   class="form-control @error('bill_limit') is-invalid @enderror"
                   value="{{ old('bill_limit', $user->bill_limit ?? 10) }}">
            @error('bill_limit')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-success">
            {{ isset($user) ? 'ویرایش کاربر' : 'ایجاد کاربر' }}
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiBase = '/admin/api';
            const fetchAndFill = (url, targetSelect, loaderId, nextSelects = []) => {
                const loader = document.getElementById(loaderId);
                const select = document.getElementById(targetSelect);

                nextSelects.forEach(id => {
                    const el = document.getElementById(id);
                    el.innerHTML = `<option value="">ابتدا انتخاب قبلی را انجام دهید...</option>`;
                    el.disabled = true;
                });

                loader.classList.remove('d-none');
                select.disabled = true;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            select.innerHTML = `<option value="">انتخاب کنید...</option>`;
                            data.data.forEach(item => {
                                select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                            });
                            select.disabled = false;
                        } else {
                            select.innerHTML = `<option value="">خطا در دریافت داده</option>`;
                        }
                    })
                    .catch(() => select.innerHTML = `<option value="">خطا در ارتباط با سرور</option>`)
                    .finally(() => loader.classList.add('d-none'));
            };

            document.getElementById('city_id')?.addEventListener('change', e => {
                const cityId = e.target.value;
                if (cityId)
                    fetchAndFill(`${apiBase}/organs?city_id=${cityId}`, 'organ_id', 'organ-loader', ['unit_id', 'center_id']);
            });

            document.getElementById('organ_id')?.addEventListener('change', e => {
                const organId = e.target.value;
                if (organId)
                    fetchAndFill(`${apiBase}/units?organ_id=${organId}`, 'unit_id', 'unit-loader', ['center_id']);
            });

            document.getElementById('unit_id')?.addEventListener('change', e => {
                const unitId = e.target.value;
                if (unitId)
                    fetchAndFill(`${apiBase}/centers?unit_id=${unitId}`, 'center_id', 'center-loader');
            });

            // نقش‌ها → فیلدهای required داینامیک
            const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
            const selects = {
                city: document.querySelector('#city_id'),
                organ: document.querySelector('#organ_id'),
                unit: document.querySelector('#unit_id'),
                center: document.querySelector('#center_id'),
            };

            function updateRequiredFields() {
                Object.values(selects).forEach(s => s?.removeAttribute('required'));

                const selectedRoles = Array.from(roleCheckboxes)
                    .filter(ch => ch.checked)
                    .map(ch => ch.nextElementSibling.textContent.trim().toLowerCase());

                if (selectedRoles.includes('city')) selects.city?.setAttribute('required', true);
                if (selectedRoles.includes('organ')) {
                    selects.city?.setAttribute('required', true);
                    selects.organ?.setAttribute('required', true);
                }
                if (selectedRoles.includes('unit')) {
                    selects.city?.setAttribute('required', true);
                    selects.organ?.setAttribute('required', true);
                    selects.unit?.setAttribute('required', true);
                }
                if (selectedRoles.includes('center')) {
                    selects.city?.setAttribute('required', true);
                    selects.organ?.setAttribute('required', true);
                    selects.unit?.setAttribute('required', true);
                    selects.center?.setAttribute('required', true);
                }
            }

            roleCheckboxes.forEach(ch => ch.addEventListener('change', updateRequiredFields));
            updateRequiredFields();
        });
    </script>
@endpush
