```markdown
# 📚 Dokumentasi API Survey Mahasiswa

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange?style=flat-square&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

API RESTful untuk sistem survey mahasiswa dengan Laravel 12, dilengkapi dengan autentikasi, manajemen survey, dan analytics dasar.

---

## 📋 Daftar Isi

-   [Tentang Proyek](#tentang-proyek)
-   [Fitur Utama](#fitur-utama)
-   [Arsitektur Sistem](#arsitektur-sistem)
-   [Instalasi](#instalasi)
-   [Konfigurasi](#konfigurasi)
-   [Database Schema](#database-schema)
-   [API Endpoints](#api-endpoints)
-   [Authentication](#authentication)
-   [Cara Penggunaan](#cara-penggunaan)
-   [Testing](#testing)
-   [Deployment](#deployment)
-   [Troubleshooting](#troubleshooting)
-   [Contributing](#contributing)
-   [License](#license)

---

## 🎯 Tentang Proyek

**API Survey Mahasiswa** adalah sistem backend yang dibangun dengan Laravel 12 untuk mengelola survey online di lingkungan kampus. Sistem ini memungkinkan:

-   **Admin** untuk membuat dan mengelola survey dengan pertanyaan multiple choice
-   **Mahasiswa** untuk mengisi survey yang tersedia

### Teknologi yang Digunakan

-   **Backend Framework:** Laravel 12
-   **Authentication:** Laravel Sanctum (Token-based)
-   **Database:** MySQL 8.0+
-   **PHP Version:** 8.2+
-   **Architecture:** RESTful API
-   **Testing:** Postman Collection

---

## ✨ Fitur Utama

### 🔐 Authentication & Authorization

-   ✅ Register mahasiswa dengan password default (tanggal lahir jika tidak disertakan)
-   ✅ Login dengan NIM atau email dan password
-   ✅ Token-based authentication (Laravel Sanctum)
-   ✅ Role-based access control (Admin & Mahasiswa)
-   ✅ Logout dengan token invalidation

### 📝 Survey Management (Admin)

-   ✅ CRUD Survey (Create, Read, Update, Delete)
-   ✅ Set periode aktif survey
-   ✅ Toggle status active/inactive
-   ✅ Cascade delete (survey → questions → options → responses)

### ❓ Question Management (Admin)

-   ✅ Tambah pertanyaan ke survey
-   ✅ Multiple choice dengan nilai/bobot per pilihan
-   ✅ Support tipe: multiple_choice, rating, text
-   ✅ Update pertanyaan dan options
-   ✅ Delete pertanyaan

### 👨‍🎓 Mahasiswa Features

-   ✅ Lihat survey yang tersedia (belum diisi)
-   ✅ Filter otomatis: active & dalam periode
-   ✅ Submit response dengan validasi
-   ✅ Satu mahasiswa hanya bisa isi survey satu kali
-   ✅ Lihat riwayat response

### 📊 Analytics (Admin)

-   ✅ Overall statistics (total surveys, responses, users)
-   ✅ Analytics per survey (rata-rata, distribusi jawaban)
-   ✅ Persentase pilihan per pertanyaan
-   ✅ Top surveys berdasarkan jumlah response

---

## 🏗️ Arsitektur Sistem

### Flow Diagram
```

┌─────────────┐
│ Client │ (Web/Mobile App)
│ (Frontend) │
└──────┬──────┘
│ HTTP/JSON
│
┌──────▼──────────────────────────────────┐
│ Laravel API (Backend) │
│ ┌────────────────────────────────┐ │
│ │ Routes (api.php) │ │
│ └─────────┬──────────────────────┘ │
│ │ │
│ ┌─────────▼──────────────────────┐ │
│ │ Middleware │ │
│ │ - auth:sanctum │ │
│ │ - IsAdmin │ │
│ └─────────┬──────────────────────┘ │
│ │ │
│ ┌─────────▼──────────────────────┐ │
│ │ Controllers │ │
│ │ - AuthController │ │
│ │ - SurveyController │ │
│ │ - QuestionController │ │
│ │ - ResponseController │ │
│ │ - AnalyticsController │ │
│ └─────────┬──────────────────────┘ │
│ │ │
│ ┌─────────▼──────────────────────┐ │
│ │ Models (Eloquent ORM) │ │
│ │ - User │ │
│ │ - Survey │ │
│ │ - Question │ │
│ │ - QuestionOption │ │
│ │ - Response │ │
│ │ - ResponseAnswer │ │
│ └─────────┬──────────────────────┘ │
│ │ │
└────────────┼─────────────────────────────┘
│
┌─────▼─────┐
│ MySQL │
│ Database │
└───────────┘

```

### Database Relationships

```

users (Admin & Mahasiswa)
↓ (1:N)
responses
↓ (1:N)
response_answers
↓ (N:1)
questions ← (1:N) surveys
↓ (1:N)
question_options

````

---

## 🚀 Instalasi

### Prerequisites

Pastikan sudah terinstall:

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Git

### Langkah Instalasi

#### 1. Clone Repository

```bash
git clone https://github.com/username/survey-api.git
cd survey-api
````

#### 2. Install Dependencies

```bash
composer install
```

#### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=survey_mahasiswa
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 5. Buat Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE survey_mahasiswa;
EXIT;
```

#### 6. Jalankan Migration & Seeder

```bash
php artisan migrate:fresh --seed
```

#### 7. Install Sanctum

```bash
php artisan install:api
```

#### 8. Jalankan Server

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

---

## ⚙️ Konfigurasi

### CORS Configuration

Edit `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:5173',
],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

### Sanctum Configuration

Edit `config/sanctum.php`:

```php
'expiration' => null, // Token tidak expire
// atau
'expiration' => 525600, // 1 tahun
```

### Environment Variables

```env
APP_NAME="Survey Mahasiswa API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173

SESSION_DRIVER=database
```

---

## 🗄️ Database Schema

### Users Table

| Column        | Type   | Description                    |
| ------------- | ------ | ------------------------------ |
| id            | BIGINT | Primary key                    |
| nim           | STRING | Nomor Induk Mahasiswa (unique) |
| nama          | STRING | Nama lengkap                   |
| email         | STRING | Email (unique)                 |
| tanggal_lahir | DATE   | Tanggal lahir                  |
| jurusan       | STRING | Jurusan (nullable)             |
| password      | STRING | Hashed password                |
| role          | ENUM   | admin, mahasiswa               |

**Default Password:** Tanggal lahir dengan format `ddmmyyyy` (contoh: 18102000 untuk 18 Oktober 2000)

### Surveys Table

| Column          | Type    | Description          |
| --------------- | ------- | -------------------- |
| id              | BIGINT  | Primary key          |
| judul           | STRING  | Judul survey         |
| deskripsi       | TEXT    | Deskripsi (nullable) |
| tanggal_mulai   | DATE    | Tanggal mulai        |
| tanggal_selesai | DATE    | Tanggal selesai      |
| is_active       | BOOLEAN | Status active        |

### Questions Table

| Column     | Type   | Description                   |
| ---------- | ------ | ----------------------------- |
| id         | BIGINT | Primary key                   |
| survey_id  | BIGINT | Foreign key → surveys         |
| pertanyaan | TEXT   | Teks pertanyaan               |
| tipe       | ENUM   | multiple_choice, rating, text |
| urutan     | INT    | Urutan tampilan               |

### Question Options Table

| Column       | Type   | Description             |
| ------------ | ------ | ----------------------- |
| id           | BIGINT | Primary key             |
| question_id  | BIGINT | Foreign key → questions |
| teks_pilihan | STRING | Teks pilihan            |
| nilai        | INT    | Nilai/bobot pilihan     |

### Responses Table

| Column       | Type      | Description           |
| ------------ | --------- | --------------------- |
| id           | BIGINT    | Primary key           |
| survey_id    | BIGINT    | Foreign key → surveys |
| user_id      | BIGINT    | Foreign key → users   |
| submitted_at | TIMESTAMP | Waktu submit          |

**Unique Constraint:** `(survey_id, user_id)` - Satu user hanya bisa submit satu kali per survey

### Response Answers Table

| Column             | Type   | Description                     |
| ------------------ | ------ | ------------------------------- |
| id                 | BIGINT | Primary key                     |
| response_id        | BIGINT | Foreign key → responses         |
| question_id        | BIGINT | Foreign key → questions         |
| question_option_id | BIGINT | Foreign key → question_options  |
| jawaban_teks       | TEXT   | Jawaban teks (nullable)         |
| nilai              | INT    | Nilai dari pilihan yang dipilih |

---

## 🌐 API Endpoints

Base URL: `http://localhost:8000/api`

### Authentication Endpoints

| Method | Endpoint  | Auth | Description        |
| ------ | --------- | ---- | ------------------ |
| POST   | /register | ❌   | Register mahasiswa |
| POST   | /login    | ❌   | Login              |
| POST   | /logout   | ✅   | Logout             |
| GET    | /me       | ✅   | Get user profile   |

### Mahasiswa Endpoints

| Method | Endpoint                        | Auth | Description           |
| ------ | ------------------------------- | ---- | --------------------- |
| GET    | /mahasiswa/surveys/available    | ✅   | Get available surveys |
| POST   | /mahasiswa/surveys/{id}/respond | ✅   | Submit response       |
| GET    | /mahasiswa/my-responses         | ✅   | Get response history  |

### Admin - Survey Management

| Method | Endpoint            | Auth  | Description       |
| ------ | ------------------- | ----- | ----------------- |
| GET    | /admin/surveys      | Admin | Get all surveys   |
| POST   | /admin/surveys      | Admin | Create survey     |
| GET    | /admin/surveys/{id} | Admin | Get survey detail |
| PUT    | /admin/surveys/{id} | Admin | Update survey     |
| DELETE | /admin/surveys/{id} | Admin | Delete survey     |

### Admin - Question Management

| Method | Endpoint                      | Auth  | Description     |
| ------ | ----------------------------- | ----- | --------------- |
| POST   | /admin/surveys/{id}/questions | Admin | Add question    |
| PUT    | /admin/questions/{id}         | Admin | Update question |
| DELETE | /admin/questions/{id}         | Admin | Delete question |

### Admin - Analytics

| Method | Endpoint                      | Auth  | Description          |
| ------ | ----------------------------- | ----- | -------------------- |
| GET    | /admin/analytics/surveys/{id} | Admin | Get survey analytics |
| GET    | /admin/analytics/overview     | Admin | Get overall stats    |

---

## 🔐 Authentication

### Register (POST /register)

**Request Body:**

```json
{
    "nim": "2021001",
    "nama": "Budi Santoso",
    "email": "budi@mahasiswa.com",
    "tanggal_lahir": "2000-10-18",
    "jurusan": "Teknik Informatika"
}
```

**Response (201 Created):**

```json
{
    "message": "Registrasi berhasil",
    "user": {
        "id": 1,
        "nim": "2021001",
        "nama": "Budi Santoso",
        "email": "budi@mahasiswa.com",
        "role": "mahasiswa"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxx"
}
```

**Password Default:** `18102000` (dari tanggal_lahir: 18-10-2000)

### Login (POST /login)

**Request Body:**

```json
{
    "nim": "2021001",
    "password": "18102000"
}
```

**Response (200 OK):**

```json
{
    "message": "Login berhasil",
    "user": {
        "id": 1,
        "nim": "2021001",
        "nama": "Budi Santoso",
        "role": "mahasiswa"
    },
    "token": "2|xxxxxxxxxxxxxxxxxxxx"
}
```

### Using Token

Setelah login, gunakan token di header untuk setiap request:

```
Authorization: Bearer {token}
```

**Contoh dengan cURL:**

```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer 2|xxxxxxxxxxxxxxxxxxxx" \
  -H "Accept: application/json"
```

**Contoh dengan JavaScript:**

```javascript
fetch("http://localhost:8000/api/me", {
    headers: {
        Authorization: "Bearer " + token,
        Accept: "application/json",
    },
});
```

---

## 📖 Cara Penggunaan

### Skenario 1: Admin Membuat Survey Lengkap

#### Step 1: Login sebagai Admin

```bash
POST /api/login
```

```json
{
    "nim": "ADMIN001",
    "password": "admin123"
}
```

Simpan `token` dari response.

#### Step 2: Buat Survey Baru

```bash
POST /api/admin/surveys
Authorization: Bearer {admin_token}
```

```json
{
    "judul": "Survey Kepuasan Mahasiswa 2025",
    "deskripsi": "Evaluasi layanan akademik semester genap",
    "tanggal_mulai": "2025-10-20",
    "tanggal_selesai": "2025-12-31",
    "is_active": true
}
```

**Response:**

```json
{
    "message": "Survey berhasil dibuat",
    "survey": {
        "id": 1,
        "judul": "Survey Kepuasan Mahasiswa 2025",
        ...
    }
}
```

Simpan `survey_id` = 1

#### Step 3: Tambah Pertanyaan

```bash
POST /api/admin/surveys/1/questions
Authorization: Bearer {admin_token}
```

```json
{
    "pertanyaan": "Bagaimana kualitas pengajaran dosen?",
    "tipe": "multiple_choice",
    "urutan": 1,
    "options": [
        {
            "teks_pilihan": "Sangat Baik",
            "nilai": 5
        },
        {
            "teks_pilihan": "Baik",
            "nilai": 4
        },
        {
            "teks_pilihan": "Cukup",
            "nilai": 3
        },
        {
            "teks_pilihan": "Kurang",
            "nilai": 2
        },
        {
            "teks_pilihan": "Sangat Kurang",
            "nilai": 1
        }
    ]
}
```

Ulangi untuk menambah pertanyaan lainnya.

#### Step 4: Verifikasi Survey

```bash
GET /api/admin/surveys/1
Authorization: Bearer {admin_token}
```

---

### Skenario 2: Mahasiswa Mengisi Survey

#### Step 1: Register/Login sebagai Mahasiswa

**Register:**

```bash
POST /api/register
```

```json
{
    "nim": "2021005",
    "nama": "Dewi Lestari",
    "email": "dewi@mahasiswa.com",
    "tanggal_lahir": "2001-08-20",
    "jurusan": "Sistem Informasi"
}
```

**Atau Login:**

```bash
POST /api/login
```

```json
{
    "nim": "2021001",
    "password": "18102000"
}
```

#### Step 2: Lihat Survey yang Tersedia

```bash
GET /api/mahasiswa/surveys/available
Authorization: Bearer {mahasiswa_token}
```

**Response:**

```json
{
    "surveys": [
        {
            "id": 1,
            "judul": "Survey Kepuasan Mahasiswa 2025",
            "deskripsi": "...",
            "is_active": true,
            "questions": [
                {
                    "id": 1,
                    "pertanyaan": "Bagaimana kualitas pengajaran dosen?",
                    "options": [
                        {
                            "id": 1,
                            "teks_pilihan": "Sangat Baik",
                            "nilai": 5
                        },
                        ...
                    ]
                }
            ]
        }
    ]
}
```

#### Step 3: Submit Response

```bash
POST /api/mahasiswa/surveys/1/respond
Authorization: Bearer {mahasiswa_token}
```

```json
{
    "answers": [
        {
            "question_id": 1,
            "question_option_id": 1
        },
        {
            "question_id": 2,
            "question_option_id": 9
        },
        {
            "question_id": 3,
            "question_option_id": 13
        }
    ]
}
```

**Response:**

```json
{
    "message": "Terima kasih! Jawaban Anda telah tersimpan",
    "response": {
        "id": 1,
        "survey_id": 1,
        "user_id": 2,
        "submitted_at": "2025-10-24T10:30:00Z",
        "answers": [...]
    }
}
```

#### Step 4: Lihat Riwayat Response

```bash
GET /api/mahasiswa/my-responses
Authorization: Bearer {mahasiswa_token}
```

---

### Skenario 3: Admin Melihat Analytics

#### Step 1: Login sebagai Admin

```bash
POST /api/login
```

```json
{
    "nim": "ADMIN001",
    "password": "admin123"
}
```

#### Step 2: Lihat Overall Statistics

```bash
GET /api/admin/analytics/overview
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
    "statistics": {
        "total_surveys": 5,
        "active_surveys": 3,
        "total_responses": 150,
        "total_mahasiswa": 45
    },
    "top_surveys": [
        {
            "id": 1,
            "judul": "Survey Kepuasan Mahasiswa 2025",
            "responses_count": 50
        }
    ]
}
```

#### Step 3: Lihat Analytics Per Survey

```bash
GET /api/admin/analytics/surveys/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
    "survey": {
        "id": 1,
        "judul": "Survey Kepuasan Mahasiswa 2025",
        "total_responses": 50
    },
    "analytics": [
        {
            "question_id": 1,
            "pertanyaan": "Bagaimana kualitas pengajaran dosen?",
            "total_jawaban": 50,
            "nilai_rata_rata": 4.2,
            "option_statistics": [
                {
                    "option_id": 1,
                    "teks_pilihan": "Sangat Baik",
                    "nilai": 5,
                    "jumlah_dipilih": 20,
                    "persentase": 40
                },
                {
                    "option_id": 2,
                    "teks_pilihan": "Baik",
                    "nilai": 4,
                    "jumlah_dipilih": 25,
                    "persentase": 50
                },
                ...
            ]
        }
    ]
}
```

---

## 🧪 Testing

### Testing dengan Postman

#### 1. Import Collection

File: `Survey_API_Complete.postman_collection.json`

1. Buka Postman
2. Klik **Import**
3. Upload file collection
4. Collection siap digunakan

#### 2. Setup Environment

Buat environment baru:

-   `base_url`: `http://localhost:8000/api`
-   `token`: (otomatis tersimpan setelah login)

#### 3. Run Tests

**Manual Testing:**

1. Folder **1. Authentication** → Login Admin/Mahasiswa
2. Token otomatis tersimpan
3. Test endpoint lainnya

**Automated Testing:**

1. Klik kanan collection
2. Pilih **Run collection**
3. Pilih requests yang ingin di-test
4. Klik **Run**

### Testing dengan Frontend HTML

#### 1. Buka Testing Interface

```bash
# Buka file di browser
open index.html
# atau
python -m http.server 8080
```

#### 2. Akses via Browser

```
http://localhost:8080
```

#### 3. Test Scenarios

-   Login sebagai Admin/Mahasiswa
-   Navigasi antar tab
-   Test CRUD operations
-   View analytics

---

## 🚀 Deployment

### Production Checklist

#### 1. Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=production-db-host
DB_DATABASE=survey_mahasiswa
DB_USERNAME=production_user
DB_PASSWORD=strong_password
```

#### 2. Optimize Laravel

```bash
# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

#### 3. Security

```bash
# Generate new APP_KEY
php artisan key:generate

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

#### 4. Database Migration

```bash
# Run migrations on production
php artisan migrate --force

# Seed admin user only (jangan seed semua data testing)
php artisan db:seed --class=AdminSeeder
```

#### 5. SSL Certificate

-   Install SSL (Let's Encrypt recommended)
-   Force HTTPS in `.env`:

```env
APP_URL=https://your-domain.com
```

#### 6. CORS Configuration

Update `config/cors.php`:

```php
'allowed_origins' => [
    'https://your-frontend-domain.com'
],
```

### Deployment Platforms

#### Option 1: VPS (DigitalOcean, Linode, AWS EC2)

```bash
# Install dependencies
sudo apt update
sudo apt install php8.2 php8.2-mysql php8.2-mbstring composer nginx mysql-server

# Clone repo
git clone https://github.com/username/survey-api.git
cd survey-api

# Install & configure
composer install --no-dev
cp .env.example .env
php artisan key:generate
php artisan migrate

# Configure Nginx
sudo nano /etc/nginx/sites-available/survey-api
```

#### Option 2: Shared Hosting (cPanel)

1. Upload files via FTP/File Manager
2. Import database via phpMyAdmin
3. Configure `.env`
4. Set document root ke `/public`

#### Option 3: Laravel Forge

1. Connect your server
2. Create new site
3. Deploy from Git repository
4. Configure environment variables

---

## 🐛 Troubleshooting

### Common Issues

#### 1. CORS Error

**Problem:** Browser menolak request karena CORS

**Solution:**

```php
// config/cors.php
'allowed_origins' => ['*'], // atau specify domain
'supports_credentials' => true,
```

#### 2. Token Not Working

**Problem:** 401 Unauthenticated

**Solution:**

-   Cek format header: `Authorization: Bearer {token}`
-   Pastikan token tidak expired (cek `config/sanctum.php`)
-   Clear cache: `php artisan config:clear`

#### 3. Migration Error

**Problem:** `SQLSTATE[42S01]: Base table or view already exists`

**Solution:**

```bash
php artisan migrate:fresh --seed
```

#### 4. Permission Denied

**Problem:** Storage/cache permission error

**Solution:**

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 5. 500 Internal Server Error

**Solution:**

```bash
# Enable debug mode
APP_DEBUG=true

# Check logs
tail -f storage/logs/laravel.log

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

#### 6. Database Connection Error

**Problem:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**

-   Cek MySQL running: `sudo service mysql status`
-   Cek credentials di `.env`
-   Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

---

## 📞 Support & Contact

### Dokumentasi Tambahan

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laravel Sanctum](https://laravel.com/docs/sanctum)
-   [Postman Learning Center](https://learning.postman.com/)

### Issue Reporting

Jika menemukan bug atau ingin request fitur:

1. Buka [GitHub Issues](https://github.com/username/survey-api/issues)
2. Jelaskan masalah dengan detail
3. Sertakan error log jika ada

### Community

-   **Discord:** [Join our server](#)
-   **Email:** support@surveyapi.com
-   **Forum:** [Community Forum](#)

---

## 👥 Contributing

Kontribusi sangat diterima! Berikut cara berkontribusi:

### 1. Fork Repository

```bash
git clone https://github.com/your-username/survey-api.git
cd survey-api
```

### 2. Create Branch

```bash
git checkout -b feature/amazing-feature
```

### 3. Commit Changes

```bash
git add .
git commit -m "Add amazing feature"
```

### 4. Push to Branch

```bash
git push origin feature/amazing-feature
```

### 5. Open Pull Request

Buka PR di GitHub dengan deskripsi lengkap.

### Coding Standards

-   Follow PSR-12 coding standard
-   Write descriptive commit messages
-   Add comments for complex logic
-   Update documentation

---

## 📄 License

Project ini dilisensikan di bawah [MIT License](LICENSE).

```
MIT License

Copyright (c) 2025 Survey Mahasiswa API

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

---

## 🙏 Acknowledgments

-   [Laravel Framework](https://laravel.com/)
-   [Laravel Sanctum](https://laravel.com/docs/sanctum)
-   [Postman](https://www.postman.com/)
-   Semua contributor yang telah membantu

---

## 📊 Project Stats

![GitHub stars](https://img.shields.io/github/stars/username/survey-api?style=social)
![GitHub forks](https://img.shields.io/github/forks/username/survey-api?style=social)
![GitHub issues](https://img.shields.io/github/issues/username/survey-api)
![GitHub pull requests](https://img.shields.io/github/issues-pr/username/survey-api)

---

## 🗺️ Roadmap

### Version 1.0 (Current) ✅

-   [x] Authentication & Authorization
-   [x] Survey CRUD
-   [x] Question Management
-   [x] Response Submission
-   [x] Basic Analytics

### Version 1.1 (Planned) 🚧

-   [ ] Email notifications
-   [ ] Export to Excel/PDF
-   [ ] Advanced analytics (charts)
-   [ ] Survey templates
-   [ ] File upload questions

### Version 2.0 (Future) 💡

-   [ ] Multi-language support
-   [ ] Survey scheduling
-   [ ] API rate limiting
-   [ ] Webhook integration
-   [ ] Mobile app (React Native)

---

## 📝 Changelog

### [1.0.0] - 2025-10-24

#### Added

-   Initial release
-   Complete REST API
-   Authentication with Laravel Sanctum
-   Survey management
-   Question management with multiple choice
-   Response submission
-   Analytics dashboard
-   Postman collection
-   HTML testing interface
-   Complete documentation

---

## 🎓 Learning Resources

### Untuk Pemula

1. **Laravel Basics**

    - [Laravel From Scratch](https://laracasts.com/series/laravel-from-scratch)
    - [Laravel Documentation](https://laravel.com/docs)

2. **REST API**

    - [RESTful API Design Best Practices](https://restfulapi.net/)
    - [HTTP Status Codes](https://httpstatuses.com/)

3. **Database Design**
    - [Database Normalization](https://www.guru99.com/database-normalization.html)
    - [Laravel Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
