# Kebijakan Keamanan (Security Policy)

Kami memprioritaskan keamanan sistem pada proyek **UntungKlik**. Terima kasih telah membantu menjaga aplikasi dan para penggunanya tetap aman.

---

## Versi yang Didukung

Tabel berikut menunjukkan versi yang saat ini menerima pembaruan keamanan:

| Versi | Didukung |
| :--- | :--- |
| **1.x** | :white_check_mark: |
| < 1.0 | :x: |

---

## Melaporkan Kerentanan Keamanan

**Mohon jangan melaporkan celah atau kerentanan keamanan melalui GitHub Issues publik.**

Jika Anda menemukan kerentanan keamanan pada sistem ini, harap laporkan secara privat melalui email kepada:

```
Nama : Yanuar Ardhika Rahmadhani Ubaidillah
Email: ardhikayanuar58@gmail.com
```

Kami berkomitmen untuk:
1. Mengonfirmasi penerimaan laporan Anda dalam waktu maksimal **48 jam**.
2. Menganalisis tingkat keparahan celah keamanan dan dampaknya pada sistem.
3. Menyiapkan perbaikan (*patch*) sesegera mungkin.
4. Memberikan pembaruan status perbaikan secara berkala kepada pelapor.

---

## Kebijakan Pengungkapan (Disclosure Policy)

Setelah laporan diterima:
1. Kami akan mereproduksi masalah dan memastikan versi yang terdampak.
2. Melakukan audit pada modul terkait untuk memastikan tidak ada celah serupa.
3. Merilis pembaruan keamanan ke branch utama.
4. Mempublikasikan catatan keamanan setelah patch resmi tersedia.

---

## Praktik Keamanan Terbaik untuk Pengguna

- **Jangan Pernah Mengunggah Berkas `.env`**: Selalu pastikan berkas `.env` masuk ke dalam `.gitignore` dan jangan pernah menyertakan kredensial database produksi ke dalam repositori publik.
- **Ganti PIN Default**: Segera ganti PIN default (`2222`) setelah melakukan instalasi pertama kali di lingkungan produksi.
- **Pembaruan Dependensi**: Perbarui dependensi secara berkala menggunakan `composer update` untuk menambal kerentanan pada paket pihak ketiga.
- **Izin Berkas (File Permissions)**: Pastikan folder `storage` dan `bootstrap/cache` memiliki izin akses tulis yang aman dan tidak dapat dieksekusi langsung oleh publik secara sembarangan.
