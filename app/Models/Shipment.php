<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'current_status' => ShipmentStatus::class,
    ];

    protected static function booted()
    {
        // 1. Saat pertama kali dibuat (Biasanya dari ShipmentController Admin)
        static::created(function ($shipment) {
            $shipment->trackings()->create([
                'status' => $shipment->current_status->value ?? $shipment->current_status,
                'notes' => 'Paket telah diterima di fasilitas KEN Logistics',
                'recorded_by' => Auth::id() ?? 1, // Default ke 1 jika via CLI/Seeder
            ]);
        });

        // 2. Saat status berubah (Diupdate Kurir atau Admin)
        static::updated(function ($shipment) {
            if ($shipment->isDirty('current_status')) {
                // Ambil nilai string murni dari Enum
                $statusVal = $shipment->current_status->value ?? $shipment->current_status;
                
                $notes = match($statusVal) {
                    'Dalam Perjalanan' => 'Paket sedang diberangkatkan dari fasilitas asal',
                    'Tiba di Tujuan' => 'Paket telah tiba di fasilitas tujuan',
                    'Dalam Pengantaran' => 'Kurir sedang mengantar paket ke alamat penerima',
                    'Penundaan Pengiriman' => 'Pengiriman tertunda atau dijadwalkan ulang',
                    'Gagal Dikirim' => 'Paket gagal dikirim',
                    'Diterima' => 'Paket berhasil diserahkan kepada penerima',
                    'Diproses' => 'Paket kembali diproses / menunggu jadwal (reset jadwal)',
                    default => 'Status paket diperbarui',
                };

                $shipment->trackings()->create([
                    'status' => $statusVal,
                    'notes' => $notes,
                    'recorded_by' => Auth::id() ?? 1,
                ]);
            }
        });
    }

    // =========================================================
    // RELASI
    // =========================================================

    public function manifest()
    {
        return $this->belongsTo(Manifest::class);
    }

    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    public function proofOfDelivery()
    {
        return $this->hasOne(ProofOfDelivery::class, 'shipment_id', 'id');
    }



    // =========================================================
    // SCOPE PENCARIAN
    // =========================================================

    public function scopePending($query)
    {
        // Menggunakan Enum DIPROSES untuk pencarian data yang siap dijadwalkan
        return $query->where('current_status', ShipmentStatus::DIPROSES)
                     ->whereNull('manifest_id');
    }
}
