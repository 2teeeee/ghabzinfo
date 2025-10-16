<x-blank-layout>
    <div class="row justify-content-center mx-0 my-5">
        <div class="col-md-4 border rounded-2 shadow-sm px-0 bg-white">
            <div class="text-center py-2">
                <img src="{{asset('img/logo-sums.png')}}" width="150" alt="sums"/>
            </div>
            <h5 class="bg-light text-center p-3 rounded-top-2 fw-bold border-bottom">
                ورود به سامانه
            </h5>

            <form method="POST" action="{{ route('login') }}" class="p-3">
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label">{{ __('نام کاربری') }}</label>
                    <div class="input-group">
                        <span class="input-group-text" id="username-l">
                            <i class="bi bi-person"></i>
                        </span>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="نام کاربری خود را وارد کنید"
                            required
                            autofocus
                        >
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('رمز عبور') }}</label>
                    <div class="input-group">
                        <span class="input-group-text" id="password-l">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="رمز عبور خود را وارد کنید"
                            required
                        >
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="form-check mb-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="remember_me"
                        name="remember"
                        value="1"
                    >
                    <label class="form-check-label" for="remember_me">
                        مرا به خاطر بسپار
                    </label>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('register') }}" class="text-decoration-none small text-primary">
                        حساب ندارم، ثبت‌نام می‌کنم
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-box-arrow-in-right me-1"></i> ورود
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-blank-layout>
