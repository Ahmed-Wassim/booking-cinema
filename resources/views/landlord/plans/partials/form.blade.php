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

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Plan Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name ?? '') }}" required>
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Currency</label>
        <select name="currency" class="form-select" required id="currency-select"
            data-currencies="{{ json_encode(collect($currencies)->keyBy('code')->toArray()) }}">
            <option value="" disabled {{ !old('currency', $plan->currency ?? null) ? 'selected' : '' }}>Select Currency
            </option>
            @foreach($currencies as $currency)
                <option value="{{ $currency['code'] }}" title="{{ $currency['name'] }}" {{ old('currency', $plan->currency ?? '') === $currency['code'] ? 'selected' : '' }}>
                    {{ $currency['code'] }} {{ $currency['symbol'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Price</label>
        <div class="input-group">
            <span class="input-group-text" id="currency-symbol">$</span>
            <input type="number" step="0.01" min="0" name="price" class="form-control"
                value="{{ old('price', $plan->price ?? '0.00') }}" required>
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Billing Interval</label>
        <select name="billing_interval" class="form-select" required>
            <option value="monthly" {{ old('billing_interval', $plan->billing_interval ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ old('billing_interval', $plan->billing_interval ?? '') === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>

    <div class="col-md-12 mb-4">
        <label class="form-label">Description (Optional)</label>
        <textarea name="description" class="form-control"
            rows="3">{{ old('description', $plan->description ?? '') }}</textarea>
    </div>
</div>

<div class="border-top pt-3 mb-3">
    <h5 class="mb-3">Plan Features</h5>
    <div id="features-container">
        @php
            $features = old('features', isset($plan) ? $plan->features->toArray() : [
                ['feature_key' => 'max_cinemas', 'feature_value' => '1'],
                ['feature_key' => 'max_halls', 'feature_value' => '5'],
                ['feature_key' => 'max_bookings', 'feature_value' => '100']
            ]);

            // Available Enum Keys
            $availableKeys = \App\Domain\Landlord\Enums\FeatureKeyEnum::cases();
        @endphp

        @foreach($features as $index => $feature)
            <div class="row mb-2 feature-row">
                <div class="col-md-5">
                    <select name="features[{{ $index }}][feature_key]" class="form-select" required>
                        @foreach($availableKeys as $enumKey)
                            <option value="{{ $enumKey->value }}" {{ ($feature['feature_key'] ?? '') === $enumKey->value ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $enumKey->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="features[{{ $index }}][feature_value]" class="form-control"
                        placeholder="e.g. 5 or unlimited" value="{{ $feature['feature_value'] ?? '' }}" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-feature-btn"><i
                            class="bi bi-x-lg"></i></button>
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add-feature-btn">
        <i class="bi bi-plus-circle me-1"></i> Add Another Feature
    </button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let featureIndex = {{ count($features) }};
            const container = document.getElementById('features-container');
            const addBtn = document.getElementById('add-feature-btn');

            // Define dropdown options from enum array
            const optionsHtml = `
                        @foreach($availableKeys as $enumKey)
                            <option value="{{ $enumKey->value }}">{{ ucwords(str_replace('_', ' ', $enumKey->value)) }}</option>
                        @endforeach
                    `;

            addBtn.addEventListener('click', function () {
                const html = `
                            <div class="row mb-2 feature-row">
                                <div class="col-md-5">
                                    <select name="features[${featureIndex}][feature_key]" class="form-select" required>
                                        ${optionsHtml}
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="features[${featureIndex}][feature_value]" class="form-control" placeholder="e.g. 5 or unlimited" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-feature-btn"><i class="bi bi-x-lg"></i></button>
                                </div>
                            </div>
                        `;
                container.insertAdjacentHTML('beforeend', html);
                featureIndex++;
            });

            container.addEventListener('click', function (e) {
                if (e.target.closest('.remove-feature-btn')) {
                    const row = e.target.closest('.feature-row');
                    if (document.querySelectorAll('.feature-row').length > 1) {
                        row.remove();
                    } else {
                        alert('You must have at least one feature line.');
                    }
                }
            });

            // Update currency symbol when currency selection changes
            const currencySelect = document.getElementById('currency-select');
            const currencySymbol = document.getElementById('currency-symbol');

            if (currencySelect) {
                const currenciesData = JSON.parse(currencySelect.dataset.currencies || '{}');

                currencySelect.addEventListener('change', function () {
                    const currency = currenciesData[this.value];
                    const symbol = currency?.symbol || this.value;
                    currencySymbol.textContent = symbol;
                });
            }
        });
    </script>
@endpush