<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShowtimeSeat;
use Carbon\Carbon;

class ReleaseExpiredSeats extends Command
{
    protected $signature = 'seats:release-expired';

    protected $description = 'Release expired locked seats';

    public function handle()
    {
        $expiredSeats = ShowtimeSeat::where('status', 'locked')
            ->whereNotNull('locked_at')
            ->where('locked_at', '<=', Carbon::now()->subMinutes(5))
            ->get();

        foreach ($expiredSeats as $seat) {
            $seat->update([
                'status' => 'available',
                'locked_at' => null
            ]);
        }

        $this->info('Expired seats released: ' . $expiredSeats->count());
    }
}
