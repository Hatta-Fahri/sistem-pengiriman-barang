<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // 1. Manfaatkan trait SoftDeletes agar akun yang dihapus tidak benar-benar hilang dari database (bisa dipulihkan)
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 2. Definisikan kolom-kolom yang boleh diisi secara massal (Mass Assignment)
    //    Kolom 'role', 'courier_code', dan data profil kurir lainnya disertakan agar bisa diproses di Controller
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

        // --- Data Spesifik Kurir ---
        'courier_code', // Kode unik kurir yang digenerate otomatis (contoh: KRR001)
        'nik',
        'phone',
        'sim_number',
        'sim_type',
        'address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // 3. Sembunyikan kolom sensitif agar tidak ikut terekspos saat model di-serialize (misal: ke JSON API response)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // 4. Casting otomatis tipe data kolom agar nilai yang dibaca selalu dalam format yang tepat
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis di-hash saat disimpan
    ];

    // ====================================================================
    // RELASI DATABASE
    // ====================================================================

    // 5. Relasi ke Shipment: Satu kurir bisa memiliki banyak resi pengiriman yang pernah ia tangani
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'courier_id');
    }

    // 6. Relasi ke Manifest: Satu kurir bisa memiliki banyak riwayat jadwal pengantaran
    public function manifests()
    {
        return $this->hasMany(Manifest::class, 'courier_id');
    }

}
        