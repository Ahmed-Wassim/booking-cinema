{{--
    Stat Card Component
    @param string $title
    @param string $value
    @param string $change
    @param string $trend     'up' | 'down'
    @param string $icon      Bootstrap icon class e.g. 'bi-currency-dollar'
    @param string $color     Bootstrap color: primary, success, warning, info, danger
    @param string $subtitle
--}}
<div class="col-sm-6 col-xl-3">
    <div class="card stat-card">
        <div class="card-body">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon bg-{{ $color ?? 'primary' }}-subtle text-{{ $color ?? 'primary' }}">
                    <i class="bi {{ $icon ?? 'bi-bar-chart-fill' }}"></i>
                </div>
                <span class="badge {{ ($trend ?? 'up') === 'up' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill">
                    <i class="bi bi-arrow-{{ ($trend ?? 'up') === 'up' ? 'up' : 'down' }}-right me-1"></i>
                    {{ $change ?? '0%' }}
                </span>
            </div>
            <div class="stat-value">{{ $value ?? '0' }}</div>
            <div class="stat-label text-muted">{{ $title ?? 'Metric' }}</div>
            <div class="stat-sub text-muted small mt-1">{{ $subtitle ?? '' }}</div>
        </div>
    </div>
</div>
