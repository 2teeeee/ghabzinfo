<!doctype html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'سامانه استعلام قبض' }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

        <link href="{{asset('fonts/style.css')}}" rel="stylesheet">

        @stack('styles')
    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">سامانه قبض</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                        aria-controls="mainNav" aria-expanded="false" aria-label="تغییر ناوبری">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('electricity.index') }}">قبض های برق</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('gas.index') }}">قبض های گاز</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('water.index') }}">قبض های آب</a></li>
                    </ul>

                    <ul class="navbar-nav ms-auto d-none">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="">ورود</a></li>
                            <li class="nav-item"><a class="nav-link" href="">ثبت‌نام</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('electricity.index') }}">قبض‌های من</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="">
                                            @csrf
                                            <button type="submit" class="dropdown-item">خروج</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                    <ul class="navbar-nav nav-left py-1">
                        @if(!Auth::check())
                            <li class="nav-item px-2 border-start text-sm text-center d-block th-1">
                                <a class="nav-link" href="{{route('register')}}">
                                    <i class="bi bi-person-add icon-size-2x"></i>
                                    <div>ثبت نام</div>
                                </a>
                            </li>
                            <li class="nav-item px-2 border-start text-sm text-center d-block th-1">
                                <a class="nav-link" href="{{route('login')}}">
                                    <i class="bi bi-box-arrow-in-left icon-size-2x"></i>
                                    <div>ورود</div>
                                </a>
                            </li>

                        @else
                            <li class="nav-item px-2 border-start text-sm text-center d-block th-1 border-left-light-gray dropdown">
                                <button type="button"
                                        class="border-0 bg-transparent shadow-none text-sm text-darkgray nav-link"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person icon-size-2x"></i>
                                    <div>
                                    <span class="dropdown-toggle">
                                        {{ Auth::user()->name }}
                                    </div>
                                </button>
                                <ul class="dropdown-menu text-sm text-decoration-none pb-1">
                                    @if(Auth::user()->hasRole(['admin','manager']))
                                        <li>
                                            <a class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex" href="{{route('admin.index')}}">
                                                <i class="bi bi-bag-check me-2"></i>
                                                <span>پنل مدیریت</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <a href="{{route('logout')}}" class="text-dark text-decoration-none px-2 pb-1 align-self-center d-flex"
                                               onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                <i class="bi bi-box-arrow-right me-2"></i>
                                                <span>{{ __('logout') }}</span>
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">

                {{-- پیام موفقیت --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>
                    </div>
                @endif

                {{-- خطاها --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        @stack('scripts')

    </body>
</html>
