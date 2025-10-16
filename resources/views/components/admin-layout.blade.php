<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('fonts/style.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* --- Sidebar --- */
        .admin-sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            background-color: #343a40;
            color: #fff;
            padding-top: 1rem;
            transition: all 0.3s ease-in-out;
            z-index: 1050;
        }

        .admin-sidebar a {
            color: #adb5bd;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            transition: 0.2s;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background-color: #495057;
            color: #fff;
        }

        /* --- Content --- */
        .admin-content {
            margin-right: 250px;
            padding: 1rem;
            transition: margin-right 0.3s ease-in-out;
        }

        /* --- Header --- */
        .admin-header {
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* --- Menu toggle --- */
        .menu-toggle {
            display: none;
            font-size: 1.6rem;
            background: none;
            border: none;
            color: #343a40;
            cursor: pointer;
        }

        /* --- Overlay --- */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .admin-sidebar {
                right: -250px;
            }

            .admin-sidebar.open {
                right: 0;
            }

            .admin-content {
                margin-right: 0;
            }

            .menu-toggle {
                display: inline-block !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div class="admin-sidebar" id="sidebar">
    <div class="text-center py-2">
        <img src="{{ asset('img/logo-sums.png') }}" width="100" alt="sums" class="bg-white rounded p-2"/>
    </div>
    <h5 class="text-center text-light mb-3">سامانه مدیریت قبوض</h5>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-person"></i>
        کاربران
    </a>
    <a href="{{ route('admin.electricity_bills.index') }}" class="{{ request()->routeIs('admin.electricity_bills.*') ? 'active' : '' }}">
        <i class="bi bi-lightbulb"></i>
        قبض‌های برق
    </a>
    <a href="{{ route('admin.gas_bills.index') }}" class="{{ request()->routeIs('admin.gas_bills.*') ? 'active' : '' }}">
        <i class="bi bi-fire"></i>
        قبض‌های گاز
    </a>
    <a href="{{ route('admin.water_bills.index') }}" class="{{ request()->routeIs('admin.water_bills.*') ? 'active' : '' }}">
        <i class="bi bi-droplet"></i>
        قبض‌های آب
    </a>
    <hr class="border-secondary">
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-left"></i>
        خروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

<div class="sidebar-overlay" id="overlay"></div>

{{-- Main Content --}}
<div class="admin-content">
    {{-- Header --}}
    <div class="admin-header mb-3">
        <div class="d-flex align-items-center gap-2">
            <button class="menu-toggle" id="menuToggle">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="m-0">{{ $header }}</h5>
        </div>
        <div>
            <span class="text-muted small">{{ auth()->user()->name ?? 'مدیر' }}</span>
        </div>
    </div>

    {{-- Content --}}
    <div>
        {{ $slot }}
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('menuToggle');
    const overlay = document.getElementById('overlay');

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });
</script>

@stack('scripts')
</body>
</html>
