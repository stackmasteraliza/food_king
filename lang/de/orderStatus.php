<?php

use App\Enums\OrderStatus;

return [
    OrderStatus::PENDING          => 'Ausstehend',
    OrderStatus::ACCEPT           => 'Akzeptieren',
    OrderStatus::PREPARING        => 'In Bearbeitung',
    OrderStatus::PREPARED         => 'Vorbereitet',
    OrderStatus::OUT_FOR_DELIVERY => 'Zur Lieferung bereit',
    OrderStatus::DELIVERED        => 'Geliefert',
    OrderStatus::CANCELED         => 'Storniert',
    OrderStatus::REJECTED         => 'Abgelehnt',
    OrderStatus::RETURNED         => 'Zurückgegeben',


];
