# Elektronik Store - Admin Panel

Web admin toko elektronik berbasis Laravel + Inertia + Vue 3.

## Fitur

- **Login Admin** - autentikasi dengan email & password
- **Dashboard** - menampilkan total pendapatan, grafik penjualan (harian/mingguan/bulanan), grafik perbandingan produk
- **Manajemen Produk** - CRUD lengkap (tambah, edit, hapus produk)

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+
- Docker (untuk MySQL)

## Cara Menjalankan

### 1. Jalankan MySQL dengan Docker

```bash
docker-compose up -d
```

Tunggu sampai MySQL ready (~10-15 detik).

### 2. Setup Laravel

```bash
composer install
```

### 3. Generate App Key (jika belum ada)

```bash
php artisan key:generate
```

### 4. Jalankan Migration & Seeder

```bash
php artisan migrate:fresh --seed
```

### 5. Build Frontend

```bash
npm install
npm run build
```

### 6. Jalankan Server

```bash
php artisan serve
```

Aplikasi bisa diakses di: **http://localhost:8000**

## Login

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@elektronik.com` | `admin123` |

## Struktur Database

### Tabel `kategori`
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key |
| nama | VARCHAR(100) | Nama kategori |
| created_at | TIMESTAMP | Tanggal dibuat |
| updated_at | TIMESTAMP | Tanggal diupdate |

### Tabel `produk`
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key |
| nama | VARCHAR(200) | Nama produk |
| kategori_id | BIGINT | FK ke kategori |
| harga | DECIMAL(15,0) | Harga produk |
| thumbnail | VARCHAR(500) | Link gambar thumbnail |
| link_gambar | VARCHAR(500) | Link gambar lengkap |
| deskripsi | TEXT | Deskripsi produk |
| stok | INT | Jumlah stok |
| created_at | TIMESTAMP | Tanggal dibuat |
| updated_at | TIMESTAMP | Tanggal diupdate |

### Tabel `penjualan`
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key |
| produk_id | BIGINT | FK ke produk |
| jumlah | INT | Jumlah terjual |
| total_harga | DECIMAL(15,0) | Total harga |
| customer_name | VARCHAR(200) | Nama pelanggan |
| status | ENUM | pending/processing/completed/cancelled |
| created_at | TIMESTAMP | Tanggal transaksi |
| updated_at | TIMESTAMP | Tanggal diupdate |

## Routes

| Method | URI | Deskripsi |
|--------|-----|-----------|
| GET | `/login` | Halaman login |
| POST | `/login` | Proses login |
| POST | `/logout` | Logout |
| GET | `/dashboard` | Dashboard (auth required) |
| GET | `/produk` | Daftar produk (auth required) |
| POST | `/produk` | Tambah produk |
| PUT | `/produk/{id}` | Update produk |
| DELETE | `/produk/{id}` | Hapus produk |

## Docker MySQL

```bash
# Start
docker-compose up -d

# Stop
docker-compose down

# Lihat logs
docker-compose logs -f mysql
```
