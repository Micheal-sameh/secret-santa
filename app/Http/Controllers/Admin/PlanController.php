<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        $plans = $this->subscriptionService->getAllPlans();
        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                            => ['required', 'string', 'max:100'],
            'description'                     => ['nullable', 'string', 'max:500'],
            'price'                           => ['required', 'numeric', 'min:0'],
            'online_game_participant_limit'   => ['required', 'integer', 'min:1'],
            'inperson_game_participant_limit' => ['required', 'integer', 'min:1'],
            'is_active'                       => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $this->subscriptionService->createPlan($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $data = $request->validate([
            'name'                            => ['required', 'string', 'max:100'],
            'description'                     => ['nullable', 'string', 'max:500'],
            'price'                           => ['required', 'numeric', 'min:0'],
            'online_game_participant_limit'   => ['required', 'integer', 'min:1'],
            'inperson_game_participant_limit' => ['required', 'integer', 'min:1'],
            'is_active'                       => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $this->subscriptionService->updatePlan($plan, $data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $this->subscriptionService->deletePlan($plan);
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }
}
