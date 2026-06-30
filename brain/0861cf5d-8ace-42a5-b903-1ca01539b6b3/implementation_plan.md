# Implementasi Input Divisi Sekaligus dengan CO dan Anggota

Karena proses ini akan mengubah cara pembuatan divisi secara signifikan dan membuat form menjadi lebih kompleks, saya telah menyusun rencana implementasinya.

## Proposed Changes

### 1. Perubahan Tampilan (UI) Modal Tambah Divisi
Modal "Tambah Divisi" akan dibuat lebih dinamis menggunakan Alpine.js dengan struktur berikut:
- **Informasi Divisi:** Nama Divisi & Deskripsi.
- **Koordinator (CO) Divisi (Opsional):** 
  - Pilihan: `Kosongkan`, `Pilih Akun Terdaftar`, atau `Buat Akun Baru`.
  - Jika pilih Terdaftar: Muncul *dropdown* pilihan *user*.
  - Jika pilih Baru: Muncul input Nama & Email.
- **Anggota Divisi (Opsional):**
  - Terdapat tombol `+ Tambah Anggota Lainnya` yang bisa diklik berkali-kali.
  - Setiap baris anggota bisa dipilih apakah menggunakan `Akun Terdaftar` atau `Buat Akun Baru`.

### 2. Perubahan Logika (Backend) di Controller
Fungsi `storeDivision` di `KetupelDashboardController` akan diperbarui untuk memproses data ganda sekaligus secara aman (menggunakan *Database Transaction*):
- Membuat data Divisi baru.
- Jika form CO diisi: Mendaftarkan CO ke tabel kepanitiaan (`EventCommittee`).
- Jika form Anggota diisi: Melakukan iterasi (perulangan) pada semua data anggota yang dikirim, dan mendaftarkannya satu per satu ke divisi tersebut.

## Open Questions

> [!IMPORTANT]
> **Penting untuk Dikonfirmasi:**
> 1. Apakah Anda setuju dengan desain form di atas (di mana CO dan Anggota bersifat opsional saat pembuatan divisi, tapi *bisa* langsung diisi jika mau)?
> 2. Apakah fitur "Buat Akun Baru" (menginput nama dan email langsung) tetap perlu disediakan di dalam form penambahan massal ini, atau cukup membatasi ke pilihan "Akun yang sudah terdaftar" saja agar form tidak terlalu panjang? (Saya merekomendasikan tetap ada keduanya demi fleksibilitas).

Mohon konfirmasi atau berikan tanggapan Anda agar saya bisa segera mengimplementasikan kode ini.
