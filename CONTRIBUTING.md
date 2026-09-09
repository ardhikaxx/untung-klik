# Panduan Kontribusi (Contributing)

Terima kasih telah meluangkan waktu dan tertarik untuk berkontribusi pada proyek **UntungKlik**. Panduan ini dibuat untuk memastikan proses kolaborasi tetap rapi, konsisten, dan berkualitas tinggi.

---

## Memulai Kontribusi

1. **Fork** repositori ini ke akun GitHub Anda.
2. **Clone** hasil fork ke komputer lokal:
   ```bash
   git clone https://github.com/<username-anda>/untung-klik.git
   cd untung-klik
   ```
3. Buat branch baru dari branch `main`:
   ```bash
   git checkout -b feat/nama-fitur-baru
   # atau
   git checkout -b fix/nama-perbaikan-bug
   ```
4. Lakukan perubahan kode dan pastikan fungsionalitas berjalan normal.
5. Commit perubahan Anda dengan pesan commit yang jelas dan terstruktur.
6. Push branch Anda ke repositori fork:
   ```bash
   git push origin feat/nama-fitur-baru
   ```
7. Buat **Pull Request (PR)** baru ke branch `main` repositori utama.

---

## Standar Kode (Code Style)

Proyek ini menggunakan standar konvensi ekosistem Laravel modern (PHP 8.3+) dan diformat menggunakan **Laravel Pint**:

- Gunakan **Laravel Pint** sebelum melakukan commit untuk menjaga konsistensi gaya penulisan:
  ```bash
  vendor/bin/pint --dirty --format agent
  ```
- Selalu gunakan kurung kurawal `{ ... }` untuk setiap blok kontrol kendali (`if`, `foreach`, `while`), bahkan jika hanya satu baris.
- Terapkan *type hints* dan *explicit return types* pada setiap method PHP (`public function store(Request $request): RedirectResponse`).
- Gunakan *PHP 8 constructor property promotion* pada class service / action.
- Tuliskan kode yang ekspresif, mudah dibaca, dan gunakan nama method/variabel yang deskriptif.

---

## Pengujian Otomatis (Testing)

Sebelum mengajukan Pull Request, pastikan seluruh rangkaian pengujian fitur dan unit lolos 100%:

```bash
# Menjalankan pengujian Pest
php artisan test --compact

# atau
vendor\bin\pest
```

Jika Anda menambahkan fitur baru atau memperbaiki bug, sertakan test kasus terkait di dalam direktori `tests/Feature/`.

---

## Konvensi Pesan Commit

Gunakan standar **Conventional Commits**:

- `feat:` penambahan fitur atau kemampuan baru.
- `fix:` perbaikan bug atau error.
- `refactor:` perubahan atau pembersihan struktur kode tanpa merubah fungsi/fitur.
- `style:` pembaruan tampilan Blade, CSS, atau format kode yang tidak mengubah alur logika.
- `docs:` perubahan dokumentasi (`README.md`, panduan instalasi, dsb).
- `test:` penambahan atau penyesuaian unit & feature tests.
- `chore:` pembaruan dependensi, konfigurasi build, atau berkas pendukung.

*Contoh*: `feat(pos): tambah pencarian barcode produk otomatis pada kasir`

---

## Alur Pull Request (PR)

1. Pastikan branch Anda selalu terbarui dengan branch `main` repositori upstream.
2. Perbarui berkas `CHANGELOG.md` pada bagian `[Unreleased]` untuk mencatat perubahan yang Anda lakukan.
3. Jelaskan secara ringkas perubahan apa saja yang dibuat pada deskripsi Pull Request.
4. Tunggu proses review dan respon umpan balik jika diperlukan perbaikan lanjutan.

---

## Kode Etik (Code of Conduct)

Harap saling menghormati, berkomunikasi secara profesional, dan terbuka dalam memberikan maupun menerima masukan. Perilaku yang merugikan atau tidak sopan tidak akan ditoleransi.
