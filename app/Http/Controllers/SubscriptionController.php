<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        $plans      = $this->subscriptionService->getActivePlans();
        $activeSub  = Auth::user()->activeSubscription()->with('plan')->first();

        return view('subscriptions.index', compact('plans', 'activeSub'));
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate(['plan_id' => ['required', 'exists:plans,id']]);

        $plan = Plan::findOrFail($request->plan_id);

        if (!$plan->is_active) {
            return back()->with('error', 'This plan is no longer available.');
        }

        $this->subscriptionService->subscribe(Auth::user(), $plan);

        return back()->with('success', "Subscribed to {$plan->name}! Your subscription is valid for one year.");
    }
}
