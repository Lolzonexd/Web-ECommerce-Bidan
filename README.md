# 🩺✨ E-Commerce Praktik Bidan

Website Proyek UAS Kelompok – Praktik Pemrograman berbasis Web 2

Repo ini berisi source code untuk proyek UAS mata kuliah pemrograman web, yaitu **Website E-Commerce Praktik Bidan**. Website ini dirancang untuk membantu pasien melakukan pemesanan layanan kebidanan secara online, serta mempermudah pengelolaan data oleh bidan.

---

## 👥 Anggota Kelompok

| Nama                          | NIM / Keterangan         |
| ----------------------------- | ------------------------ |
| **A. Hanif Nursyabana**       | 062430701367 / Developer |
| **Raden Fadlurahman Said F.** | 0624307013 / Developer   |

---

## 🎯 Tujuan Proyek

- Membuat platform yang mempermudah pasien dalam mengakses layanan praktik bidan.
- Menyediakan sistem pemesanan online (booking).
- Menyediakan halaman admin untuk mengelola layanan, jadwal, pasien, dan transaksi.
- Menerapkan konsep dasar e-commerce pada domain kesehatan.

---

## 🛠 Teknologi yang Digunakan

- **Frontend:** HTML, CSS, JavaScript
- **Framework:** Bootstrap / Tailwind
- **Backend:** PHP
- **Database:** MySQL
- **Version Control:** Git & GitHub
- **Tools Pendukung:** XAMPP

---

## 📂 Struktur Proyek

```bash
/project-root
├── public/ # file yang diakses langsung user (frontend)
│ ├── index.php # halaman utama
│ ├── login.php # halaman login
│ ├── register.php # halaman register
│ ├── booking.php # halaman pemesanan layanan
│ └── services.php # daftar layanan
│
├── admin/ # halaman backend admin/bidan
│ ├── index.php # dashboard admin
│ ├── layanan/ # CRUD layanan
│ ├── pasien/ # CRUD pasien
│ ├── booking/ # kelola pemesanan
│ └── auth/ # login admin, logout, dsb
│
├── assets/ # file statis
│ ├── css/
│ ├── js/
│ └── images/
│
├── config/
│ ├── database.php # koneksi database
│ └── app.php # konfigurasi global
│
├── includes/ # komponen reusable
│ ├── header.php
│ ├── footer.php
│ └── navbar.php
│
├── helpers/ # fungsi tambahan (utils)
│ └── auth.php # pengecekan login, cookie remember me
│
├── sql/
│ └── database.sql # file struktur database
│
└── README.md
```

---

## 🚀 Fitur Utama

### 🛒 Untuk Pengguna / Pasien

- Melihat daftar layanan praktik bidan
- Melakukan booking layanan
- Registrasi & login
- Sistem "Remember Me" menggunakan cookie
- Notifikasi status pemesanan

### 🔧 Untuk Admin / Bidan

- Kelola data layanan
- Kelola jadwal praktik
- Kelola data pasien & booking
- Manajemen akun
- Dashboard statistik singkat

---

## 📸 Preview (Opsional)

_(Tambahkan screenshot di sini nanti)_

---

## 🧑💻 Cara Menjalankan Proyek

1. Clone repository:
    ```bash
    git clone https://github.com/username/nama-repo.git
    ```
2. Pindahkan folder ke direktori **htdocs** (untuk XAMPP).
3. Import file SQL ke MySQL via phpMyAdmin.
4. Jalankan Apache & MySQL.
5. Buka di browser:
   [http://localhost/nama-repo](http://localhost/Web-ECommerce-Bidan)

---

## 🤝 Kontribusi

Pull request dan issue sangat terbuka bagi anggota tim.
Pastikan setiap update dikirim melalui branch masing-masing.

---

## 📜 Lisensi

Proyek ini dibuat untuk keperluan akademik (UAS).
Tidak diperkenankan digunakan untuk tujuan komersial tanpa izin.

---

## ⭐ Kredit

Dikembangkan oleh **Kelompok UAS Praktik Bidan**:
**A. Hanif Nursyabana** & **Raden Fadlurahman Said F.**
