<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class DeactivateExpiredSubscriptions extends Command
{
    protected $signature   = 'subscriptions:deactivate-expired';
    protected $description = 'Deactivate all subscriptions whose end date has passed';

    public function handle(SubscriptionService $subscriptionService): int
    {
        $count = $subscriptionService->deactivateExpired();
        $this->info("Deactivated {$count} expired subscription(s).");
        return Command::SUCCESS;
    }
}
