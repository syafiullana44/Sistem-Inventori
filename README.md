# SR Wood Craft - Sistem Informasi Manajemen Gudang & Produksi

**SR Wood Craft** adalah aplikasi Sistem Informasi Manajemen berbasis web yang dibangun menggunakan framework [Laravel](https://laravel.com). Aplikasi ini dirancang untuk mengelola berbagai proses operasional harian seperti manajemen gudang, pengadaan barang, hingga pencatatan proses produksi secara terintegrasi.

## Fitur Utama

- **Manajemen Gudang (Warehouse):** Pemantauan stok bahan baku dan pencatatan barang masuk/keluar.
- **Manajemen Pengadaan (Procurement):** Pencatatan dan pengelolaan riwayat permintaan pengadaan bahan baku.
- **Manajemen Produksi (Production):** Pengelolaan permintaan produksi dari gudang dan pencatatan riwayat produksi.
- **Laporan & Ekspor (Export):** Mendukung ekspor data dan riwayat ke dalam format PDF (misal: Histori Pengadaan, Histori Pengeluaran, dll).
- **Manajemen Pengguna & Peran:** (Admin, Gudang, Produksi, Pengadaan) untuk membatasi akses sesuai otoritasnya.

## Persyaratan Sistem

- PHP ^8.2
- Composer
- Node.js & npm
- Database (MySQL/MariaDB)

## Instalasi Lokal (Tanpa Docker)

Jika Anda ingin menjalankan proyek ini secara lokal tanpa Docker, ikuti langkah-langkah berikut:

1. Salin `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda:
   ```bash
   cp .env.example .env
   ```
2. Install dependensi PHP melalui Composer:
   ```bash
   composer install
   ```
3. Generate *application key*:
   ```bash
   php artisan key:generate
   ```
4. Jalankan migrasi (beserta *seeder* jika diperlukan):
   ```bash
   php artisan migrate --seed
   ```
5. Install dependensi Node.js dan build asset:
   ```bash
   npm install && npm run build
   ```
6. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

---

## Docker Setup for SR Wood Craft

This project includes a `Dockerfile` to easily containerize and run the application using Docker. It is configured using PHP 8.2 with Apache and includes the necessary extensions and configurations for Laravel.

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/) installed on your machine.

### How to Build the Image

To build the Docker image, run the following command in the root of the project (where the `Dockerfile` is located):

```bash
docker build -t sr-wood-craft-app .
```

This will download the base PHP 8.2 image, install required PHP extensions, Composer dependencies, Node.js dependencies, and build the Vite assets.

### How to Run the Container

Once the image is built, you can start the application using:

```bash
docker run -d -p 8080:80 --name sr-wood-craft sr-wood-craft-app
```

* This maps port `8080` on your host machine to port `80` (Apache) in the container.
* After running, you can access the application by opening `http://localhost:8080` in your web browser.

### Important Notes for Environment Variables

When running in production or testing, make sure your database and other environment variables are properly set. You can pass environment variables to Docker during `docker run` using the `-e` flag or via an `.env` file with the `--env-file` flag:

```bash
docker run -d -p 8080:80 --env-file .env --name sr-wood-craft sr-wood-craft-app
```

### Useful Commands

* **Stop the container:** `docker stop sr-wood-craft`
* **Start the container again:** `docker start sr-wood-craft`
* **Remove the container:** `docker rm sr-wood-craft`
* **Access the container shell:** `docker exec -it sr-wood-craft bash`
