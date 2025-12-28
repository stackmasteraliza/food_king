<?php

use App\Enums\OrderStatus;

return [
        OrderStatus::PENDING          => 'Pending',
        OrderStatus::ACCEPT           => 'Accept',
        OrderStatus::PREPARING        => 'Preparing',
        OrderStatus::PREPARED         => 'Prepared',
        OrderStatus::OUT_FOR_DELIVERY => 'Out For Delivery',
        OrderStatus::DELIVERED        => 'Delivered',
        OrderStatus::CANCELED         => 'Canceled',
        OrderStatus::REJECTED         => 'Rejected',
        OrderStatus::RETURNED         => 'Returned',


];
