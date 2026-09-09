# Changelog

Seluruh perubahan penting pada proyek **UntungKlik** akan dicatat secara berkala dalam berkas ini.

Format berkas ini mengacu pada [Keep a Changelog](https://keepachangelog.com/id/1.1.0/), dan proyek ini mematuhi standar [Semantic Versioning](https://semver.org/lang/id/).

---

## [Unreleased]

### Ditambahkan
- Pengaturan identitas nota toko (nama toko, alamat, nomor telepon, catatan kaki nota, dan logo toko) dengan pratinjau struk kasir termal langsung.
- Ekspor laporan penjualan dan data produk ke format PDF dan Excel.
- Validasi form dan interaksi konfirmasi hapus/logout menggunakan SweetAlert2.

### Diperbaiki
- Standarisasi sistem warna UI/UX secara komprehensif ke 4 warna utama (`#002626`, `#0E4749`, `#95C623`, `#E55812`).
- Penyesuaian tata letak kartu ringkasan statistik keuangan dan kartu stok produk.
- Pembersihan seluruh kode warna *legacy* inline RGBA di seluruh template Blade.

---

## [1.0.0] - 2026-09-09

### Ditambahkan
- **Inisialisasi Proyek**: Kerangka aplikasi berbasis Laravel 13 dan PHP 8.3+.
- **Sistem Autentikasi & Otorisasi**:
  - Login cepat berbasis Username dan 4-digit PIN.
  - Pemulihan akun (Lupa PIN & Reset PIN) berbasis secure token.
  - Manajemen peran (*Role-Based Access Control*) untuk **Owner** dan **Karyawan**.
- **Kasir Penjualan (Point of Sale)**:
  - Antarmuka kasir cepat multi-item dengan pencarian produk instan & scanner barcode.
  - Kalkulasi diskon/voucher dan nominal kembalian otomatis.
  - Cetak struk nota belanja siap printer thermal (58mm dan 80mm).
- **Manajemen Produk & Inventaris**:
  - Master data katalog produk lengkap dengan SKU, HPP (harga beli), harga jual, dan persentase margin laba.
  - Pengelompokan kategori produk multi-level.
  - Kontrol stok otomatis, peringatan stok menipis (*low stock alert*), penyesuaian stok manual (*stock adjustment*), dan kartu histori mutasi stok (*stock movements*).
- **Arus Kas & Keuangan**:
  - Pencatatan kas masuk dan kas keluar dengan kategori transaksi dinamis.
  - Pencatatan modal usaha awal dan tambahan (*capital entries*).
  - Manajemen beban operasional usaha (*operational expenses*) seperti sewa, listrik, promosi, dan gaji.
- **Laporan & Analitik**:
  - Rekapitulasi laporan Laba/Rugi (*Profit and Loss*), Arus Kas (*Cash Flow*), dan ringkasan transaksi penjualan.
  - Penyaringan periode fleksibel (hari ini, minggu ini, bulan ini, tahun ini, dan kustom rentang tanggal).
  - Ekspor laporan keuangan ke format PDF (`barryvdh/laravel-dompdf`) dan Excel (`maatwebsite/excel`).
  - Dashboard analitik interaktif berbasis Chart.js.
- **Pengujian Otomatis**:
  - Suite pengujian lengkap menggunakan Pest PHP (42 pengujian fitur & unit dengan 226 assertions).
