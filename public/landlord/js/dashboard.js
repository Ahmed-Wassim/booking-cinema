/**
 * AdminKit Dashboard — dashboard.js
 * Handles: sidebar toggle, dark mode, mobile overlay, chart filters
 */

(function () {
    'use strict';

    /* ---- Elements ---- */
    const sidebar       = document.getElementById('sidebar');
    const mainWrapper   = document.getElementById('mainWrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose  = document.getElementById('sidebarClose');
    const overlay       = document.getElementById('sidebarOverlay');
    const themeToggle   = document.getElementById('themeToggle');
    const themeIcon     = document.getElementById('themeIcon');

    /* ============================================================
       1. DARK MODE
       ============================================================ */
    const savedTheme = localStorage.getItem('adminkit-theme') || 'light';
    applyTheme(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(next);
            localStorage.setItem('adminkit-theme', next);
        });
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
    }

    /* ============================================================
       2. SIDEBAR TOGGLE (desktop collapse)
       ============================================================ */
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth >= 992) {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('adminkit-sidebar', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'open');
            } else {
                openMobileSidebar();
            }
        });
    }

    // Restore desktop collapse state
    if (window.innerWidth >= 992 && localStorage.getItem('adminkit-sidebar') === 'collapsed') {
        document.body.classList.add('sidebar-collapsed');
    }

    /* ============================================================
       3. MOBILE SIDEBAR
       ============================================================ */
    function openMobileSidebar() {
        sidebar && sidebar.classList.add('show');
        overlay && overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        sidebar && sidebar.classList.remove('show');
        overlay && overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    sidebarClose && sidebarClose.addEventListener('click', closeMobileSidebar);
    overlay && overlay.addEventListener('click', closeMobileSidebar);

    /* ============================================================
       4. GLOBAL SEARCH (placeholder — wire up to your backend)
       ============================================================ */
    const globalSearch = document.getElementById('globalSearch');
    if (globalSearch) {
        globalSearch.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const q = globalSearch.value.trim();
                if (q) {
                    // Replace with your search route:
                    // window.location.href = `/search?q=${encodeURIComponent(q)}`;
                    console.log('Search:', q);
                }
            }
        });
    }

    /* ============================================================
       5. CHART FILTER BUTTONS
       ============================================================ */
    document.querySelectorAll('.chart-filter').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.d-flex').querySelectorAll('.chart-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Fire a custom event so specific pages can react:
            document.dispatchEvent(new CustomEvent('chartFilterChange', {
                detail: { period: this.dataset.period }
            }));
        });
    });

    /* ============================================================
       6. AUTO-DISMISS ALERTS
       ============================================================ */
    document.querySelectorAll('.alert.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert && bsAlert.close();
        }, 5000);
    });

    /* ============================================================
       7. ACTIVE NAV LINK HIGHLIGHTING (fallback)
       ============================================================ */
    document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
        if (link.href && link.href === window.location.href) {
            link.classList.add('active');
        }
    });

})();
