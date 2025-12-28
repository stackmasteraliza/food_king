<?php

namespace App\Enums;

interface OrderStatus
{
    const PENDING          = 1;
    const ACCEPT           = 4;
    const PREPARING        = 7;
    const PREPARED         = 8;
    const OUT_FOR_DELIVERY = 10;
    const DELIVERED        = 13;
    const CANCELED         = 16;
    const REJECTED         = 19;
    const RETURNED         = 22;
}
