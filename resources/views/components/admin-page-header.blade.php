{{-- resources/views/components/admin-page-header.blade.php --}}
<div class="d-flex flex-wrap justify-content-between align-items-center bg-white shadow-sm p-3 mb-4 rounded">
    <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
        @if($icon ?? false)
            <i class="bi {{ $icon }} fs-5 text-primary"></i>
        @endif
        <h4 class="mb-0">{{ $title }}</h4>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center">
        {{-- فرم جستجو --}}
        @isset($search)
            <form method="GET" class="d-flex me-2">
                <div class="input-group">
                    <input
                        type="text"
                        name="bill_id"
                        class="form-control form-control-sm shadow-sm"
                        placeholder="{{ $search['placeholder'] ?? 'جستجو...' }}"
                        value="{{ request($search['name'] ?? '') }}">
                    <button class="btn btn-primary btn-sm px-2" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        @endisset

        {{-- دکمه‌های اختیاری --}}
        @isset($backUrl)
            <a href="{{ $backUrl }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="bi bi-arrow-right-circle me-1"></i> بازگشت
            </a>
        @endisset

        @isset($createUrl)
            <a href="{{ $createUrl }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg me-1"></i> ایجاد جدید
            </a>
        @endisset
    </div>
</div>
