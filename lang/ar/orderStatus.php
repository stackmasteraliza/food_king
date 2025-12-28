<?php

use App\Enums\OrderStatus;

return [
    OrderStatus::PENDING          => 'قيد الانتظار',
    OrderStatus::ACCEPT           => 'تم القبول',
    OrderStatus::PREPARING        => 'قيد التنفيذ',
    OrderStatus::PREPARED         => 'مستعد',
    OrderStatus::OUT_FOR_DELIVERY => 'في الطريق للتسليم',
    OrderStatus::DELIVERED        => 'تم التسليم',
    OrderStatus::CANCELED         => 'تم الإلغاء',
    OrderStatus::REJECTED         => 'تم الرفض',
    OrderStatus::RETURNED         => 'تم الإرجاع',


];
