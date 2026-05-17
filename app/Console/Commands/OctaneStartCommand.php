<?php

namespace App\Console\Commands;

use Laravel\Octane\Commands\StartCommand;

class OctaneStartCommand extends StartCommand
{
    /**
     * Returns the list of signals to subscribe.
     */
    public function getSubscribedSignals(): array
    {
        // Disable signal subscriptions on Windows to prevent pcntl_signal crashes
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return [];
        }

        return [SIGINT, SIGTERM, SIGHUP];
    }
}
