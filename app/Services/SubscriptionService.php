<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionService
{
    public function subscriptionsEnabled(): bool
    {
        return (bool) Setting::get('subscriptions_enabled', '1');
    }

    public function onlineGameFreeLimit(): int
    {
        return (int) Setting::get('online_game_free_limit', 10);
    }

    public function inPersonGameFreeLimit(): int
    {
        return (int) Setting::get('inperson_game_free_limit', 15);
    }

    public function inPersonLoginRequiredAfter(): int
    {
        return (int) Setting::get('inperson_login_required_after', 5);
    }

    /**
     * Check if a user can host an online game with the given participant count.
     * Returns true if allowed, false if subscription is required.
     */
    public function userCanHostOnlineGame(User $user, int $participantCount): bool
    {
        if (!$this->subscriptionsEnabled()) {
            return true;
        }
        if ($participantCount <= $this->onlineGameFreeLimit()) {
            return true;
        }
        return $user->hasActiveSubscription();
    }

    /**
     * Check if someone can create an in-person game with the given participant count.
     * Returns true if allowed.
     */
    public function canCreateInPersonGame(?User $user, int $participantCount): bool
    {
        if (!$this->subscriptionsEnabled()) {
            return true;
        }
        if ($participantCount <= $this->inPersonGameFreeLimit()) {
            return true;
        }
        return $user !== null && $user->hasActiveSubscription();
    }

    public function subscribe(User $user, Plan $plan): Subscription
    {
        // Cancel any existing active subscription first
        $user->subscriptions()->where('is_active', true)->update(['is_active' => false]);

        return Subscription::create([
            'user_id'    => $user->id,
            'plan_id'    => $plan->id,
            'starts_at'  => now(),
            'ends_at'    => now()->addYear(),
            'is_active'  => true,
        ]);
    }

    public function deactivateExpired(): int
    {
        return Subscription::where('is_active', true)
            ->where('ends_at', '<', now())
            ->update(['is_active' => false]);
    }

    public function getActivePlans()
    {
        return Plan::where('is_active', true)->get();
    }

    public function getAllPlans()
    {
        return Plan::latest()->get();
    }

    public function createPlan(array $data): Plan
    {
        return Plan::create($data);
    }

    public function updatePlan(Plan $plan, array $data): Plan
    {
        $plan->update($data);
        return $plan->fresh();
    }

    public function deletePlan(Plan $plan): void
    {
        $plan->delete();
    }
}
