## EPIC 2 – Sistem Pembelajaran
**PBI:** PBI-4 (Dashboard Progres) · PBI-5 (Akses Materi & Video) · PBI-6 (Kuis Evaluasi)


## BACKEND (BE)
### Database Migrations
```
database/migrations/2026_04_16_200007_create_enrollments_table.php
database/migrations/2026_04_16_200008_create_module_progress_table.php
database/migrations/2026_04_16_200005_create_quizzes_table.php
database/migrations/2026_04_16_200006_create_quiz_questions_table.php
database/migrations/2026_04_16_200009_create_quiz_attempts_table.php
```
> Enrollment: `user_id`, `course_id`, `progress` (0–100), `completed_at`.  
> ModuleProgress: `user_id`, `module_id`, `completed_at`.  
> Quiz: `module_id`, `title`, `pass_score`.  
> QuizQuestion: `quiz_id`, `question`, `options` (JSON), `correct_answer`.  
> QuizAttempt: `user_id`, `quiz_id`, `score`, `answers` (JSON), `passed`.

### Models
```
app/Models/Enrollment.php       ← belongsTo User, belongsTo Course
app/Models/ModuleProgress.php   ← belongsTo User, belongsTo Module
app/Models/Quiz.php             ← belongsTo Module, hasMany QuizQuestion, hasMany QuizAttempt
app/Models/QuizQuestion.php     ← belongsTo Quiz
app/Models/QuizAttempt.php      ← belongsTo User, belongsTo Quiz
```

### Controllers
```
app/Http/Controllers/DashboardController.php
    index()   → tampilkan kursus aktif user + % progres masing-masing

app/Http/Controllers/EnrollmentController.php
    store()   → daftar/enroll ke kursus (cek sudah bayar atau gratis)

app/Http/Controllers/ModuleController.php
    learn()    → tampilkan konten modul (teks/video)
    complete() → tandai modul selesai, update progres enrollment

app/Http/Controllers/QuizController.php
    show()    → tampilkan soal kuis
    submit()  → hitung skor, simpan QuizAttempt

app/Http/Controllers/MyCoursesController.php
    index()   → daftar semua kursus yang diikuti user

app/Http/Controllers/Api/QuizApiController.php
    submit()  → submit jawaban via AJAX
```

## FRONTEND (FE)
### Views
```
resources/views/dashboard.blade.php            ← ringkasan progres + kursus aktif
resources/views/courses/learn.blade.php        ← video player + konten teks modul
resources/views/courses/quiz.blade.php         ← form kuis pilihan ganda
resources/views/my-courses/index.blade.php     ← list semua kursus yang diikuti
```

### Routes (tambahkan di `routes/web.php`, dalam grup `auth`)
```php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('my-courses.index');
Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
Route::get('/courses/{course}/learn/{module}', [ModuleController::class, 'learn'])->name('courses.learn');
Route::post('/courses/{course}/learn/{module}/complete', [ModuleController::class, 'complete'])->name('courses.module.complete');
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
```

### Checklist PBI
- [ ] PBI-4: Dashboard menampilkan daftar kursus aktif + progress bar persentase
- [ ] PBI-5: Modul teks tampil di halaman, video embed/player berfungsi, tombol "Tandai Selesai"
- [ ] PBI-6: Kuis tampil setelah modul, submit menampilkan skor & status lulus/tidak
