<?php

use App\Enums\OrderType;

return [
    OrderType::DELIVERY      => 'توصيل',
    OrderType::TAKEAWAY      => 'الاستلام',
    OrderType::POS           => 'POS',
    OrderType::DINING_TABLE  => 'طاولة الطعام',
    OrderType::Reservations  => 'حجوزات',
];
