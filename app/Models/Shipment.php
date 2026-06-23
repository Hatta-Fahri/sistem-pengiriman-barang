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

    // 1. Gunakan guarded agar semua kolom bisa diisi secara massal kecuali 'id' (Primary Key)
    protected $guarded = ['id'];

    // 2. Casting otomatis kolom 'current_status' ke tipe Enum ShipmentStatus
    //    Ini memastikan nilai status selalu valid dan konsisten di seluruh sistem
    protected $casts = [
        'current_status' => ShipmentStatus::class,
    ];

    protected static function booted()
    {
        // 3. Event Observer: Saat resi baru pertama kali dibuat, otomatis catat entri pertama log pelacakan
        static::created(function ($shipment) {
            $shipment->trackings()->create([
                'status'      => $shipment->current_status->value ?? $shipment->current_status,
                'notes'       => 'Gudang Pusat MEDAN',
                'recorded_by' => Auth::id() ?? 1, // Default ke 1 jika via CLI/Seeder
            ]);
        });

        // 4. Event Observer: Setiap kali status resi berubah, sistem otomatis mencatat satu entri baru ke log pelacakan
        static::updated(function ($shipment) {
            if ($shipment->isDirty('current_status')) {
                // 5. Ambil nilai string murni dari Enum untuk disimpan di database
                $statusVal = $shipment->current_status->value ?? $shipment->current_status;

                // 6. Tentukan pesan keterangan (catatan) yang relevan berdasarkan status baru yang berlaku
                $notes = match($statusVal) {
                    'Dalam Perjalanan'     => "Paket sedang dibawa menuju {$shipment->destination_city}",
                    'Tiba di Kota Tujuan'       => "Paket telah tiba di {$shipment->destination_city}",
                    'Dalam Pengantaran'    => 'Kurir sedang mengantar paket ke alamat penerima',
                    'Penundaan Pengiriman' => 'Pengiriman tertunda atau dijadwalkan ulang',
                    'Gagal Dikirim'        => 'Paket gagal dikirim',
                    'Dibatalkan'           => 'Resi dibatalkan permanen dan tidak akan dijadwalkan ulang',
                    'Diterima'             => 'Paket berhasil diserahkan kepada penerima',
                    'Terjadwal'            => 'Paket telah dijadwalkan dan menunggu keberangkatan',
                    'Diproses'             => 'Paket kembali diproses / menunggu jadwal (reset jadwal)',
                    default                => 'Status paket diperbarui',
                };

                // 7. Simpan entri baru log pelacakan ke database beserta siapa yang melakukan perubahan status
                $shipment->trackings()->create([
                    'status'      => $statusVal,
                    'notes'       => $notes,
                    'recorded_by' => Auth::id() ?? 1,
                ]);
            }
        });
    }

    // =========================================================
    // RELASI
    // =========================================================

    // 8. Relasi ke Manifest: Satu resi hanya bisa tergabung ke dalam satu jadwal pengantaran (manifest)
    public function manifest()
    {
        return $this->belongsTo(Manifest::class);
    }

    // 9. Relasi ke ShipmentTracking: Satu resi memiliki banyak catatan log riwayat pergerakan statusnya
    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    // 10. Relasi ke ProofOfDelivery: Satu resi hanya memiliki satu foto bukti serah terima (POD)
    public function proofOfDelivery()
    {
        return $this->hasOne(ProofOfDelivery::class, 'shipment_id', 'id');
    }



    // =========================================================
    // SCOPE PENCARIAN
    // =========================================================

    // 11. Scope 'pending': Filter cepat untuk menampilkan resi yang menunggu dijadwalkan ke manifest
    //     (Status DIPROSES dan belum tergabung ke manifest manapun)
    public function scopePending($query)
    {
        return $query->where('current_status', ShipmentStatus::DIPROSES)
                     ->whereNull('manifest_id');
    }
}
