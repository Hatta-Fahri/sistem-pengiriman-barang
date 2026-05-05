# 📦 KEN Logistics - Sistem Manajemen Pengiriman Barang

KEN Logistics adalah sebuah sistem informasi berbasis web untuk mengelola dan melacak pengiriman barang (paket/kargo) secara *real-time*. Sistem ini memfasilitasi tiga aktor utama: **Admin**, **Kurir**, dan **Pelanggan (Publik)**.

## 🌟 Fitur Utama

### 1. 🔍 Pelacakan Resi (Public)
- Pelanggan dapat melacak posisi paket menggunakan Nomor Resi (misal: `KEN-20260417-ABCD`).
- Menampilkan *timeline* perjalanan paket secara dinamis.
- Menampilkan estimasi status (Diproses, Dalam Perjalanan, Tiba di Tujuan, Dalam Pengantaran, Diterima, Gagal/Tertunda).
- Menampilkan foto *Proof of Delivery* (POD) setelah paket berhasil diterima.

### 2. 👨‍💻 Panel Admin
- **Manajemen Resi (Shipment):** Pembuatan resi baru, pencetakan resi (label), dan pengeditan (selama belum dijadwalkan).
- **Manajemen Manifest:** Pengelompokan resi ke dalam satu jadwal perjalanan (*Manifest*) dan penugasan ke Kurir tertentu.
- **Laporan & Rekapitulasi:** Cetak laporan pengiriman dan memantau kinerja harian.

### 3. 🚚 Panel Kurir
- **Tugas Harian:** Melihat daftar paket yang harus diantar (*Manifest* aktif).
- **Update Status:** Kurir dapat memperbarui status paket secara *real-time* dari lapangan.
- **Upload POD (Proof of Delivery):** Mendukung pengambilan foto secara langsung (Base64/Kamera) sebagai bukti penerimaan.

---

## 🛠️ Teknologi yang Digunakan

*   **Framework Backend:** [Laravel](https://laravel.com) (PHP)
*   **Frontend/Styling:** [Tailwind CSS](https://tailwindcss.com/) & Blade Templating
*   **Icons & Animations:** [Lucide Icons](https://lucide.dev/) & [LottieFiles](https://lottiefiles.com/)
*   **Database:** MySQL / SQLite
*   **Cloud Storage:** [Cloudinary](https://cloudinary.com/) (Untuk penyimpanan foto Proof of Delivery agar server tidak cepat penuh)

---

## 🚀 Panduan Instalasi (Untuk Developer Baru)

Jika Anda melakukan *pull* atau *clone* repositori ini, ikuti langkah-langkah berikut untuk menjalankan aplikasi di lokal:

### 1. *Clone* Repositori
```bash
git clone <url-repo-anda>
cd sistem-pengiriman-barang
```

### 2. Instalasi Dependensi
Pastikan [Composer](https://getcomposer.org/) dan [Node.js](https://nodejs.org/) sudah terinstal.
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`.
```bash
cp .env.example .env
php artisan key:generate
```

**⚠️ PENTING (Cloudinary):**
Karena sistem ini menggunakan Cloudinary untuk penyimpanan foto POD, pastikan Anda menambahkan kredensial API Cloudinary Anda di file `.env`.
```env
CLOUDINARY_URL=cloudinary://<API_KEY>:<API_SECRET>@<CLOUD_NAME>
```

### 4. Setup Database
Pastikan pengaturan database di `.env` sudah sesuai (misalnya `DB_CONNECTION=sqlite` atau `mysql`). Jalankan migrasi dan *seeder* (jika ada):
```bash
php artisan migrate --seed
```

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi bisa diakses melalui browser di `http://localhost:8000`.

---

## 📂 Struktur Penting / Konsep Sistem

- **`Shipment` (Resi):** Entitas utama barang yang dikirim.
- **`Manifest` (Surat Jalan):** Wadah yang berisi kumpulan *Shipment* dan ditugaskan ke seorang Kurir.
- **`ShipmentTracking`:** Catatan (*log*) pergerakan paket. Otomatis bertambah saat status `Shipment` berubah.
- **`ProofOfDelivery`:** Bukti serah terima barang (nama penerima dan foto Cloudinary).

---
*Dibuat untuk mempermudah operasional pengiriman PT. Ken Ekspres Nusantara.*
