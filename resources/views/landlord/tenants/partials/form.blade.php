{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $centralDomain = config('tenancy.central_domains')[0];
    $domainValue = isset($tenant) && $tenant->domains->count() > 0 ? $tenant->domains->first()->domain : '';
    $domainValue = str_replace('.' . $centralDomain, '', $domainValue);

    $activeSub = isset($tenant) ? $tenant->subscriptions->firstWhere('status', 'active') : null;
    $currentPlanId = $activeSub ? $activeSub->plan_id : null;
@endphp

<div class="row">
    <div class="col-md-12 mb-3">
        <label for="id" class="form-label">Tenant ID (Unique identifier)</label>
        <input type="text" name="id" id="id" class="form-control" value="{{ old('id', $tenant->id ?? '') }}"
            placeholder="e.g. acme-corp" required {{ isset($tenant) ? 'readonly' : '' }}>
        <div class="form-text">This is the unique identifier for the tenant (used for database name).</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="domain" class="form-label">Subdomain</label>
        <div class="input-group">
            <input type="text" class="form-control" id="domain" name="domain" value="{{ old('domain', $domainValue) }}"
                placeholder="e.g. acme" required {{ isset($tenant) ? 'readonly' : '' }}>
            <span class="input-group-text">.{{ $centralDomain }}</span>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="plan_id" class="form-label">Subscription Plan</label>
        <select class="form-select" id="plan_id" name="plan_id" required>
            <option value="" disabled {{ !$currentPlanId ? 'selected' : '' }}>Select a Plan</option>
            @foreach($plans ?? [] as $plan)
                <option value="{{ $plan->id }}" {{ old('plan_id', $currentPlanId) == $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }} - ${{ number_format($plan->price, 2) }}/{{ ucfirst($plan->billing_interval) }}
                </option>
            @endforeach
        </select>
    </div>
</div>