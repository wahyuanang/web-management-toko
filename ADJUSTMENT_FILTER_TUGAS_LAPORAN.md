# 📝 Adjustment: Filter Tugas di Buat Laporan

**Tanggal:** 11 November 2025  
**Status:** ✅ SELESAI

---

## 📋 Request Adjustment

> "Di role karyawan bagian buat laporan baru, label pilih tugas itu tugasnya hanya update tugas yang belum diantar saja. Ketika sudah diantar semuanya, maka selectnya tidak ada."

---

## ✅ Solusi yang Diimplementasikan

### **Problem:**
- Dropdown "Pilih Tugas" menampilkan SEMUA tugas (termasuk yang sudah selesai)
- Karyawan bingung karena bisa memilih tugas yang sudah done
- Tidak ada indikasi visual ketika semua tugas sudah selesai

### **Solution:**

#### **1. Filter Dropdown "Pilih Tugas"** ✅
- Hanya tampilkan assignment dengan **status ≠ 'done'**
- Tugas yang sudah selesai diantar **tidak muncul** di dropdown
- Helper text: "Hanya menampilkan tugas yang belum selesai diantar"

#### **2. Empty State UI** ✅
- Ketika tidak ada tugas aktif → tampilkan alert informasi
- Pesan: "Tidak Ada Tugas Aktif"
- Button: "Lihat Daftar Tugas" untuk navigasi cepat
- Form tidak ditampilkan jika tidak ada tugas

#### **3. Special Case di Edit** ✅
- Di halaman edit, assignment yang sedang di-edit tetap muncul
- Meskipun statusnya sudah 'done', tetap bisa di-edit
- Ini untuk backward compatibility

---

## 🔄 Alur Kerja Baru

### **Scenario 1: Ada Tugas Aktif**

```
Karyawan buka "Buat Laporan Baru"
   ↓
Dropdown "Pilih Tugas" menampilkan:
   ✅ Tugas A (Status: pending)
   ✅ Tugas B (Status: in_progress)
   ❌ Tugas C (Status: done) → TIDAK MUNCUL
   ↓
Karyawan pilih tugas dan buat laporan
```

---

### **Scenario 2: Semua Tugas Selesai**

```
Karyawan buka "Buat Laporan Baru"
   ↓
Tidak ada tugas aktif (semua status = 'done')
   ↓
Tampilkan Alert:
   "Tidak Ada Tugas Aktif"
   "Semua tugas sudah selesai diantar"
   ↓
Form tidak ditampilkan
Button: "Lihat Daftar Tugas"
```

---

### **Scenario 3: Edit Laporan**

```
Karyawan buka "Edit Laporan"
   ↓
Dropdown "Pilih Tugas" menampilkan:
   ✅ Assignment yang sedang di-edit (meskipun done)
   ✅ Tugas lain yang status ≠ 'done'
   ↓
Karyawan bisa edit assignment
```

---

## 📊 Implementasi Detail

### **1. ReportController - create() Method**

**File:** `app/Http/Controllers/Karyawan/ReportController.php`

```php
public function create()
{
    // Filter: hanya tampilkan assignment yang belum selesai
    $assignments = Assignment::where('assigned_to', Auth::id())
        ->where('status', '!=', 'done')
        ->orderBy('title')
        ->get();

    return view('karyawan.reports.create', compact('assignments'));
}
```

**Logic:**
- Query assignment milik user login
- Filter: `status != 'done'`
- Order by title (ascending)

---

### **2. ReportController - edit() Method**

**File:** `app/Http/Controllers/Karyawan/ReportController.php`

```php
public function edit(Report $report)
{
    // Filter: assignment yang belum selesai
    // ATAU assignment yang sedang di-edit
    $assignments = Assignment::where('assigned_to', Auth::id())
        ->where(function($query) use ($report) {
            $query->where('status', '!=', 'done')
                  ->orWhere('id', $report->assignment_id);
        })
        ->orderBy('title')
        ->get();

    return view('karyawan.reports.edit', compact('report', 'assignments'));
}
```

**Logic:**
- Query assignment milik user login
- Filter: `status != 'done'` **ATAU** `id = report.assignment_id`
- Ini memastikan assignment yang sedang di-edit tetap muncul

---

### **3. View - create.blade.php (Empty State)**

**File:** `resources/views/karyawan/reports/create.blade.php`

```blade
@if($assignments->count() == 0)
    <!-- No Active Assignments Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-yellow-800">Tidak Ada Tugas Aktif</h3>
                <p class="mt-2 text-yellow-700">
                    Saat ini tidak ada tugas yang dapat dilaporkan. 
                    Semua tugas yang ditugaskan kepada Anda sudah selesai diantar.
                </p>
                <div class="mt-4">
                    <a href="{{ route('karyawan.assignments.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg">
                        <i class="fas fa-clipboard-list mr-2"></i>Lihat Daftar Tugas
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Form ditampilkan -->
@endif
```

---

### **4. View - Helper Text**

**File:** `resources/views/karyawan/reports/create.blade.php` & `edit.blade.php`

```blade
<select name="assignment_id" id="assignment_id">
    <option value="">-- Pilih Tugas --</option>
    @foreach($assignments as $assignment)
        <option value="{{ $assignment->id }}">
            {{ $assignment->title }} (Target: {{ $assignment->qty_target }} unit)
        </option>
    @endforeach
</select>
<p class="mt-1 text-xs text-gray-500">
    <i class="fas fa-info-circle"></i> Hanya menampilkan tugas yang belum selesai diantar
</p>
```

---

## 📸 UI Changes

### **BEFORE:**

```
┌─────────────────────────────────────────┐
│ Buat Laporan Baru                       │
├─────────────────────────────────────────┤
│ Pilih Tugas *                           │
│ ┌─────────────────────────────────────┐ │
│ │ -- Pilih Tugas --                   │ │
│ │ Pengantaran Tahu (40 unit)          │ │ ← Status: done
│ │ Pengantaran Tempe (25 unit)         │ │ ← Status: done
│ │ Tempe (10 unit)                     │ │ ← Status: in_progress
│ └─────────────────────────────────────┘ │
└─────────────────────────────────────────┘
```

**Problem:**
- ❌ Tugas selesai masih muncul
- ❌ Karyawan bisa pilih tugas yang sudah done
- ❌ Tidak ada informasi tugas mana yang aktif

---

### **AFTER:**

**Case 1: Ada Tugas Aktif**

```
┌─────────────────────────────────────────┐
│ Buat Laporan Baru                       │
├─────────────────────────────────────────┤
│ Pilih Tugas *                           │
│ ┌─────────────────────────────────────┐ │
│ │ -- Pilih Tugas --                   │ │
│ │ Tempe (10 unit)                     │ │ ← Only active tasks
│ └─────────────────────────────────────┘ │
│ ℹ️ Hanya menampilkan tugas yang belum   │
│    selesai diantar                      │
└─────────────────────────────────────────┘
```

**Case 2: Tidak Ada Tugas Aktif**

```
┌─────────────────────────────────────────┐
│ Buat Laporan Baru                       │
├─────────────────────────────────────────┤
│ ⚠️  Tidak Ada Tugas Aktif               │
│                                         │
│ Saat ini tidak ada tugas yang dapat    │
│ dilaporkan. Semua tugas yang ditugaskan│
│ kepada Anda sudah selesai diantar.     │
│                                         │
│ [📋 Lihat Daftar Tugas]                 │
└─────────────────────────────────────────┘
```

---

## 🎯 Business Logic

### **Assignment Status Flow:**

```
PENDING → IN_PROGRESS → DONE
   ↓          ↓          ↓
  ✅ Show   ✅ Show   ❌ Hide
```

### **Filter Logic:**

```php
// Create Form
WHERE status != 'done'

// Edit Form (Special Case)
WHERE status != 'done' OR id = current_assignment_id
```

---

## 📁 File Changes Summary

### **Modified Files (3):**

1. **`app/Http/Controllers/Karyawan/ReportController.php`**
   - ✅ `create()`: Filter `where('status', '!=', 'done')`
   - ✅ `edit()`: Filter dengan OR condition untuk maintain selected assignment
   - Baris: 42-49, 99-112

2. **`resources/views/karyawan/reports/create.blade.php`**
   - ✅ Empty state alert ketika tidak ada tugas aktif
   - ✅ Helper text "Hanya menampilkan tugas yang belum selesai diantar"
   - ✅ Conditional rendering: `@if($assignments->count() == 0)`
   - ✅ Fix JavaScript: check if element exists before addEventListener
   - Baris: 7-32, 56-59, 217-221

3. **`resources/views/karyawan/reports/edit.blade.php`**
   - ✅ Helper text "Hanya menampilkan tugas yang belum selesai diantar"
   - Baris: 45-48

---

## 🧪 Testing Scenarios

### **Test 1: Buat Laporan - Ada Tugas Aktif**

**Steps:**
1. Login karyawan
2. Buka "Buat Laporan Baru"
3. Cek dropdown "Pilih Tugas"

**Expected Result:**
- ✅ Hanya tugas dengan status `pending` atau `in_progress` muncul
- ✅ Tugas dengan status `done` TIDAK muncul
- ✅ Helper text ditampilkan
- ✅ Form bisa diisi dan submit

---

### **Test 2: Buat Laporan - Tidak Ada Tugas Aktif**

**Steps:**
1. Login karyawan (yang semua tugasnya done)
2. Buka "Buat Laporan Baru"

**Expected Result:**
- ✅ Alert "Tidak Ada Tugas Aktif" ditampilkan
- ✅ Form TIDAK ditampilkan
- ✅ Button "Lihat Daftar Tugas" ada
- ✅ Klik button → redirect ke assignments index

---

### **Test 3: Edit Laporan - Assignment Sudah Done**

**Scenario:**
- Ada laporan lama dengan assignment yang sekarang sudah done

**Steps:**
1. Login karyawan
2. Buka "Edit Laporan" (assignment-nya done)
3. Cek dropdown "Pilih Tugas"

**Expected Result:**
- ✅ Assignment yang sedang di-edit TETAP muncul (meskipun done)
- ✅ Assignment lain yang active juga muncul
- ✅ Bisa edit dan update laporan

---

### **Test 4: Transisi Status**

**Steps:**
1. Admin buat assignment A (target: 50 unit)
2. Karyawan buka "Buat Laporan Baru"
   - ✅ Assignment A muncul di dropdown
3. Karyawan buat laporan: 50 unit
   - Status assignment → 'done'
4. Karyawan buka "Buat Laporan Baru" lagi
   - ✅ Assignment A TIDAK muncul di dropdown
5. Karyawan buka "Edit Laporan" (laporan tadi)
   - ✅ Assignment A TETAP muncul (untuk edit)

---

## 💡 Benefits & Impact

### **User Experience (Karyawan):**
- ✅ Dropdown lebih clean - hanya tugas yang relevan
- ✅ Tidak bingung memilih tugas yang sudah selesai
- ✅ Informasi jelas ketika tidak ada tugas
- ✅ Navigation cepat ke daftar tugas

### **Data Integrity:**
- ✅ Prevent duplicate reports untuk tugas done
- ✅ Logic konsisten dengan status assignment
- ✅ Edit laporan lama tetap bisa dilakukan

### **System Flow:**
- ✅ Konsisten dengan dashboard (filter yang sama)
- ✅ Sync dengan auto-update status di Report model
- ✅ Clear separation: tugas aktif vs selesai

---

## 🔄 Integration dengan Fitur Lain

### **Terkait dengan:**

1. **Dashboard Filter** (ADJUSTMENT sebelumnya)
   - Sama-sama filter assignment dengan `status != 'done'`
   - Konsisten di semua halaman karyawan

2. **Auto-update Status** (Report Model)
   - Ketika total delivery ≥ target → status = 'done'
   - Assignment otomatis hilang dari dropdown create

3. **Notification Filter** (ADJUSTMENT sebelumnya)
   - Notifikasi assignment done hilang
   - Dropdown assignment done juga hilang
   - Full sync behaviour

---

## 🚀 Deployment

### **No Migration Required ✅**
- Hanya perubahan logic & view
- Tidak ada perubahan database schema

### **Deployment Steps:**

```bash
# 1. Pull code
git pull origin main

# 2. Clear cache (opsional)
php artisan view:clear

# 3. Test
# - Login karyawan
# - Buka "Buat Laporan Baru"
# - Verifikasi filter berjalan
```

---

## 🔒 Backward Compatibility

### ✅ **FULLY COMPATIBLE**

**Data Existing:**
- Laporan lama tidak terpengaruh
- Assignment lama tetap bisa di-edit
- Tidak ada data yang hilang

**User Behavior:**
- Karyawan: Experience lebih baik (filter otomatis)
- Admin: Tidak ada perubahan

---

## 📌 Key Points

1. **Filter di Create:**
   - Query: `where('status', '!=', 'done')`
   - Result: Hanya tugas aktif yang muncul

2. **Special Case di Edit:**
   - Query: `where('status', '!=', 'done') OR id = current_id`
   - Result: Assignment being edited tetap muncul

3. **Empty State:**
   - UI alert ketika tidak ada tugas
   - CTA: Navigate ke assignments index

4. **Helper Text:**
   - Informasi jelas untuk user
   - "Hanya menampilkan tugas yang belum selesai diantar"

---

## 📞 Support & Troubleshooting

### **Issue: Dropdown Kosong Padahal Ada Tugas**

**Solusi:**
1. Cek status assignment: `SELECT status FROM assignments WHERE assigned_to = X`
2. Pastikan ada assignment dengan status ≠ 'done'
3. Clear cache: `php artisan cache:clear`

---

### **Issue: Assignment yang Sudah Done Masih Muncul**

**Solusi:**
1. Cek query di ReportController
2. Pastikan filter `where('status', '!=', 'done')` ada
3. Verify status di database

---

### **Issue: Edit Laporan - Assignment Tidak Muncul**

**Solusi:**
1. Check OR condition di edit() method
2. Pastikan `orWhere('id', $report->assignment_id)` ada
3. Verify report.assignment_id valid

---

## 🎨 UI Components

### **Empty State Alert:**
- Background: Yellow (warning)
- Icon: Exclamation triangle
- Message: Clear & actionable
- CTA: "Lihat Daftar Tugas"

### **Helper Text:**
- Color: Gray-500
- Icon: Info circle
- Position: Below select dropdown
- Text: "Hanya menampilkan tugas yang belum selesai diantar"

---

## 🔗 Related Adjustments

1. **ADJUSTMENT_NOTIFIKASI_DAN_TUGAS.md**
   - Filter notifikasi (status != done)
   - Filter tugas terbaru (status != done)
   - Same logic, different context

2. **UPDATE_NOVEMBER_11_2025.md**
   - Status auto-update ketika delivery complete
   - Integration dengan Report model

---

**Status:** ✅ PRODUCTION READY  
**Last Updated:** 11 November 2025, 14:00 WIB  
**Version:** 1.0.0  
**No Breaking Changes** ✅
