<?php

use App\Enums\OrderStatus;

return [

    OrderStatus::PENDING          => 'মুলতুবি',
    OrderStatus::ACCEPT           => 'গ্রহণ করুন',
    OrderStatus::PREPARING        => 'প্রস্তুত করছে',
    OrderStatus::PREPARED         => 'প্রস্তুত',
    OrderStatus::OUT_FOR_DELIVERY => 'ডেলিভারির জন্য বাইরে',
    OrderStatus::DELIVERED        => 'ডেলিভার',
    OrderStatus::CANCELED         => 'বাতিল',
    OrderStatus::REJECTED         => 'প্রত্যাখ্যাত',
    OrderStatus::RETURNED         => 'ফেরত',


];
