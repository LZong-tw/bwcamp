@props([
    'applicant', 
    'fields'
])

<div class="applicant-info-grid">
    @foreach($fields as $key => $label)
        @php
            $lowKey = strtolower($key);
            $displayKey = $key . '_display';
            $dialKey = $key . '_dial';
            $textKey = $key . '_text';

            // 取得原始值與顯示值
            $rawValue = $applicant->$key;
            $displayValue = $applicant->$displayKey ?? $rawValue;
            $dialValue = $applicant->$dialKey ?? $rawValue;
            $textValue = $applicant->$textKey ?? $rawValue;

            // 檢查是否為空 (null, 空字串, 或只有空白)
            $isEmpty = empty(trim((string)$rawValue));
        @endphp
        <!-- <div class="row mb-2 border-bottom pb-1"> -->
        <div class="row mb-2 pb-1">
            {{-- Label --}}
            <div class="col-sm-4 font-weight-bold text-muted">
                {{ $label }}
            </div>

            {{-- Value --}}
            <div class="col-sm-8">
                @if($isEmpty)
                    <span class="text-muted font-italic">(未填寫)</span>
                
                @elseif(str_contains($lowKey, 'phone') || str_contains($lowKey, 'mobile'))
                    <a href="tel:{{ $dialValue }}" class="text-decoration-none">
                        <i class="fa fa-phone-alt fa-sm mr-1"></i>{{ $displayValue }}
                    </a>

                @elseif(str_contains($lowKey, 'email'))
                    <a href="mailto:{{ $rawValue }}" class="text-decoration-none">
                        <i class="fa fa-envelope fa-sm mr-1"></i>{{ $rawValue }}
                    </a>
                @elseif($key === 'is_attend' && $applicant->is_attend)
                    <span class="{{ $applicant->is_attend->colorClass() }}">
                        {{ $applicant->is_attend->label() }}
                    </span>
                @else
                    {{ $displayValue }}
                @endif
            </div>
        </div>
    @endforeach
</div>