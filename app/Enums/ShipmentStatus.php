<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case DIPROSES = 'Diproses';
    case DALAM_PERJALANAN = 'Dalam Perjalanan';
    case TIBA_DI_TUJUAN = 'Tiba di Tujuan';
    case DALAM_PENGANTARAN = 'Dalam Pengantaran';
    case DITERIMA = 'Diterima';
    case PENUNDAAN = 'Penundaan Pengiriman';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
