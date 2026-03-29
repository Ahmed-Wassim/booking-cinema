@php
    use App\Http\Controllers\Landlord\LocaleController;
    $locales  = LocaleController::locales();
    $current  = collect($locales)->firstWhere('active', true);
    $isRtl    = $current['rtl'] ?? false;
    $langEmoji = ['en' => '🇬🇧', 'ar' => '🇸🇦', 'fr' => '🇫🇷'];
@endphp

<div class="dropdown lang-switcher me-2">
    <button
        class="lang-switcher-btn"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        aria-label="{{ __('landlord.Language') }}"
        id="langDropdown">
        <span class="lang-flag">{{ $langEmoji[$current['code']] ?? '🌐' }}</span>
        <span class="lang-code">{{ strtoupper($current['code']) }}</span>
        @if($isRtl)
            <span class="rtl-badge">RTL</span>
        @endif
        <i class="bi bi-chevron-down lang-chevron"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end lang-dropdown shadow" aria-labelledby="langDropdown">
        <li class="lang-dropdown-header">
            <i class="bi bi-translate me-1"></i>{{ __('landlord.Select Language') }}
        </li>
        @foreach ($locales as $locale)
            <li>
                <a href="{{ $locale['url'] }}"
                   class="lang-option {{ $locale['active'] ? 'active' : '' }}"
                   dir="{{ $locale['rtl'] ? 'rtl' : 'ltr' }}">
                    <span class="lang-option-flag">{{ $langEmoji[$locale['code']] ?? '🌐' }}</span>
                    <span class="lang-option-text">
                        <span class="lang-option-native">{{ $locale['native'] }}</span>
                        <span class="lang-option-name">{{ $locale['name'] }}</span>
                    </span>
                    @if($locale['rtl'])
                        <span class="lang-option-dir-badge">RTL</span>
                    @endif
                    @if($locale['active'])
                        <i class="bi bi-check2 lang-option-check ms-auto"></i>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>
