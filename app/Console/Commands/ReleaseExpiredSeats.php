<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ReleaseExpiredSeats extends Command
{
    protected $signature = 'seats:release-expired';

    protected $description = 'Release expired locked seats automatically';

    public function handle()
    {
        $service = app(BookingService::class);
        $count = $service->releaseAllExpiredLocks();

        $this->info("Expired seats released: {$count}");

        return 0;
    }
}
