# 📚 Dokumentasi API Survey Mahasiswa

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.35.1-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![REST API](https://img.shields.io/badge/REST-API-009688?style=for-the-badge&logo=fastapi&logoColor=white)

</div>

---

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
-   ✅ Update pertanyaan dan options
-   ✅ Delete pertanyaan

### 👨‍🎓 Mahasiswa Features

-   ✅ Lihat survey yang tersedia (belum diisi)
-   ✅ Submit response dengan validasi
-   ✅ Satu mahasiswa hanya bisa isi survey satu kali
-   ✅ Lihat riwayat response

### 📊 Analytics (Admin)

-   ✅ Overall statistics (total surveys, responses, users)
-   ✅ Analytics per survey (rata-rata, distribusi jawaban)
-   ✅ Persentase pilihan per pertanyaan
-   ✅ Top surveys berdasarkan jumlah response

---

## 🚀 Instalasi

### Prerequisites

Pastikan sudah terinstall:

-   PHP >= 8.2
-   Composer
-   MySQL >= 8.0
-   Git

### Langkah Instalasi

#### 1. Clone Repository

```bash
git clone https://github.com/rahmathidayatlubis/survey-api.git

```

```bash
cd survey-api
```

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

Atau bisa juga menggunakan phpMyAdmin supaya lebih mudah

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

### Environment Variables

```env
APP_NAME="Survey Mahasiswa API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

## 🗄️ Database Schema

### Users Table

| Column        | Type   | Description                    |
| ------------- | ------ | ------------------------------ |
| id            | BIGINT | Primary key                    |
| uuid          | CHAR   | Unik field                     |
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
| uuid            | CHAR    | Unik field           |
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
| POST   | /login    | ❌   | Login              |
| POST   | /register | ✅   | Register mahasiswa |
| POST   | /logout   | ✅   | Logout             |
| GET    | /me       | ✅   | Get user profile   |

### Mahasiswa Endpoints

| Method | Endpoint                          | Auth | Description           |
| ------ | --------------------------------- | ---- | --------------------- |
| GET    | /mahasiswa/surveys/available      | ✅   | Get available surveys |
| POST   | /mahasiswa/surveys/{uuid}/respond | ✅   | Submit response       |
| GET    | /mahasiswa/my-responses           | ✅   | Get response history  |

### Admin - Survey Management

| Method | Endpoint              | Auth  | Description       |
| ------ | --------------------- | ----- | ----------------- |
| GET    | /admin/surveys        | Admin | Get all surveys   |
| POST   | /admin/surveys        | Admin | Create survey     |
| GET    | /admin/surveys/{uuid} | Admin | Get survey detail |
| PUT    | /admin/surveys/{uuid} | Admin | Update survey     |
| DELETE | /admin/surveys/{uuid} | Admin | Delete survey     |

### Admin - Question Management

| Method | Endpoint                        | Auth  | Description     |
| ------ | ------------------------------- | ----- | --------------- |
| POST   | /admin/surveys/{uuid}/questions | Admin | Add question    |
| PUT    | /admin/questions/{id}           | Admin | Update question |
| DELETE | /admin/questions/{id}           | Admin | Delete question |

### Admin - Analytics

| Method | Endpoint                        | Auth  | Description          |
| ------ | ------------------------------- | ----- | -------------------- |
| GET    | /admin/analytics/surveys/{uuid} | Admin | Get survey analytics |
| GET    | /admin/analytics/overview       | Admin | Get overall stats    |

---

## 🔐 Authentication

### Register (POST /register)

**Request Body:**

```json
{
    "nim": "21220019",
    "nama": "Rahmat Hidayat Lubis",
    "email": "rahmattlubis86@gmail.com",
    "tanggal_lahir": "2000-10-09",
    "jurusan": "Sistem Informasi"
}
```

**Response (201 Created):**

```json
{
    "success": true,
    "message": "Registrasi mahasiswa berhasil",
    "data": {
        "user": {
            "nim": "21220019",
            "nama": "Rahmat Hidayat Lubis",
            "email": "rahmattlubis86@gmail.com",
            "tanggal_lahir": "2000-10-09T17:00:00.000000Z",
            "jurusan": "Sistem Informasi",
            "role": "mahasiswa",
            "uuid": "52c5065c-e619-44c5-8e6e-894dff6b7139",
            "updated_at": "2025-10-28T02:29:12.000000Z",
            "created_at": "2025-10-28T02:29:12.000000Z",
            "id": 4
        }
    }
}
```

**Password Default (Jika tidak mengirim request password):** `10102000` (dari tanggal_lahir: 10-10-2000)

### Login (POST /login)

**Request Body:**

```json
{
    "identifier": "21220019",
    "password": "10102000"
}
```

**Response (200 OK):**

```json
{
    "success": true,
    "message": "Login berhasil.",
    "data": {
        "user": {
            "uuid": "a919a045-357a-4761-8d54-bd7358d7a783",
            "nama": "Rahmat Hidayat Lubis",
            "email": "rahmattlubis86@gmail.com",
            "role": "mahasiswa",
            "nim": "21220019",
            "jurusan": "Sistem Informasi"
        },
        "token": "3|m1GW9RFOrpEPVMbiIoVQ4SOmndQZSrXAilHAVAUGd0fa262e"
    }
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
    "identifier": "admin@survey.com",
    "password": "admin123"
}
```

Simpan `token` dari response.

#### Step 2: Buat Survey Baru

```bash
POST /api/admin/surveys
Authorization: Bearer {token}
```

```json
{
    "judul": "Survey Kepuasan Mahasiswa Semester Genap 2025",
    "deskripsi": "Survey untuk mengukur tingkat kepuasan mahasiswa terhadap pembelajaran semester genap",
    "tanggal_mulai": "2025-10-25",
    "tanggal_selesai": "2025-12-31",
    "is_active": true
}
```

**Response:**

```json
{
    "success": true,
    "message": "Survey berhasil dibuat.",
    "data": {
        "survey": {
            "judul": "Survey Kepuasan Mahasiswa Semester Genap 2025",
            "deskripsi": "Survey untuk mengukur tingkat kepuasan mahasiswa terhadap pembelajaran semester genap",
            "tanggal_mulai": "2025-10-24T17:00:00.000000Z",
            "tanggal_selesai": "2025-12-30T17:00:00.000000Z",
            "is_active": true,
            "uuid": "f5d63c3f-c011-45c4-8340-db87f45e1632",
            "updated_at": "2025-10-28T02:43:44.000000Z",
            "created_at": "2025-10-28T02:43:44.000000Z",
            "id": 3
        }
    }
}
```

Simpan `uuid_survey` = xxxx-xxxx-xxxx-xx

#### Step 3: Tambah Pertanyaan

```bash
POST /api/admin/surveys/{uuid_survey}/questions
Authorization: Bearer {token}
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
GET /api/admin/surveys/{uuid_survey}
Authorization: Bearer {token}
```

---

### Skenario 2: Mahasiswa Mengisi Survey

#### Step 1: Login dengan akun mahasiswa

**Login:**

```bash
POST /api/login
```

```json
{
    "identifier": "21220019",
    "password": "10102000"
}
```

#### Step 2: Lihat Survey yang Tersedia

```bash
GET /api/mahasiswa/surveys/available
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Berhasil mengambil survey tersedia.",
    "data": {
        "surveys": [
            {
                "id": 1,
                "uuid": "cbd8916f-c460-4ff0-9e57-59819ad6996f",
                "judul": "Evaluasi Kepuasan Mahasiswa Terhadap Layanan Akademik",
                "deskripsi": "Survey untuk mengevaluasi tingkat kepuasan mahasiswa terhadap layanan akademik kampus semester genap 2025",
                "tanggal_mulai": "2025-10-20T17:00:00.000000Z",
                "tanggal_selesai": "2025-11-26T17:00:00.000000Z",
                "is_active": true,
                "created_at": "2025-10-27T17:05:07.000000Z",
                "updated_at": "2025-10-27T17:05:07.000000Z",
                "questions": [
                    {
                        "id": 1,
                        "survey_id": 1,
                        "pertanyaan": "Bagaimana penilaian Anda terhadap kualitas pengajaran dosen?",
                        "tipe": "multiple_choice",
                        "urutan": 1,
                        "created_at": "2025-10-27T17:05:07.000000Z",
                        "updated_at": "2025-10-27T17:05:07.000000Z",
                        "options": [
                            {
                                "id": 1,
                                "question_id": 1,
                                "teks_pilihan": "Sangat Baik",
                                "nilai": 5,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 2,
                                "question_id": 1,
                                "teks_pilihan": "Baik",
                                "nilai": 4,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 3,
                                "question_id": 1,
                                "teks_pilihan": "Cukup",
                                "nilai": 3,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 4,
                                "question_id": 1,
                                "teks_pilihan": "Kurang",
                                "nilai": 2,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 5,
                                "question_id": 1,
                                "teks_pilihan": "Sangat Kurang",
                                "nilai": 1,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            }
                        ]
                    },
                    {
                        "id": 2,
                        "survey_id": 1,
                        "pertanyaan": "Seberapa puas Anda dengan fasilitas perpustakaan?",
                        "tipe": "multiple_choice",
                        "urutan": 2,
                        "created_at": "2025-10-27T17:05:07.000000Z",
                        "updated_at": "2025-10-27T17:05:07.000000Z",
                        "options": [
                            {
                                "id": 6,
                                "question_id": 2,
                                "teks_pilihan": "Sangat Puas",
                                "nilai": 5,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 7,
                                "question_id": 2,
                                "teks_pilihan": "Puas",
                                "nilai": 4,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 8,
                                "question_id": 2,
                                "teks_pilihan": "Cukup Puas",
                                "nilai": 3,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 9,
                                "question_id": 2,
                                "teks_pilihan": "Tidak Puas",
                                "nilai": 2,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 10,
                                "question_id": 2,
                                "teks_pilihan": "Sangat Tidak Puas",
                                "nilai": 1,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            }
                        ]
                    },
                    {
                        "id": 3,
                        "survey_id": 1,
                        "pertanyaan": "Bagaimana pelayanan administrasi akademik?",
                        "tipe": "multiple_choice",
                        "urutan": 3,
                        "created_at": "2025-10-27T17:05:07.000000Z",
                        "updated_at": "2025-10-27T17:05:07.000000Z",
                        "options": [
                            {
                                "id": 11,
                                "question_id": 3,
                                "teks_pilihan": "Sangat Memuaskan",
                                "nilai": 5,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 12,
                                "question_id": 3,
                                "teks_pilihan": "Memuaskan",
                                "nilai": 4,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 13,
                                "question_id": 3,
                                "teks_pilihan": "Cukup",
                                "nilai": 3,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 14,
                                "question_id": 3,
                                "teks_pilihan": "Kurang Memuaskan",
                                "nilai": 2,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 15,
                                "question_id": 3,
                                "teks_pilihan": "Tidak Memuaskan",
                                "nilai": 1,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            }
                        ]
                    },
                    {
                        "id": 4,
                        "survey_id": 1,
                        "pertanyaan": "Bagaimana kondisi fasilitas laboratorium komputer?",
                        "tipe": "multiple_choice",
                        "urutan": 4,
                        "created_at": "2025-10-27T17:05:07.000000Z",
                        "updated_at": "2025-10-27T17:05:07.000000Z",
                        "options": [
                            {
                                "id": 16,
                                "question_id": 4,
                                "teks_pilihan": "Sangat Baik",
                                "nilai": 5,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 17,
                                "question_id": 4,
                                "teks_pilihan": "Baik",
                                "nilai": 4,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 18,
                                "question_id": 4,
                                "teks_pilihan": "Cukup",
                                "nilai": 3,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 19,
                                "question_id": 4,
                                "teks_pilihan": "Buruk",
                                "nilai": 2,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 20,
                                "question_id": 4,
                                "teks_pilihan": "Sangat Buruk",
                                "nilai": 1,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "survey_id": 1,
                        "pertanyaan": "Bagaimana penilaian Anda terhadap kebersihan lingkungan kampus?",
                        "tipe": "multiple_choice",
                        "urutan": 5,
                        "created_at": "2025-10-27T17:05:07.000000Z",
                        "updated_at": "2025-10-27T17:05:07.000000Z",
                        "options": [
                            {
                                "id": 21,
                                "question_id": 5,
                                "teks_pilihan": "Sangat Bersih",
                                "nilai": 5,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 22,
                                "question_id": 5,
                                "teks_pilihan": "Bersih",
                                "nilai": 4,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 23,
                                "question_id": 5,
                                "teks_pilihan": "Cukup Bersih",
                                "nilai": 3,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 24,
                                "question_id": 5,
                                "teks_pilihan": "Kotor",
                                "nilai": 2,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            },
                            {
                                "id": 25,
                                "question_id": 5,
                                "teks_pilihan": "Sangat Kotor",
                                "nilai": 1,
                                "created_at": "2025-10-27T17:05:07.000000Z",
                                "updated_at": "2025-10-27T17:05:07.000000Z"
                            }
                        ]
                    },
                    {
                        "id": 7,
                        "survey_id": 1,
                        "pertanyaan": "Apakah koneksi WiFi kampus memadai?",
                        "tipe": "multiple_choice",
                        "urutan": 6,
                        "created_at": "2025-10-27T17:05:57.000000Z",
                        "updated_at": "2025-10-27T17:05:57.000000Z",
                        "options": [
                            {
                                "id": 31,
                                "question_id": 7,
                                "teks_pilihan": "Sangat Memadai",
                                "nilai": 5,
                                "created_at": "2025-10-27T17:05:57.000000Z",
                                "updated_at": "2025-10-27T17:05:57.000000Z"
                            },
                            {
                                "id": 32,
                                "question_id": 7,
                                "teks_pilihan": "Memadai",
                                "nilai": 4,
                                "created_at": "2025-10-27T17:05:57.000000Z",
                                "updated_at": "2025-10-27T17:05:57.000000Z"
                            },
                            {
                                "id": 33,
                                "question_id": 7,
                                "teks_pilihan": "Cukup",
                                "nilai": 3,
                                "created_at": "2025-10-27T17:05:57.000000Z",
                                "updated_at": "2025-10-27T17:05:57.000000Z"
                            },
                            {
                                "id": 34,
                                "question_id": 7,
                                "teks_pilihan": "Kurang Memadai",
                                "nilai": 2,
                                "created_at": "2025-10-27T17:05:57.000000Z",
                                "updated_at": "2025-10-27T17:05:57.000000Z"
                            },
                            {
                                "id": 35,
                                "question_id": 7,
                                "teks_pilihan": "Tidak Memadai",
                                "nilai": 1,
                                "created_at": "2025-10-27T17:05:57.000000Z",
                                "updated_at": "2025-10-27T17:05:57.000000Z"
                            }
                        ]
                    }
                ]
            }
        ]
    }
}
```

#### Step 3: Submit Response

```bash
POST /api/mahasiswa/surveys/{uuid_survey}/respond
Authorization: Bearer {token}
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
        },
        {
            "question_id": 4,
            "question_option_id": 18
        },
        {
            "question_id": 5,
            "question_option_id": 23
        }
    ]
}
```

**Response:**

```json
{
    "success": true,
    "message": "Terima kasih! Jawaban Anda telah tersimpan.",
    "data": {
        "response": {
            "survey_id": 3,
            "user_id": 4,
            "submitted_at": "2025-10-28T02:52:05.000000Z",
            "updated_at": "2025-10-28T02:52:05.000000Z",
            "created_at": "2025-10-28T02:52:05.000000Z",
            "id": 1,
            "answers": [
                {
                    "id": 1,
                    "response_id": 1,
                    "question_id": 1,
                    "question_option_id": 1,
                    "jawaban_teks": null,
                    "nilai": 5,
                    "created_at": "2025-10-28T02:52:05.000000Z",
                    "updated_at": "2025-10-28T02:52:05.000000Z"
                },
                {
                    "id": 2,
                    "response_id": 1,
                    "question_id": 2,
                    "question_option_id": 9,
                    "jawaban_teks": null,
                    "nilai": 2,
                    "created_at": "2025-10-28T02:52:05.000000Z",
                    "updated_at": "2025-10-28T02:52:05.000000Z"
                },
                {
                    "id": 3,
                    "response_id": 1,
                    "question_id": 3,
                    "question_option_id": 13,
                    "jawaban_teks": null,
                    "nilai": 3,
                    "created_at": "2025-10-28T02:52:05.000000Z",
                    "updated_at": "2025-10-28T02:52:05.000000Z"
                },
                {
                    "id": 4,
                    "response_id": 1,
                    "question_id": 4,
                    "question_option_id": 18,
                    "jawaban_teks": null,
                    "nilai": 3,
                    "created_at": "2025-10-28T02:52:05.000000Z",
                    "updated_at": "2025-10-28T02:52:05.000000Z"
                },
                {
                    "id": 5,
                    "response_id": 1,
                    "question_id": 5,
                    "question_option_id": 23,
                    "jawaban_teks": null,
                    "nilai": 3,
                    "created_at": "2025-10-28T02:52:05.000000Z",
                    "updated_at": "2025-10-28T02:52:05.000000Z"
                }
            ]
        }
    }
}
```

#### Step 4: Lihat Riwayat Response

```bash
GET /api/mahasiswa/my-responses
Authorization: Bearer {token}
```

---

### Skenario 3: Admin Melihat Analytics

#### Step 1: Login sebagai Admin

```bash
POST /api/login
```

```json
{
    "identifier": "admin@survey.com",
    "password": "admin123"
}
```

#### Step 2: Lihat Overall Statistics

```bash
GET /api/admin/analytics/overview
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Statistik keseluruhan berhasil diambil.",
    "data": {
        "statistics": {
            "total_surveys": 3,
            "active_surveys": 2,
            "total_responses": 1,
            "total_mahasiswa": 3
        },
        "top_surveys": [
            {
                "id": 3,
                "judul": "Survey Kepuasan Mahasiswa Semester Genap 2025",
                "responses_count": 1
            },
            {
                "id": 1,
                "judul": "Evaluasi Kepuasan Mahasiswa Terhadap Layanan Akademik",
                "responses_count": 0
            },
            {
                "id": 2,
                "judul": "Evaluasi Pembelajaran Online",
                "responses_count": 0
            }
        ]
    }
}
```

#### Step 3: Lihat Analytics Per Survey

```bash
GET /api/admin/analytics/surveys/{uuid_survey}
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Analytics survey berhasil diambil.",
    "data": {
        "survey": {
        "id": 1,
        "judul": "Survey Kepuasan Mahasiswa 2025",
        "total_responses": 50
    },
    "analytics": {
        {
            "question_id": 1,
            "pertanyaan": "Bagaimana kualitas pengajaran dosen?",
            "total_jawaban": 50,
            "nilai_rata_rata": 4.2,
            "option_statistics": {
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
            }
        }
    }
    }
}
```

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
# Run migrations on local
php artisan migrate:fresh --seed
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

1. Buka [GitHub Issues](https://github.com/rahmathidayatlubis/survey-api/issues)
2. Jelaskan masalah dengan detail
3. Sertakan error log jika ada

---

## 👥 Contributing

Kontribusi sangat diterima! Berikut cara berkontribusi:

### 1. Fork Repository

```bash
git clone https://github.com/rahmathidayatlubis/survey-api.git
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
