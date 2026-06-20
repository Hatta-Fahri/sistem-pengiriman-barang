<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case TERSEDIA = 'Tersedia';
    case TERJADWAL = 'Terjadwal';
    case SEDANG_DIGUNAKAN = 'Sedang Digunakan';
    case MAINTENANCE = 'Maintenance';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
