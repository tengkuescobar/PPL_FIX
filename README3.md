# 📋 RENCANA KERJA TIM — Learn Everything (Per Epic & PBI)

## Daftar Isi
- [Setup Awal Project di GitHub](#-setup-awal-project-di-github)
- [Yang TIDAK Masuk GitHub](#-yang-tidak-masuk-github-otomatis-diabaikan-gitignore)
- [Setup Local Tiap Anggota](#-setup-local-tiap-anggota-setelah-clone)
- [Shared Foundation Files](#-shared-foundation-files-sudah-ada-jangan-dihapus)
- [Peta PBI Per Epic](#-peta-pbi-per-epic)
  - [EPIC 1 – Eksplorasi Kursus (PBI 1-3)](#epic-1--eksplorasi-kursus)
  - [EPIC 2 – Sistem Pembelajaran (PBI 4-6)](#epic-2--sistem-pembelajaran)
  - [EPIC 3 – Layanan Tutor & Home Visit (PBI 7-9)](#epic-3--layanan-tutor--home-visit)
  - [EPIC 4 – Komunikasi & Komunitas (PBI 10-12)](#epic-4--komunikasi--komunitas)
  - [EPIC 5 – Marketplace Keterampilan (PBI 13-15)](#epic-5--marketplace-keterampilan)
  - [EPIC 6 – Transaksi & Pembayaran (PBI 16-18)](#epic-6--transaksi--pembayaran)
  - [EPIC 7 – Manajemen Profil Pengguna (PBI 19-21)](#epic-7--manajemen-profil-pengguna)
- [Dependency Antar PBI](#-dependency-antar-pbi)
- [Git Workflow Per PBI](#-git-workflow-per-pbi)
- [Checklist Sebelum Push](#-checklist-sebelum-push)
- [Troubleshooting](#-troubleshooting)

---

## 🚀 Setup Awal Project di GitHub

### Langkah 1: Satu Orang (Lead) Push Skeleton Project

Lead project push **base project kosong** yang berisi:
- File konfigurasi Laravel (sudah ada dari `composer create-project`)
- File `.gitignore` (sudah ada)
- `composer.json` & `composer.lock`
- `package.json` & `package-lock.json`
- Folder kosong dengan `.gitkeep`

```bash
# Lead membuat repo di GitHub, lalu:
cd c:\xampp\htdocs\1try_ppl\1try_ppl
git init
git remote add origin https://github.com/<username>/<repo-name>.git
git add .
git commit -m "Initial commit: Laravel 12 skeleton"
git branch -M main
git push -u origin main
```

### Langkah 2: Anggota Lain Clone

```bash
cd c:\xampp\htdocs
git clone https://github.com/<username>/<repo-name>.git 1try_ppl
cd 1try_ppl
```

---

## 🚫 Yang TIDAK Masuk GitHub (Otomatis Diabaikan `.gitignore`)

| Folder/File | Kenapa | Cara Dapat di Local |
|---|---|---|
| `vendor/` | Dependency PHP (100MB+) | `composer install` |
| `node_modules/` | Dependency NPM (200MB+) | `npm install` |
| `public/build/` | Hasil compile Vite | `npm run dev` atau `npm run build` |
| `public/hot` | Vite hot reload marker | `npm run dev` |
| `public/storage` | Symlink ke storage | `php artisan storage:link` |
| `.env` | Kredensial database | Copy dari `.env.example`, edit manual |
| `storage/*.key` | Encryption key | `php artisan key:generate` |
| `*.log` | Log file | Auto-generate |

**Kesimpulan:** Yang di-push ke GitHub hanya **source code** (PHP, Blade, JS, CSS, migrations, config). Dependency & build output TIDAK di-push.

---

## 💻 Setup Local Tiap Anggota (Setelah Clone)

```bash
# 1. Install dependency PHP
composer install

# 2. Install dependency NPM
npm install

# 3. Copy environment file
copy .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Edit .env → sesuaikan database
# DB_DATABASE=learn_everything
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Buat database di phpMyAdmin: learn_everything

# 7. Run migration + seed
php artisan migrate:fresh --seed

# 8. Buat symlink storage
php artisan storage:link

# 9. Jalankan server (2 terminal)
# Terminal 1:
php artisan serve
# Terminal 2:
npm run dev
```

**PENTING:** Langkah 1-9 di atas **HANYA JALAN DI LOCAL** (terminal laptop/PC masing-masing). GitHub hanya menyimpan source code, bukan menjalankan perintah.

---

## 🏗️ Shared Foundation Files (SUDAH ADA, JANGAN DIHAPUS)

File-file ini adalah **pondasi project** yang dipakai SEMUA PBI. Jangan dihapus/overwrite sembarangan.

### Konfigurasi Project
| File | Fungsi |
|---|---|
| `composer.json` | Dependency PHP (Laravel, Breeze, Sanctum) |
| `package.json` | Dependency NPM (Vite, Tailwind, Alpine.js) |
| `vite.config.js` | Config bundler Vite |
| `tailwind.config.js` | Config Tailwind CSS |
| `postcss.config.js` | Config PostCSS |
| `.env.example` | Template environment variables |
| `.gitignore` | File yang diabaikan Git |
| `phpunit.xml` | Config testing |

### Bootstrap & Config
| File | Fungsi |
|---|---|
| `bootstrap/app.php` | Register routes, middleware alias `role` |
| `bootstrap/providers.php` | Service providers |
| `config/*.php` (11 file) | Konfigurasi Laravel |

### Auth System (dari Laravel Breeze)
| File | Fungsi |
|---|---|
| `routes/auth.php` | Route login, register, logout, password reset |
| `app/Http/Controllers/Auth/*` (8 file) | Controller authentication |
| `resources/views/auth/*` (6 file) | View login, register, dll |
| `resources/views/layouts/guest.blade.php` | Layout halaman guest/auth |

### Core System
| File | Fungsi |
|---|---|
| `app/Models/User.php` | Model utama user (HasApiTokens, role, relationships) |
| `app/Http/Middleware/RoleMiddleware.php` | Cek role user (admin/tutor/user) |
| `resources/views/layouts/app.blade.php` | Layout utama aplikasi |
| `resources/views/layouts/navigation.blade.php` | Navigasi (tiap PBI yang tambah menu → edit file ini) |
| `resources/views/layouts/footer.blade.php` | Footer |
| `resources/views/components/*` (14 file) | UI components (button, input, modal, dll) |
| `resources/css/app.css` | Custom CSS + Tailwind directives |
| `resources/js/app.js` | Entry point JavaScript |
| `resources/js/bootstrap.js` | Axios config |
| `app/View/Components/AppLayout.php` | Layout component class |
| `app/View/Components/GuestLayout.php` | Guest layout component class |
| `app/Http/Controllers/Controller.php` | Base controller |

### Database Base
| File | Fungsi |
|---|---|
| `database/migrations/0001_01_01_000000_create_users_table.php` | Tabel users, password_resets, sessions |
| `database/migrations/0001_01_01_000001_create_cache_table.php` | Tabel cache |
| `database/migrations/0001_01_01_000002_create_jobs_table.php` | Tabel jobs |
| `database/migrations/2026_04_16_102719_create_personal_access_tokens_table.php` | Tabel Sanctum tokens |
| `database/migrations/2026_04_16_200001_create_admins_table.php` | Tabel admins |
| `database/seeders/DatabaseSeeder.php` | Seeder utama (tiap PBI yang butuh data dummy → tambah di sini) |
| `database/factories/UserFactory.php` | Factory untuk testing |

### API System
| File | Fungsi |
|---|---|
| `routes/api.php` | Route API (login, register, logout + endpoint per fitur) |
| `config/sanctum.php` | Config Sanctum API auth |

### Landing Page
| File | Fungsi |
|---|---|
| `resources/views/landing.blade.php` | Halaman utama sebelum login |
| `resources/views/welcome.blade.php` | Welcome page default Laravel |

---

## 🗺️ PETA PBI PER EPIC

---

## EPIC 1 – Eksplorasi Kursus

### 📌 PBI 1 – Halaman Katalog Kursus
> *Sebagai calon pelajar, saya ingin melihat katalog daftar kursus beserta gambar dan nama tutornya.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200002_create_tutors_table.php` | Tabel `tutors`: user_id, bio, skills (JSON), hourly_rate, rating, total_reviews, document, status |
| **Migration** | `database/migrations/2026_04_16_200003_create_courses_table.php` | Tabel `courses`: tutor_id (FK), title, slug, description, image, category, price, is_published |
| **Migration** | `database/migrations/2026_04_19_000002_add_status_to_tutors_table.php` | Tambah kolom `status` enum(pending,approved,rejected) ke tutors |
| **Model** | `app/Models/Tutor.php` | Fillable, casts (skills=array, rating=decimal), relasi: belongsTo(User), hasMany(Course) |
| **Model** | `app/Models/Course.php` | Fillable, casts (price=decimal, is_published=boolean), auto-generate slug, relasi: belongsTo(Tutor), hasMany(Module), hasMany(Enrollment) |
| **Controller** | `app/Http/Controllers/CourseController.php` | Method `index()`: query Course with tutor, where is_published=true, paginate(12). Return view |
| **View** | `resources/views/courses/index.blade.php` | Grid card kursus: gambar, judul, nama tutor, harga, rating. Link ke detail |
| **Route** | `routes/web.php` | `Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');` |
| **API Controller** | `app/Http/Controllers/Api/CourseApiController.php` | Method `index()`: return JSON paginated courses |
| **API Route** | `routes/api.php` | `Route::get('/courses', [CourseApiController::class, 'index']);` |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Kursus" ke `/courses` |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed 3 tutors + 4 courses dengan data dummy |

---

### 📌 PBI 2 – Detail & Silabus Kursus
> *Sebagai calon pelajar, saya ingin melihat detail deskripsi, silabus, dan harga saat mengklik sebuah kursus.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200004_create_modules_table.php` | Tabel `modules`: course_id (FK), title, content (text), video_url, order (integer) |
| **Model** | `app/Models/Module.php` | Fillable, relasi: belongsTo(Course), hasOne(Quiz), hasMany(ModuleProgress) |
| **Controller** | `app/Http/Controllers/CourseController.php` | Method `show(Course $course)`: load modules, tutor. Cek enrollment status jika login. Return view |
| **View** | `resources/views/courses/show.blade.php` | Detail kursus: deskripsi, harga, tutor info, daftar silabus (modul), tombol enroll/beli |
| **Route** | `routes/web.php` | `Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');` |
| **API Controller** | `app/Http/Controllers/Api/CourseApiController.php` | Method `show()`: return JSON course + modules + enrollment status |
| **API Route** | `routes/api.php` | `Route::get('/courses/{course}', [CourseApiController::class, 'show']);` |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed 3-4 modules per course |

**Dependensi:** PBI 1 (butuh Course model & migration sudah ada)

---

### 📌 PBI 3 – Fitur Pencarian & Filter
> *Sebagai calon pelajar, saya ingin menggunakan kolom pencarian dan filter kategori/harga.*

#### File yang Harus Dimodifikasi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Controller** | `app/Http/Controllers/CourseController.php` | Update `index()`: tambah filter `?search=`, `?category=`, `?min_price=`, `?max_price=`, `?sort=` dari request query |
| **View** | `resources/views/courses/index.blade.php` | Tambah form pencarian (input text), dropdown filter kategori, range harga, tombol filter. Tampilkan hasil yang ter-filter |
| **API Controller** | `app/Http/Controllers/Api/CourseApiController.php` | Update `index()`: support query params search, category, sort |

**Dependensi:** PBI 1 (halaman katalog harus ada dulu)

**Tidak perlu file baru** — hanya modifikasi file PBI 1.

---

## EPIC 2 – Sistem Pembelajaran

### 📌 PBI 4 – Dashboard Ringkasan Belajar
> *Sebagai pelajar terdaftar, saya ingin melihat daftar dan progres kursus yang sedang saya ikuti.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200007_create_enrollments_table.php` | Tabel `enrollments`: user_id (FK), course_id (FK), progress (integer default 0), is_completed (boolean), completed_at |
| **Model** | `app/Models/Enrollment.php` | Fillable, casts (is_completed=boolean, completed_at=datetime), relasi: belongsTo(User), belongsTo(Course), hasOne(Certificate) |
| **Controller** | `app/Http/Controllers/DashboardController.php` | Method `index()`: switch role → `userDashboard()` shows enrollments with progress, `tutorDashboard()`, `adminDashboard()`. Return view berdasarkan role |
| **View** | `resources/views/dashboard.blade.php` | Dashboard user: daftar kursus enrolled dengan progress bar (%), link "Lanjutkan Belajar" |
| **View** | `resources/views/admin/dashboard.blade.php` | Dashboard admin: total users, courses, tutors, revenue |
| **View** | `resources/views/tutor/dashboard.blade.php` | Dashboard tutor: total kursus, siswa, pendapatan |
| **Route** | `routes/web.php` | `Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');` |
| **API Controller** | `app/Http/Controllers/Api/ProfileApiController.php` | Method `dashboard()`: return JSON stats berdasarkan role |
| **API Route** | `routes/api.php` | `Route::get('/dashboard', [ProfileApiController::class, 'dashboard']);` |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed enrollments untuk beberapa student |

**Dependensi:** PBI 1 (Course model), Auth system (login)

---

### 📌 PBI 5 – Akses Materi & Pemutar Video
> *Sebagai pelajar terdaftar, saya ingin mengakses modul teks dan memutar video.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200008_create_module_progress_table.php` | Tabel `module_progress`: user_id (FK), module_id (FK), is_completed (boolean), completed_at |
| **Model** | `app/Models/ModuleProgress.php` | Fillable, table='module_progress', casts, relasi: belongsTo(User), belongsTo(Module) |
| **Controller** | `app/Http/Controllers/EnrollmentController.php` | Method `store()`: enroll user ke course, create Enrollment record |
| **Controller** | `app/Http/Controllers/ModuleController.php` | Method `learn()`: cek enrollment, load module + progress. Method `complete()`: mark module done, update enrollment progress % |
| **View** | `resources/views/courses/learn.blade.php` | Tampilan belajar: konten teks modul, embed video (iframe YouTube/URL), sidebar daftar modul dengan status ✅/⬜, tombol "Selesai" |
| **Route** | `routes/web.php` | `Route::post('/courses/{course}/enroll', ...)` dan `Route::get('/courses/{course}/learn/{module}', ...)` dan `Route::post('.../{module}/complete', ...)` |
| **API Controller** | `app/Http/Controllers/Api/CourseApiController.php` | Methods: `enroll()`, `learnModule()`, `completeModule()`, `myEnrollments()` |
| **API Route** | `routes/api.php` | Endpoint enroll, learn, complete, my/enrollments |

**Dependensi:** PBI 2 (Module model), PBI 4 (Enrollment model)

---

### 📌 PBI 6 – Kuis Evaluasi Singkat
> *Sebagai pelajar terdaftar, saya ingin mengerjakan kuis pilihan ganda di akhir modul.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200005_create_quizzes_table.php` | Tabel `quizzes`: module_id (FK), title, passing_score (integer) |
| **Migration** | `database/migrations/2026_04_16_200006_create_quiz_questions_table.php` | Tabel `quiz_questions`: quiz_id (FK), question (text), options (JSON array), correct_answer (string) |
| **Migration** | `database/migrations/2026_04_16_200009_create_quiz_attempts_table.php` | Tabel `quiz_attempts`: user_id (FK), quiz_id (FK), score, passed (boolean), answers (JSON) |
| **Model** | `app/Models/Quiz.php` | Fillable, relasi: belongsTo(Module), hasMany(QuizQuestion), hasMany(QuizAttempt) |
| **Model** | `app/Models/QuizQuestion.php` | Fillable, casts (options=array), relasi: belongsTo(Quiz) |
| **Model** | `app/Models/QuizAttempt.php` | Fillable, casts (passed=boolean, answers=array), relasi: belongsTo(User), belongsTo(Quiz) |
| **Controller** | `app/Http/Controllers/QuizController.php` | Method `show()`: tampilkan soal (tanpa jawaban). Method `submit()`: hitung score, create QuizAttempt, return hasil |
| **View** | `resources/views/courses/quiz.blade.php` | Form kuis: pertanyaan, radio button pilihan ganda, tombol submit. Hasil: score, passed/failed |
| **Route** | `routes/web.php` | `Route::get('/quizzes/{quiz}', ...)` dan `Route::post('/quizzes/{quiz}/submit', ...)` |
| **API Controller** | `app/Http/Controllers/Api/QuizApiController.php` | Methods: `show()` (soal tanpa jawaban), `submit()` (auto-score) |
| **API Route** | `routes/api.php` | Endpoint quiz show & submit |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed quiz + 5 questions per module |

**Dependensi:** PBI 2 (Module model), PBI 5 (Enrollment check)

---

## EPIC 3 – Layanan Tutor & Home Visit

### 📌 PBI 7 – Daftar & Profil Tutor
> *Sebagai pengguna, saya ingin melihat daftar profil, kualifikasi, dan spesialisasi tutor.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Controller** | `app/Http/Controllers/TutorController.php` | Method `index()`: list approved tutors, searchable. Method `show()`: detail tutor + courses + reviews |
| **View** | `resources/views/tutors/index.blade.php` | Grid tutor: foto, nama, skill tags, rating bintang, link ke profil |
| **View** | `resources/views/tutors/show.blade.php` | Detail tutor: bio, skills, hourly_rate, rating, daftar kursus, review list, tombol booking |
| **Route** | `routes/web.php` | `Route::get('/tutors', ...)` dan `Route::get('/tutors/{tutor}', ...)` |
| **API Controller** | `app/Http/Controllers/Api/TutorApiController.php` | Methods: `index()`, `show()` |
| **API Route** | `routes/api.php` | Endpoint tutors list & detail |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Tutor" ke `/tutors` |

**Dependensi:** PBI 1 (Tutor model & migration)

---

### 📌 PBI 8 – Form Pemesanan Home Visit
> *Sebagai pelajar terdaftar, saya ingin memesan jadwal untuk layanan Home Visit.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200019_create_home_visit_bookings_table.php` | Tabel `home_visit_bookings`: user_id (FK), tutor_id (FK), date, time, location, status (enum: pending/confirmed/completed/cancelled), notes |
| **Model** | `app/Models/HomeVisitBooking.php` | Fillable, casts (date=date), relasi: belongsTo(User), belongsTo(Tutor) |
| **Controller** | `app/Http/Controllers/BookingController.php` | Method `create()`: form booking. Method `store()`: validasi & simpan. Method `updateStatus()`: tutor confirm/complete, user cancel (dengan authorization check) |
| **View** | `resources/views/tutors/booking.blade.php` | Form: pilih tanggal, jam, lokasi, catatan. Tombol submit |
| **Route** | `routes/web.php` | `Route::get('/tutors/{tutor}/book', ...)`, `Route::post('/bookings', ...)`, `Route::patch('/bookings/{booking}/status', ...)` |
| **API Controller** | `app/Http/Controllers/Api/TutorApiController.php` | Methods: `book()`, `myBookings()`, `updateBookingStatus()` |
| **API Route** | `routes/api.php` | Endpoint book, my/bookings, bookings/{id}/status |

**Dependensi:** PBI 7 (Tutor profil page, tombol booking ada di sana)

---

### 📌 PBI 9 – Penilaian & Ulasan (Rating) Tutor
> *Sebagai pelajar terdaftar, saya ingin memberikan rating bintang dan ulasan setelah sesi belajar.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200020_create_tutor_reviews_table.php` | Tabel `tutor_reviews`: user_id (FK), tutor_id (FK), rating (integer 1-5), comment (text nullable) |
| **Model** | `app/Models/TutorReview.php` | Fillable, relasi: belongsTo(User), belongsTo(Tutor) |
| **Model** | `app/Models/Tutor.php` | Tambah method `updateRating()`: hitung avg rating & total_reviews dari reviews, simpan ke tutor |
| **Controller** | `app/Http/Controllers/TutorReviewController.php` | Method `store()`: validasi, updateOrCreate review, panggil tutor->updateRating() |
| **View** | `resources/views/tutors/show.blade.php` | Tambah: form rating (bintang 1-5 + textarea), daftar review dari user lain |
| **Route** | `routes/web.php` | `Route::post('/tutors/{tutor}/reviews', ...)` |
| **API Controller** | `app/Http/Controllers/Api/TutorApiController.php` | Method: `storeReview()` |
| **API Route** | `routes/api.php` | `Route::post('/tutors/{tutor}/reviews', ...)` |

**Dependensi:** PBI 7 (halaman profil tutor)

---

## EPIC 4 – Komunikasi & Komunitas

### 📌 PBI 10 – Pembuatan Topik Diskusi
> *Sebagai pelajar terdaftar, saya ingin membuat thread pertanyaan di forum komunitas.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200010_create_forum_threads_table.php` | Tabel `forum_threads`: user_id (FK), title, content (text) |
| **Model** | `app/Models/ForumThread.php` | Fillable, relasi: belongsTo(User), hasMany(ForumReply) |
| **Controller** | `app/Http/Controllers/ForumThreadController.php` | Method `index()`: list threads with reply count. Method `show()`: detail thread + replies. Method `create()`: form buat thread. Method `store()`: validasi & simpan |
| **View** | `resources/views/forum/index.blade.php` | List thread: judul, author, jumlah reply, tanggal. Tombol "Buat Topik" |
| **View** | `resources/views/forum/show.blade.php` | Detail thread + daftar reply + form reply |
| **View** | `resources/views/forum/create.blade.php` | Form: judul thread + isi konten. Tombol submit |
| **Route** | `routes/web.php` | `Route::get('/forum', ...)`, `Route::get('/forum/{thread}', ...)`, `Route::get('/forum-create', ...)`, `Route::post('/forum', ...)` |
| **API Controller** | `app/Http/Controllers/Api/ForumApiController.php` | Methods: `index()`, `show()`, `store()` |
| **API Route** | `routes/api.php` | Endpoint forum list, detail, create |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Forum" ke `/forum` |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed 4 forum threads |

---

### 📌 PBI 11 – Interaksi Forum (Reply & Like)
> *Sebagai pelajar terdaftar, saya ingin bisa membalas dan menyukai thread pengguna lain.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200011_create_forum_replies_table.php` | Tabel `forum_replies`: forum_thread_id (FK), user_id (FK), content (text) |
| **Migration** | `database/migrations/2026_04_16_200012_create_forum_likes_table.php` | Tabel `forum_likes`: user_id (FK), forum_reply_id (FK) |
| **Model** | `app/Models/ForumReply.php` | Fillable, relasi: belongsTo(ForumThread), belongsTo(User), hasMany(ForumLike). Method `isLikedBy(User)` |
| **Model** | `app/Models/ForumLike.php` | Fillable, relasi: belongsTo(User), belongsTo(ForumReply) |
| **Controller** | `app/Http/Controllers/ForumReplyController.php` | Method `store()`: validasi & simpan reply. Method `toggleLike()`: toggle like/unlike |
| **View** | `resources/views/forum/show.blade.php` | Update: form reply di bawah thread, tombol like (toggle) dengan jumlah, tampilan reply list |
| **Route** | `routes/web.php` | `Route::post('/forum/{thread}/replies', ...)`, `Route::post('/forum/replies/{reply}/like', ...)` |
| **API Controller** | `app/Http/Controllers/Api/ForumApiController.php` | Methods: `reply()`, `toggleLike()` |
| **API Route** | `routes/api.php` | Endpoint reply & toggle like |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed replies + likes |

**Dependensi:** PBI 10 (Forum thread harus ada dulu)

---

### 📌 PBI 12 – Live Chat Konsultasi
> *Sebagai pelajar terdaftar, saya ingin berkirim pesan langsung (live chat) dengan tutor.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200013_create_chats_table.php` | Tabel `chats`: sender_id (FK users), receiver_id (FK users), message (text), is_read (boolean default false) |
| **Model** | `app/Models/Chat.php` | Fillable, casts (is_read=boolean), relasi: belongsTo(User, 'sender_id'), belongsTo(User, 'receiver_id') |
| **Controller** | `app/Http/Controllers/ChatController.php` | Method `index()`: list chat partners + available tutors. Method `show()`: conversation messages, mark as read |
| **View** | `resources/views/chat/index.blade.php` | Sidebar: daftar chat partners + available tutors. Main: conversation messages + input kirim pesan. Real-time via Socket.IO |
| **Route** | `routes/web.php` | `Route::get('/chat', ...)`, `Route::get('/chat/{receiver}', ...)` |
| **Socket Server** | `socket-server/server.js` | Node.js WebSocket server: terima & broadcast pesan real-time |
| **Socket Config** | `socket-server/package.json` | Dependency: express, socket.io, cors |
| **API Controller** | `app/Http/Controllers/Api/ChatApiController.php` | Methods: `messages()`, `send()` |
| **API Route** | `routes/api.php` | Endpoint chats |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Chat" ke `/chat` |

**Setup tambahan di local:**
```bash
cd socket-server
npm install
node server.js   # Terminal terpisah
```

---

## EPIC 5 – Marketplace Keterampilan

### 📌 PBI 13 – Katalog Marketplace
> *Sebagai pengguna, saya ingin melihat katalog barang dan karya kerajinan.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200014_create_products_table.php` | Tabel `products`: seller_id (FK users), name, description, image, price, stock, is_active |
| **Migration** | `database/migrations/2026_04_19_000001_add_type_to_products_table.php` | Tambah kolom `type` enum(physical,digital) ke products |
| **Model** | `app/Models/Product.php` | Fillable (termasuk type), casts (price=decimal, is_active=boolean), relasi: belongsTo(User, 'seller_id') |
| **Controller** | `app/Http/Controllers/ProductController.php` | Method `index()`: list active products, search & filter. Method `show()`: detail produk |
| **View** | `resources/views/marketplace/index.blade.php` | Grid produk: gambar, nama, harga, seller. Search bar + filter type |
| **View** | `resources/views/marketplace/show.blade.php` | Detail produk: gambar besar, deskripsi, harga, stock, seller info, tombol "Tambah Keranjang" |
| **Route** | `routes/web.php` | `Route::get('/marketplace', ...)`, `Route::get('/marketplace/{product}', ...)` |
| **API Controller** | `app/Http/Controllers/Api/ProductApiController.php` | Methods: `index()`, `show()` |
| **API Route** | `routes/api.php` | Endpoint products list & detail |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Marketplace" (hidden untuk tutor) |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Seed 5 products |

---

### 📌 PBI 14 – Form Jual Karya/Produk
> *Sebagai pelajar/penjual, saya ingin mengunggah foto, deskripsi, dan harga barang.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Controller** | `app/Http/Controllers/ProductController.php` | Method `create()`: form upload. Method `store()`: validasi, upload image ke `storage/app/public/products/`, simpan record |
| **View** | `resources/views/marketplace/create.blade.php` | Form: nama produk, tipe (physical/digital), deskripsi, harga, stock, upload foto. Tombol submit |
| **Route** | `routes/web.php` | `Route::get('/marketplace/create', ...)`, `Route::post('/marketplace', ...)` — middleware `role:user` |
| **API Controller** | `app/Http/Controllers/Api/ProductApiController.php` | Method: `store()` — validasi role user, upload image |
| **API Route** | `routes/api.php` | `Route::post('/products', ...)` |

**Dependensi:** PBI 13 (Product model & migration)

---

### 📌 PBI 15 – Kelola Etalase Toko
> *Sebagai pelajar/penjual, saya ingin mengelola produk jualan saya (edit/hapus).*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Controller** | `app/Http/Controllers/ProductController.php` | Method `edit()`: form edit (pre-filled). Method `update()`: validasi, replace image jika baru, update record. Method `destroy()`: hapus image & record. Authorization check: seller_id === user id |
| **View** | `resources/views/marketplace/edit.blade.php` | Form edit: sama dengan create tapi pre-filled, tombol update & hapus |
| **Route** | `routes/web.php` | `Route::get('/marketplace/{product}/edit', ...)`, `Route::put('/marketplace/{product}', ...)`, `Route::delete('/marketplace/{product}', ...)` — middleware `role:user` |
| **API Controller** | `app/Http/Controllers/Api/ProductApiController.php` | Methods: `myProducts()`, `update()`, `destroy()` — authorization check |
| **API Route** | `routes/api.php` | Endpoint my/products, update, delete |

**Dependensi:** PBI 14 (form create sudah ada)

---

## EPIC 6 – Transaksi & Pembayaran

### 📌 PBI 16 – Katalog Paket Langganan
> *Sebagai pengguna, saya ingin melihat pilihan paket berlangganan beserta harganya.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200017_create_subscriptions_table.php` | Tabel `subscriptions`: user_id (FK), package (string), price, status, starts_at, expires_at |
| **Model** | `app/Models/Subscription.php` | Fillable, casts (price=decimal, starts_at/expires_at=datetime), method `isActive()`, relasi: belongsTo(User) |
| **Controller** | `app/Http/Controllers/SubscriptionController.php` | Method `index()`: tampilkan packages (Basic/Premium) + current subscription. Method `payment()`: halaman bayar. Method `process()`: create Subscription record |
| **View** | `resources/views/subscriptions/index.blade.php` | Kartu paket: nama, harga, fitur list, tombol "Pilih Paket". Tampilkan paket aktif jika ada |
| **View** | `resources/views/subscriptions/payment.blade.php` | Halaman konfirmasi: detail paket, pilih metode bayar, tombol proses |
| **Route** | `routes/web.php` | `Route::get('/subscriptions', ...)`, `Route::post('/subscriptions', ...)`, `Route::get('/subscriptions/payment/{package}', ...)`, `Route::post('/subscriptions/process', ...)` |
| **API Controller** | `app/Http/Controllers/Api/SubscriptionApiController.php` | Methods: `packages()`, `current()`, `subscribe()` |
| **API Route** | `routes/api.php` | Endpoint packages, current, subscribe |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Langganan" |

---

### 📌 PBI 17 – Keranjang Belanja
> *Sebagai pengguna, saya ingin menyimpan produk marketplace ke dalam keranjang.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200015_create_carts_table.php` | Tabel `carts`: user_id (FK) |
| **Migration** | `database/migrations/2026_04_16_200016_create_cart_items_table.php` | Tabel `cart_items`: cart_id (FK), itemable_type, itemable_id (polymorphic: Product/Course), quantity, price |
| **Model** | `app/Models/Cart.php` | Fillable, relasi: belongsTo(User), hasMany(CartItem). Accessor `getTotalAttribute()` |
| **Model** | `app/Models/CartItem.php` | Fillable, casts (price=decimal), relasi: belongsTo(Cart), morphTo(itemable) |
| **Controller** | `app/Http/Controllers/CartController.php` | Method `index()`: tampilkan isi keranjang + total. Method `addProduct()`: tambah produk. Method `addCourse()`: tambah kursus. Method `remove()`: hapus item |
| **View** | `resources/views/cart/index.blade.php` | Tabel keranjang: item, harga, qty, subtotal. Tombol hapus per item. Total. Tombol "Checkout" |
| **Route** | `routes/web.php` | Cart routes — middleware `role:user` |
| **API Controller** | `app/Http/Controllers/Api/CartApiController.php` | Methods: `index()`, `addProduct()`, `addCourse()`, `removeItem()` |
| **API Route** | `routes/api.php` | Endpoint cart CRUD |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah icon keranjang (hidden untuk tutor) |

**Dependensi:** PBI 13 (Product model)

---

### 📌 PBI 18 – Checkout & Konfirmasi Pembayaran
> *Sebagai pengguna, saya ingin melakukan checkout dan memproses pembayaran.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200018_create_transactions_table.php` | Tabel `transactions`: user_id (FK), transaction_code, total_amount, status, payment_method, items (JSON) |
| **Migration** | `database/migrations/2026_04_19_000003_add_type_to_transactions_table.php` | Tambah kolom `type` ke transactions |
| **Model** | `app/Models/Transaction.php` | Fillable, casts (total_amount=decimal, items=array), relasi: belongsTo(User) |
| **Controller** | `app/Http/Controllers/CheckoutController.php` | Method `index()`: tampilkan ringkasan cart + pilih alamat + metode bayar. Method `process()`: create Transaction, auto-enroll untuk course, clear cart |
| **View** | `resources/views/checkout/index.blade.php` | Ringkasan belanja, pilih alamat pengiriman, pilih metode bayar (transfer/ewallet/cod), tombol "Bayar" |
| **Route** | `routes/web.php` | `Route::get('/checkout', ...)`, `Route::post('/checkout/process', ...)` — middleware `role:user` |
| **API Controller** | `app/Http/Controllers/Api/CartApiController.php` | Method: `checkout()` |
| **API Route** | `routes/api.php` | `Route::post('/checkout', ...)` |

**Dependensi:** PBI 17 (Cart model), PBI 4 (Enrollment — auto-enroll setelah beli kursus)

---

## EPIC 7 – Manajemen Profil Pengguna

### 📌 PBI 19 – Kelola Data Diri
> *Sebagai pengguna terdaftar, saya ingin mengubah informasi dasar seperti nama, foto profil, dan nomor telepon.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Controller** | `app/Http/Controllers/ProfileController.php` | Method `edit()`: form profil. Method `update()`: validasi, upload foto, update user. Method `destroy()`: hapus akun |
| **View** | `resources/views/profile/edit.blade.php` | Halaman profil: include partials untuk edit info, password, hapus akun |
| **View** | `resources/views/profile/partials/update-profile-information-form.blade.php` | Form: nama, email, telepon, upload foto profil |
| **View** | `resources/views/profile/partials/update-password-form.blade.php` | Form: password lama, password baru, konfirmasi password |
| **View** | `resources/views/profile/partials/delete-user-form.blade.php` | Form konfirmasi hapus akun |
| **Route** | `routes/web.php` | `Route::get('/profile', ...)`, `Route::patch('/profile', ...)`, `Route::delete('/profile', ...)` |
| **API Controller** | `app/Http/Controllers/Api/ProfileApiController.php` | Methods: `show()`, `update()`, `updatePassword()` |
| **API Route** | `routes/api.php` | Endpoint user profile CRUD |

---

### 📌 PBI 20 – Manajemen Alamat
> *Sebagai pengguna terdaftar, saya ingin menambahkan atau mengedit alamat rumah.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200021_create_addresses_table.php` | Tabel `addresses`: user_id (FK), label, address, city, province, postal_code, is_default (boolean) |
| **Model** | `app/Models/Address.php` | Fillable, casts (is_default=boolean), relasi: belongsTo(User) |
| **Controller** | `app/Http/Controllers/AddressController.php` | Method `index()`: list alamat user. Method `store()`: tambah alamat (reset is_default jika perlu). Method `update()`: edit alamat. Method `destroy()`: hapus alamat |
| **View** | `resources/views/profile/addresses.blade.php` | List alamat + form tambah/edit: label, alamat, kota, provinsi, kode pos, checkbox default |
| **Route** | `routes/web.php` | `Route::get('/addresses', ...)`, `Route::post('/addresses', ...)`, `Route::put('/addresses/{address}', ...)`, `Route::delete('/addresses/{address}', ...)` |
| **API Controller** | `app/Http/Controllers/Api/ProfileApiController.php` | Methods: `addresses()`, `storeAddress()`, `updateAddress()`, `destroyAddress()` |
| **API Route** | `routes/api.php` | Endpoint addresses CRUD |

---

### 📌 PBI 21 – Riwayat & Unduh Sertifikat
> *Sebagai pelajar terdaftar, saya ingin melihat riwayat kursus yang sudah tamat dan mengunduh sertifikat.*

#### File yang Harus Dibuat/Diisi:

| Layer | File | Isi / Tugas |
|---|---|---|
| **Migration** | `database/migrations/2026_04_16_200022_create_certificates_table.php` | Tabel `certificates`: user_id (FK), enrollment_id (FK), certificate_number, issued_at |
| **Model** | `app/Models/Certificate.php` | Fillable, casts (issued_at=datetime), relasi: belongsTo(User), belongsTo(Enrollment) |
| **Controller** | `app/Http/Controllers/CertificateController.php` | Method `history()`: list completed enrollments + certificates. Method `download()`: generate PDF sertifikat (auto-create Certificate record jika belum ada) |
| **View** | `resources/views/profile/history.blade.php` | Tabel riwayat kursus: nama kursus, tanggal selesai, tombol "Download Sertifikat" |
| **View** | `resources/views/certificates/pdf.blade.php` | Template sertifikat PDF: logo, nama pelajar, nama kursus, certificate_number, tanggal |
| **Route** | `routes/web.php` | `Route::get('/history', ...)`, `Route::get('/certificates/{enrollment}/download', ...)` |
| **API Controller** | `app/Http/Controllers/Api/ProfileApiController.php` | Method: `certificates()` |
| **API Route** | `routes/api.php` | Endpoint certificates list |
| **Nav** | `resources/views/layouts/navigation.blade.php` | Tambah link "Riwayat" |

**Dependensi:** PBI 4 (Enrollment model — hanya yang is_completed=true)

---

## 🔗 Dependency Antar PBI

```
PBI 1 (Katalog Kursus)
  └── PBI 2 (Detail & Silabus) ── butuh PBI 1
       └── PBI 3 (Pencarian & Filter) ── modifikasi PBI 1
       └── PBI 5 (Akses Materi) ── butuh PBI 2 + PBI 4
            └── PBI 6 (Kuis) ── butuh PBI 5

PBI 4 (Dashboard) ── butuh PBI 1 + Auth
  └── PBI 21 (Sertifikat) ── butuh PBI 4

PBI 7 (Daftar Tutor) ── butuh PBI 1 (Tutor model)
  └── PBI 8 (Booking) ── butuh PBI 7
  └── PBI 9 (Review) ── butuh PBI 7

PBI 10 (Forum Thread) ── independent
  └── PBI 11 (Reply & Like) ── butuh PBI 10

PBI 12 (Chat) ── independent

PBI 13 (Marketplace) ── independent
  └── PBI 14 (Jual Produk) ── butuh PBI 13
       └── PBI 15 (Kelola Etalase) ── butuh PBI 14

PBI 16 (Langganan) ── independent
PBI 17 (Keranjang) ── butuh PBI 13
  └── PBI 18 (Checkout) ── butuh PBI 17 + PBI 4

PBI 19 (Profil) ── independent (dari Breeze)
PBI 20 (Alamat) ── independent
```

### Urutan Pengerjaan yang Disarankan:

**Sprint 1 (Paralel):**
- Anggota A: PBI 1 → PBI 2 → PBI 3
- Anggota B: PBI 10 → PBI 11
- Anggota C: PBI 19 → PBI 20
- Anggota D: PBI 13

**Sprint 2 (Paralel):**
- Anggota A: PBI 4 → PBI 5 → PBI 6
- Anggota B: PBI 12
- Anggota C: PBI 7 → PBI 8 → PBI 9
- Anggota D: PBI 14 → PBI 15

**Sprint 3 (Paralel):**
- Anggota A: PBI 21
- Anggota B: PBI 16
- Anggota C: PBI 17 → PBI 18

---

## 🔄 Git Workflow Per PBI

### 1. Mulai PBI Baru
```bash
git checkout main
git pull origin main
git checkout -b feature/epic-1-pbi-1-katalog-kursus
```

### 2. Kerjakan (di local)
```bash
# Buat migration jika perlu
php artisan make:migration create_xxx_table
php artisan make:model XxxModel
php artisan make:controller XxxController

# Test migration
php artisan migrate

# Test di browser
php artisan serve   # Terminal 1
npm run dev         # Terminal 2
```

### 3. Commit & Push
```bash
git add .
git status   # Pastikan tidak ada .env, vendor/, node_modules/
git commit -m "[Epic 1 - PBI 1] Implementasi halaman katalog kursus"
git push origin feature/epic-1-pbi-1-katalog-kursus
```

### 4. Buat Pull Request di GitHub
- Buka repo → "Compare & pull request"
- Tulis deskripsi perubahan
- Assign reviewer
- Merge setelah approved

### 5. Anggota Lain Sync
```bash
git checkout main
git pull origin main
composer install       # Jika ada package baru
npm install            # Jika ada package baru
php artisan migrate    # Jika ada migration baru
```

---

## ✅ Checklist Sebelum Push

- [ ] `php artisan serve` → tidak ada error
- [ ] Fitur sudah di-test di browser
- [ ] `php artisan migrate:fresh --seed` → berjalan tanpa error
- [ ] `git status` → tidak ada `.env`, `vendor/`, `node_modules/`
- [ ] Commit message jelas: `[Epic X - PBI Y] Deskripsi`
- [ ] Hanya file yang relevan dengan PBI yang di-commit

---

## 🚨 Troubleshooting

| Error | Solusi |
|---|---|
| `Class not found` | `composer dump-autoload` lalu `php artisan optimize:clear` |
| `Table doesn't exist` | `php artisan migrate` atau `php artisan migrate:fresh --seed` |
| `Mix/Vite manifest not found` | `npm install` lalu `npm run dev` |
| `No application encryption key` | `php artisan key:generate` |
| `Permission denied (storage)` | `php artisan storage:link` |
| `Merge conflict di migration` | Resolve manual, test `php artisan migrate:fresh --seed` |
| `Merge conflict di DatabaseSeeder` | Gabungkan kedua seed code, test ulang |
| `Merge conflict di web.php` | Gabungkan kedua route group, test `php artisan route:list` |

---

*Last Updated: April 19, 2026*
