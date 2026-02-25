{{--
    Activity Feed Item Component
    @param string $icon
    @param string $iconBg   Bootstrap text-bg-* class
    @param string $title
    @param string $desc
    @param string $time
    @param bool   $last
--}}
<div class="activity-item {{ isset($last) && $last ? 'last' : '' }}">
    <div class="activity-icon {{ $iconBg ?? 'text-bg-secondary' }}">
        <i class="bi {{ $icon ?? 'bi-circle-fill' }}"></i>
    </div>
    <div class="activity-body">
        <div class="activity-title fw-medium">{{ $title ?? '' }}</div>
        <div class="activity-desc text-muted small">{{ $desc ?? '' }}</div>
        <div class="activity-time text-muted" style="font-size:.75rem">{{ $time ?? '' }}</div>
    </div>
</div>
