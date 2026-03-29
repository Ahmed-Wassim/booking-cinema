<div class="dropdown me-3">
    <button class="topbar-btn d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; background: transparent;">
        <i class="bi bi-globe fs-5"></i>
        <span class="ms-1 d-none d-md-inline text-uppercase fw-semibold">{{ str_replace('_', '-', app()->getLocale()) }}</span>
        <i class="bi bi-chevron-down small ms-1"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow">
        <li>
            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('landlord.lang.switch', 'en') }}">
               English
            </a>
        </li>
        <li>
            <a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ route('landlord.lang.switch', 'ar') }}">
               العربية
            </a>
        </li>
        <li>
            <a class="dropdown-item {{ app()->getLocale() == 'fr' ? 'active' : '' }}" href="{{ route('landlord.lang.switch', 'fr') }}">
               Français
            </a>
        </li>
    </ul>
</div>
