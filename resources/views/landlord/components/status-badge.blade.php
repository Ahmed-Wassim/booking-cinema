{{--
    Status Badge Component
    @param string $status  completed | pending | processing | cancelled | active | inactive
--}}
@php
$config = match(strtolower($status ?? 'pending')) {
    'completed' => ['class' => 'bg-success-subtle text-success', 'label' => 'Completed'],
    'active'    => ['class' => 'bg-success-subtle text-success', 'label' => 'Active'],
    'pending'   => ['class' => 'bg-warning-subtle text-warning', 'label' => 'Pending'],
    'processing'=> ['class' => 'bg-info-subtle text-info',    'label' => 'Processing'],
    'cancelled' => ['class' => 'bg-danger-subtle text-danger', 'label' => 'Cancelled'],
    'inactive'  => ['class' => 'bg-secondary-subtle text-secondary', 'label' => 'Inactive'],
    default     => ['class' => 'bg-secondary-subtle text-secondary', 'label' => ucfirst($status ?? 'Unknown')],
};
@endphp
<span class="badge rounded-pill {{ $config['class'] }}">{{ $config['label'] }}</span>
