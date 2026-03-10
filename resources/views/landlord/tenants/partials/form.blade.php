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
        <label class="form-label">ID (Subdomain prefix or unique identifier)</label>
        <input type="text" name="id" class="form-control" value="{{ old('id', $tenant->id ?? '') }}" required {{ isset($tenant) ? 'readonly' : '' }}>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Domain</label>
        <input type="text" name="domain" class="form-control"
            value="{{ old('domain', $tenant->domains[0]->domain ?? '') }}" required>
    </div>
</div>