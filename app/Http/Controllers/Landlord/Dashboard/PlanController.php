<?php

namespace App\Http\Controllers\Landlord\Dashboard;

use App\Domain\Landlord\Dashboard\Web\Plan\DTO\PlanDTO;
use App\Domain\Landlord\Dashboard\Web\Plan\Services\Interfaces\IPlanService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\StorePlanRequest;
use App\Http\Requests\Landlord\UpdatePlanRequest;

class PlanController extends Controller
{
    public function __construct(
        protected IPlanService $planService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = $this->planService->listAllPlans();

        return view('landlord.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('landlord.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        $this->planService->storePlan((array) PlanDTO::fromRequest($request->validated()));

        return redirect()->route('landlord.plans.index')->with('success', 'Plan created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plan = $this->planService->editPlan($id);

        return view('landlord.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, string $id)
    {
        $this->planService->updatePlan((array) PlanDTO::fromRequest($request->validated()), $id);

        return redirect()->route('landlord.plans.index')->with('success', 'Plan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->planService->deletePlan($id);

        return redirect()->route('landlord.plans.index')->with('success', 'Plan deleted successfully');
    }
}
