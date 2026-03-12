<?php

namespace App\Http\Controllers\Landlord;

use App\Domain\Landlord\DTO\TenantDTO;
use App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Landlord\Services\Interfaces\IPlanService;
use App\Domain\Landlord\Services\Interfaces\ITenantService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\StoreTenantRequest;
use App\Http\Requests\Landlord\UpdateTenantRequest;

class TenantController extends Controller
{
    public function __construct(
        protected ITenantService $tenantService,
        protected IPlanService $planService,
        protected IPaymentRepository $paymentRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = $this->tenantService->listAllTenants();

        return view('landlord.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = $this->planService->listAllPlans();

        return view('landlord.tenants.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenantRequest $request)
    {
        $this->tenantService->storeTenant((array) TenantDTO::fromRequest($request->validated()));

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenant = $this->tenantService->editTenant($id);
        $plans = $this->planService->listAllPlans();
        $payments = $this->paymentRepository->getByTenant($id);

        return view('landlord.tenants.edit', compact('tenant', 'plans', 'payments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, string $id)
    {
        $this->tenantService->updateTenant((array) TenantDTO::fromRequest($request->validated()), $id);

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->tenantService->deleteTenant($id);

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant deleted successfully');
    }
}
