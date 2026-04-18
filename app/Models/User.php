<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

        // --- Data Spesifik Kurir ---
        'courier_code', // ID Kurir Otomatis (KRR001)
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
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ====================================================================
    // RELASI DATABASE
    // ====================================================================

    /**
     * Relasi: Paket apa saja yang sedang/pernah dibawa kurir ini?
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'courier_id');
    }

    public function manifests()
    {
        return $this->hasMany(Manifest::class, 'courier_id');
    }

}
        