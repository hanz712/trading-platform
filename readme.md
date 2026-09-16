# Trading Learning Platform V2

Platform edukasi trading berbasis **PHP Native + MySQL**.

## Fitur V2
- Login / register dengan password hashing + session.
- Role `student` dan `admin`.
- Dashboard statistik jurnal + progress belajar.
- Learning roadmap berdasarkan level.
- Detail lesson + tandai materi selesai.
- Trading journal dengan timeframe, setup, entry, SL, TP, R:R, thesis, dan lesson learned.
- Quiz materi dasar.
- Backtest calculator: win rate dan expectancy.
- Admin dashboard untuk menambah level dan lesson.
- CSRF token untuk form POST.
- Semua typo `dasboard` sudah diperbaiki menjadi `dashboard`.

## Struktur
```text
trading-learning-platform-v2/
├── database/schema.sql
├── frontend/index.html
└── server/
    ├── index.php
    ├── config/database.php
    └── api/
        ├── auth/
        ├── admin/
        ├── dashboard.php
        ├── roadmap.php
        ├── lesson.php
        ├── journal.php
        ├── quiz.php
        └── backtest.php
```

## Instalasi
1. Upload folder project ke hosting/XAMPP/Laragon.
2. Buat/import database memakai `database/schema.sql`.
3. Sesuaikan host, database, username, dan password di `server/config/database.php`.
4. Buka `server/index.php`.

### Membuat akun admin
Registrasi publik selalu membuat role `student`. Untuk membuat admin, setelah registrasi ubah role akun secara manual di MySQL:

```sql
UPDATE users SET role='admin' WHERE email='EMAIL_KAMU';
```

Jangan membuka endpoint pembuatan admin ke publik.
