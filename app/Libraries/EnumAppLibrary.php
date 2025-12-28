<?php 

namespace App\Libraries;


use App\Enums\Ask;
use App\Enums\Status;
use App\Enums\ItemType;


class EnumAppLibrary {
    
    public static function itemType($itemType): int
    {
        $itemType = strtolower(trim($itemType));
        if ($itemType === 'veg') {
            return ItemType::VEG;
        } elseif ($itemType === 'non veg' || $itemType === 'non-veg') {
            return ItemType::NON_VEG;
        }

        return ItemType::VEG;
    }

    public static function itemFeature($featureType): int
    {
        $featureType = strtolower(trim($featureType));
        if ($featureType === 'yes') {
            return Ask::YES;
        } elseif ($featureType === 'no') {
            return Ask::NO;
        }
        return Ask::NO;
    }

    public static function itemStatus($status): int
    {
        $status = strtolower(trim($status));
        if ($status === 'active') {
            return Status::ACTIVE;
        } elseif ($status === 'inactive') {
            return Status::INACTIVE;
        }
        return Status::INACTIVE;
    }
}