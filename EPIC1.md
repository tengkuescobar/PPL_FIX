## EPIC 1 – Eksplorasi Kursus
**PBI:** PBI-1 (Katalog Kursus) · PBI-2 (Detail & Silabus) · PBI-3 (Pencarian & Filter)

## BACKEND (BE)
### Database Migrations
```
database/migrations/2026_04_16_200003_create_courses_table.php

database/migrations/2026_04_16_200004_create_modules_table.php
```

### Models
```
app/Models/Course.php        ← relasi: belongsTo Tutor, hasMany Module, hasMany Enrollment
app/Models/Module.php        ← relasi: belongsTo Course, hasMany ModuleProgress
```
Tambahkan scope `published()`, `search($query)`, `filterByCategory($cat)`, `filterByPrice($min,$max)` di `Course.php`.

### Controllers
```
app/Http/Controllers/CourseController.php
    index()   → tampilkan katalog + handle query search & filter
    show()    → detail kursus + silabus + daftar modul

app/Http/Controllers/Api/CourseApiController.php
    index()   → JSON list kursus (untuk filter dinamis/AJAX)
    show()    → JSON detail kursus
```

### Requests
```
(tidak ada form input khusus — hanya GET query params)
```

## FRONTEND (FE)
### Views
```
resources/views/courses/index.blade.php   ← katalog + form search + filter kategori/harga
resources/views/courses/show.blade.php    ← detail, deskripsi, silabus, tombol enroll
resources/views/landing.blade.php         ← seksi featured courses (sudah ada, perlu dilengkapi)
```

### Routes (tambahkan di `routes/web.php`)
```php
// Public
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
```
```php
// API (routes/api.php)
Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/courses/{course}', [CourseApiController::class, 'show']);
```

### Checklist PBI
- [ ] PBI-1: Grid/list kursus tampil dengan gambar & nama tutor
- [ ] PBI-2: Halaman detail menampilkan deskripsi, silabus accordion, harga, tombol daftar
- [ ] PBI-3: Input search live (atau submit form) + filter dropdown kategori + slider/input harga

---