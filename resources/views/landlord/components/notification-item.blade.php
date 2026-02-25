{{--
    Notification Item Component
    @param string $icon
    @param string $iconBg
    @param string $title
    @param string $time
--}}
<div class="notification-item">
    <div class="notif-icon {{ $iconBg ?? 'bg-secondary' }} text-white">
        <i class="bi {{ $icon ?? 'bi-bell-fill' }}"></i>
    </div>
    <div class="notif-body">
        <div class="notif-title">{{ $title ?? '' }}</div>
        <div class="notif-time text-muted">{{ $time ?? '' }}</div>
    </div>
</div>
