<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Services\KitchenPrintService;
use Illuminate\Support\Facades\Log;

class PrintKitchenOrder
{
    public function handle(OrderConfirmed $event)
    {
        $printService = new KitchenPrintService();

        try {
            $printService->printOrder($event->order);
            Log::info("Kitchen print sent for order #{$event->order->order_serial_no}");
        } catch (\Exception $e) {
            Log::error("Kitchen print failed for order #{$event->order->order_serial_no}: " . $e->getMessage());
        }
    }
}
