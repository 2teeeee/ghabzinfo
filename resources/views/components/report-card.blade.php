@props(['title', 'value', 'unit' => '', 'icon' => '', 'class' => ''])

<div class="card shadow-sm border-0 mb-3 {{$class}}">
    <div class="card-body d-flex align-items-center">
        @if($icon)
            <div class="me-3 text-primary fs-3">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
        <div>
            <h6 class="text-muted mb-1">{{ $title }}</h6>
            <h4 class="mb-0">
                {{ is_numeric($value) ? number_format((float)$value) : $value }}
                @if($unit)
                    <small class="text-secondary">{{ $unit }}</small>
                @endif
            </h4>
        </div>
    </div>
</div>
