<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    protected $signature = 'cancel:expired-orders';

    protected $description = 'Cancel expired pending orders automatically';

    public function handle()
    {
        $service = app(BookingService::class);
        $count = $service->cancelExpiredOrders();

        $this->info('Expired orders canceled: '.$count);

        return 0;
    }
}
