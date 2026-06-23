<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case DIPROSES = 'Diproses';
    case TERJADWAL = 'Terjadwal';
    case DALAM_PERJALANAN = 'Dalam Perjalanan';
    case TIBA_DI_TUJUAN = 'Tiba di Kota Tujuan';
    case DALAM_PENGANTARAN = 'Dalam Pengantaran';
    case DITERIMA = 'Diterima';
    case PENUNDAAN = 'Penundaan Pengiriman';
    case DIBATALKAN = 'Dibatalkan';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
