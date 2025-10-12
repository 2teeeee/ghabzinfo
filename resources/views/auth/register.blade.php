<x-main-layout>
    <div class="row justify-content-center mx-0 my-5 py-5">
        <div class="col-md-5 border rounded-2 shadow-sm px-0 bg-white">
            <h5 class="bg-light text-center p-3 rounded-top-2 fw-bold border-bottom">
                ثبت‌نام در سامانه
            </h5>

            <form method="POST" action="{{ route('register') }}" class="p-4">
                @csrf

                {{-- نام --}}
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('نام و نام خانوادگی') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="مثلاً علی رضایی">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- نام کاربری (اختیاری) --}}
                <div class="mb-3">
                    <label for="username" class="form-label">{{ __('نام کاربری (اختیاری)') }}</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                           class="form-control @error('username') is-invalid @enderror"
                           placeholder="مثلاً ali_rezaei">
                    @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ایمیل (اختیاری) --}}
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('ایمیل (اختیاری)') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="example@email.com">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- موبایل --}}
                <div class="mb-3">
                    <label for="mobile" class="form-label">{{ __('شماره موبایل') }}</label>
                    <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}"
                           class="form-control @error('mobile') is-invalid @enderror"
                           placeholder="مثلاً 09121234567">
                    @error('mobile')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- رمز عبور --}}
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('رمز عبور') }}</label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="حداقل ۶ کاراکتر">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- تکرار رمز عبور --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('تکرار رمز عبور') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control"
                           placeholder="رمز عبور را دوباره وارد کنید">
                </div>

                {{-- دکمه‌ها --}}
                <div class="d-flex align-items-center justify-content-between mt-4">
                    <a href="{{ route('login') }}"
                       class="text-decoration-none text-primary small">
                        قبلاً ثبت‌نام کرده‌ام
                    </a>

                    <button type="submit" class="btn btn-success px-4">
                        ثبت‌نام
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-main-layout>
