# EPIC 1 – Eksplorasi Kursus

**PBI:** PBI-1 (Katalog Kursus) · PBI-2 (Detail & Silabus) · PBI-3 (Pencarian & Filter)

---

## PBI-1 – Katalog Kursus

### BE

**Migrations**
```
database/migrations/2026_04_16_200003_create_courses_table.php
database/migrations/2026_04_16_200004_create_modules_table.php
```

**Models**
```
app/Models/Course.php   ← belongsTo Tutor, hasMany Module, hasMany Enrollment
app/Models/Module.php   ← belongsTo Course, hasMany ModuleProgress
```

**Controllers**
```
app/Http/Controllers/CourseController.php
    index()   → tampilkan grid/list kursus yang sudah published
```

### FE

**Views**
```
resources/views/courses/index.blade.php   ← grid kursus: gambar, judul, nama tutor, harga
resources/views/landing.blade.php         ← seksi featured courses
```

**Routes** (`routes/web.php`)
```php
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
```

### Checklist
- [ ] Grid/list kursus tampil dengan gambar & nama tutor

---

## PBI-2 – Detail & Silabus

### BE

**Controllers**
```
app/Http/Controllers/CourseController.php
    show()   → detail kursus + silabus + daftar modul

app/Http/Controllers/Api/CourseApiController.php
    show()   → JSON detail kursus
```

### FE

**Views**
```
resources/views/courses/show.blade.php   ← detail, deskripsi, silabus accordion, harga, tombol enroll
```

**Routes** (`routes/web.php`)
```php
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
```

**Routes** (`routes/api.php`)
```php
Route::get('/courses/{course}', [CourseApiController::class, 'show']);
```

### Checklist
- [ ] Halaman detail menampilkan deskripsi, silabus accordion, harga, tombol daftar

---

## PBI-3 – Pencarian & Filter

### BE

**Models**
```
app/Models/Course.php   ← tambahkan scope:
    published()
    search($query)
    filterByCategory($cat)
    filterByPrice($min, $max)
```

**Controllers**
```
app/Http/Controllers/CourseController.php
    index()   → handle query params: search, kategori, harga min/max

app/Http/Controllers/Api/CourseApiController.php
    index()   → JSON list kursus (untuk filter dinamis/AJAX)
```

**Requests**
```
(tidak ada form input khusus — hanya GET query params)
```

### FE

**Views**
```
resources/views/courses/index.blade.php   ← tambahkan: form search + filter dropdown kategori + input harga
```

**Routes** (`routes/api.php`)
```php
Route::get('/courses', [CourseApiController::class, 'index']);
```

### Checklist
- [ ] Input search live (atau submit form) + filter dropdown kategori + slider/input harga
