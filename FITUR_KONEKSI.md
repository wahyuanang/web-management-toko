# Dokumentasi Koneksi Fitur - Web Management Toko

## ✅ Status Implementasi

Semua fitur telah diimplementasikan dan saling terkoneksi dengan baik.

---

## 🔄 Alur Kerja Sistem

### 1. **ADMIN → Membuat Assignment untuk Karyawan**

#### Fitur:
- Admin dapat membuat assignment baru melalui **Filament Admin Panel**
- Admin dapat memilih **karyawan** (filter otomatis hanya menampilkan user dengan role "karyawan")
- Admin mengisi:
  - Title (Judul Tugas)
  - Description (Deskripsi)
  - **Lokasi Tujuan** (akan otomatis terisi di form laporan karyawan)
  - Product (Produk yang akan dikirim)
  - Qty Target (Target jumlah barang)
  - Priority (Low, Medium, High)
  - Status (Pending, In Progress, Done, Cancelled)
  - Deadline

#### Koneksi:
- **Assignment Model** → Trigger notifikasi ke karyawan saat assignment dibuat
- **Notification System** → Karyawan menerima notifikasi real-time
- **Database** → Data tersimpan di tabel `assignments`

**File terkait:**
- `app/Filament/Resources/Assignments/Schemas/AssignmentForm.php`
- `app/Models/Assignment.php` (booted method)
- `app/Notifications/NewAssignmentNotification.php`

---

### 2. **KARYAWAN → Menerima Notifikasi Assignment**

#### Fitur:
- Karyawan otomatis menerima notifikasi saat admin membuat assignment
- Notifikasi muncul di **Dashboard Karyawan**
- Menampilkan jumlah notifikasi yang belum dibaca
- Setiap notifikasi memiliki link ke detail assignment

#### Koneksi:
- **Assignment Created Event** → Trigger notification
- **Database** → Notifikasi tersimpan di tabel `notifications`
- **User Model** → Relasi dengan notifications

**File terkait:**
- `app/Models/Assignment.php` (static::created)
- `resources/views/karyawan/dashboard.blade.php`
- `database/migrations/*_create_notifications_table.php`

---

### 3. **KARYAWAN → Melihat Daftar Tugas**

#### Fitur:
- Karyawan dapat melihat semua assignment yang ditugaskan kepadanya
- **Filter berdasarkan status** (pending, in_progress, done, cancelled)
- **Search** berdasarkan title dan description
- Menampilkan informasi lengkap: title, product, qty target, status, deadline

#### Koneksi:
- **AssignmentController** → Query assignment berdasarkan `assigned_to` = user login
- **Database** → Filter data dari tabel `assignments`

**File terkait:**
- `app/Http/Controllers/Karyawan/AssignmentController.php`
- `resources/views/karyawan/assignments/index.blade.php`

---

### 4. **KARYAWAN → Membuat Laporan**

#### Fitur AUTO-FILL:
1. **Pilih Assignment** → Dropdown hanya menampilkan assignment yang ditugaskan ke karyawan tersebut
2. **Auto-fill Jumlah Barang** → Otomatis terisi sesuai `qty_target` dari assignment
3. **Auto-fill Lokasi Tujuan** → Otomatis terisi dari field `lokasi_tujuan` di assignment

#### Form Input:
- Assignment (Select - wajib)
- Jumlah Barang Dikirim (Auto-fill)
- **Lokasi Pengiriman (Auto-fill dari assignment)**
- Catatan (Opsional)
- Foto Bukti 1 (Wajib)
- Foto Bukti 2 (Opsional)
- Waktu Laporan

#### Koneksi:
- **JavaScript** → Fetch data assignment via API endpoint
- **API Controller** → Return data assignment (lokasi_tujuan, qty_target)
- **ReportController** → Validasi dan simpan laporan

**File terkait:**
- `resources/views/karyawan/reports/create.blade.php` (JavaScript auto-fill)
- `app/Http/Controllers/Api/AssignmentController.php` (API endpoint)
- `app/Http/Controllers/Karyawan/ReportController.php`
- `routes/web.php` (API route)

**API Endpoint:**
```
GET /karyawan/api/assignments/{id}
```

---

### 5. **SISTEM → Update Status Assignment Otomatis**

#### Trigger:
Saat karyawan **menyimpan laporan**, sistem otomatis:

1. **Status: Pending → In Progress**
   - Jika assignment masih pending dan ada laporan pertama

2. **Status: In Progress → Done**
   - Jika total barang dikirim >= qty target
   - Kalkulasi: SUM semua `jumlah_barang_dikirim` dari reports

3. **Status: Done → In Progress**
   - Jika laporan dihapus dan total < qty target

4. **Status: In Progress → Pending**
   - Jika semua laporan dihapus

#### Koneksi:
- **Report Model** → Event listeners (created, updated, deleted)
- **Assignment Model** → Auto update status
- **Database** → Update tabel `assignments`

**File terkait:**
- `app/Models/Report.php` (booted method)
- `app/Models/Assignment.php` (getTotalDikirimAttribute)

---

### 6. **ADMIN → Melihat Laporan dari Karyawan**

#### Fitur di Tabel Reports (Filament):
- Nama Tugas + Status Assignment
- Nama Karyawan (badge)
- Jumlah Dikirim (badge dengan unit)
- **Status Tugas** (badge dengan warna: done=hijau, in_progress=kuning, pending=abu, cancelled=merah)
- Lokasi
- Foto Bukti 1 & 2
- Waktu Laporan
- Sort & Filter

#### Koneksi:
- **ReportsTable** → Menampilkan relasi assignment dan user
- **Database** → Join tabel reports, assignments, users
- **Badge Color** → Dinamis berdasarkan status

**File terkait:**
- `app/Filament/Resources/Reports/Tables/ReportsTable.php`
- `app/Filament/Resources/Reports/ReportResource.php`

---

### 7. **ADMIN → Melihat Progress Assignment**

#### Fitur di Tabel Assignments (Filament):
- Judul Tugas
- Karyawan
- Produk
- Target Qty
- **Total Terkirim** (badge - hijau jika >= target, kuning jika < target)
- **Progress %** (badge - hijau >= 100%, kuning >= 50%, merah < 50%)
- Prioritas
- **Status** (badge dengan warna dinamis)
- Deadline
- Dibuat Oleh

#### Kalkulasi Real-time:
- `total_dikirim` = SUM dari semua reports terkait assignment
- `progress` = (total_dikirim / qty_target) * 100

#### Koneksi:
- **Assignment Model** → Accessor methods (getTotalDikirimAttribute, getProgressAttribute)
- **AssignmentsTable** → Menampilkan data kalkulasi
- **Database** → Aggregate query dari tabel reports

**File terkait:**
- `app/Filament/Resources/Assignments/Tables/AssignmentsTable.php`
- `app/Models/Assignment.php`

---

### 8. **KARYAWAN → Dashboard Real-time**

#### Widget Statistics:
- **Total Tugas** → COUNT assignments yang assigned ke karyawan
- **Total Laporan** → COUNT reports yang dibuat karyawan

#### Recent Data:
- **Tugas Terbaru** (5 terakhir)
  - Title, Target Qty, Status, Deadline
- **Laporan Terbaru** (5 terakhir)
  - Assignment Title, Jumlah Dikirim, Waktu

#### Notifikasi:
- Jumlah notifikasi belum dibaca
- List 5 notifikasi terakhir dengan link ke detail

#### Koneksi:
- **DashboardController** → Query data real-time
- **Database** → COUNT dan latest queries
- **Notification System** → Unread notifications

**File terkait:**
- `app/Http/Controllers/Karyawan/DashboardController.php`
- `resources/views/karyawan/dashboard.blade.php`

---

## 📊 Database Schema & Relasi

### Tabel: `users`
```
- id
- name
- email
- password
- role
- phone
- address
- status
```

**Relasi:**
- `hasMany(Assignment, 'created_by')` → Assignments yang dibuat admin
- `hasMany(Assignment, 'assigned_to')` → Assignments untuk karyawan
- `hasMany(Report)` → Reports yang dibuat karyawan

---

### Tabel: `assignments`
```
- id
- title
- description
- lokasi_tujuan (BARU) ⭐
- assigned_to (FK → users)
- product_id (FK → products)
- qty_target
- priority
- status
- deadline
- created_by (FK → users)
- timestamps
```

**Relasi:**
- `belongsTo(User, 'assigned_to')` → Karyawan
- `belongsTo(User, 'created_by')` → Admin
- `belongsTo(Product)`
- `hasMany(Report)` → Laporan

**Accessor:**
- `total_dikirim` → SUM jumlah_barang_dikirim
- `progress` → Persentase (total_dikirim / qty_target * 100)

---

### Tabel: `reports`
```
- id
- assignment_id (FK → assignments)
- user_id (FK → users)
- jumlah_barang_dikirim
- lokasi
- catatan
- foto_bukti
- foto_bukti_2
- waktu_laporan
- timestamps
```

**Relasi:**
- `belongsTo(Assignment)`
- `belongsTo(User)` → Karyawan

**Events:**
- `created` → Update assignment status
- `updated` → Rekalkulasi status
- `deleted` → Rekalkulasi status

---

### Tabel: `notifications`
```
- id
- type
- notifiable_type
- notifiable_id
- data (JSON)
- read_at
- timestamps
```

**Data JSON Structure:**
```json
{
  "assignment_id": 1,
  "title": "Kirim Produk A",
  "description": "...",
  "qty_target": 100,
  "priority": "high",
  "deadline": "2025-11-20",
  "message": "Anda mendapat tugas baru: Kirim Produk A"
}
```

---

## 🔐 Role & Permission

### Role: **Admin**
- Akses Filament Admin Panel
- CRUD Assignments
- CRUD Reports (view only)
- CRUD Products
- CRUD Users
- Melihat semua laporan dari karyawan
- Melihat progress assignment real-time

### Role: **Karyawan**
- Akses Dashboard Karyawan
- View Assignments (yang ditugaskan saja)
- CRUD Reports (hanya milik sendiri)
- Menerima notifikasi assignment
- Tidak bisa akses Filament Admin

**File terkait:**
- `app/Models/User.php` (HasRoles trait)
- `database/seeders/RoleSeeder.php`
- `routes/web.php` (middleware role)

---

## 🚀 Cara Menjalankan

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit `.env`:
```
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migration & Seeder
```bash
php artisan migrate --seed
```

### 5. Create Storage Link
```bash
php artisan storage:link
```

### 6. Run Server
```bash
php artisan serve
npm run dev
```

---

## 📝 Testing Flow

### Skenario 1: Admin Buat Assignment
1. Login sebagai Admin
2. Buka `/admin/assignments/create`
3. Pilih Karyawan (hanya role karyawan muncul)
4. Isi form termasuk **Lokasi Tujuan**
5. Save
6. ✅ Notifikasi otomatis terkirim ke karyawan

### Skenario 2: Karyawan Terima & Kerjakan
1. Login sebagai Karyawan
2. Dashboard menampilkan notifikasi baru
3. Klik notifikasi → detail assignment
4. Buat laporan baru
5. **Pilih assignment** → Lokasi & Qty otomatis terisi
6. Upload foto bukti
7. Save
8. ✅ Status assignment otomatis update

### Skenario 3: Admin Monitor Progress
1. Login sebagai Admin
2. Buka `/admin/assignments`
3. Lihat kolom:
   - Total Terkirim (real-time)
   - Progress % (real-time)
   - Status (otomatis update)
4. Buka `/admin/reports`
5. Lihat semua laporan dari karyawan
6. ✅ Status assignment sudah "Done" jika target tercapai

---

## 🎯 Checklist Requirement

### ✅ Role Admin
- [x] Dapat melakukan assignment ke karyawan
- [x] Assignment masuk ke notifikasi karyawan
- [x] Assignment masuk ke daftar tugas karyawan
- [x] Dapat memilih karyawan berdasarkan role
- [x] Dapat menerima laporan dari karyawan
- [x] Laporan otomatis update status assignment
- [x] Dapat melihat hasil laporan di tabel reports
- [x] Dapat melihat progress real-time

### ✅ Role Karyawan
- [x] Dapat melihat hasil tugas dari admin
- [x] Dapat mengisi form input yang sudah diberikan admin
- [x] Form lokasi otomatis terisi setelah pilih tugas
- [x] Dapat melakukan pencarian tugas
- [x] Fitur filter berdasarkan status
- [x] Dapat melihat total tugas & laporan real-time
- [x] Dapat melihat tugas terbaru & laporan terbaru
- [x] Select pilih tugas otomatis berdasarkan assignment
- [x] Dapat menyimpan laporan
- [x] Setelah simpan laporan, status assignment otomatis update

---

## 🔧 Troubleshooting

### Issue: Notifikasi tidak muncul
**Solusi:**
```bash
php artisan migrate # Pastikan tabel notifications ada
php artisan cache:clear
```

### Issue: Lokasi tidak auto-fill
**Solusi:**
- Pastikan field `lokasi_tujuan` sudah ada di tabel assignments
- Check JavaScript console untuk error
- Pastikan API endpoint `/karyawan/api/assignments/{id}` berjalan

### Issue: Status tidak update otomatis
**Solusi:**
- Check Report Model booted() method
- Pastikan accessor `total_dikirim` berfungsi
- Clear cache: `php artisan cache:clear`

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan check:
- Model files di `app/Models/`
- Controller files di `app/Http/Controllers/`
- View files di `resources/views/karyawan/`
- Migration files di `database/migrations/`

---

**Dibuat pada:** 11 November 2025
**Status:** ✅ Semua fitur terkoneksi dan berfungsi
