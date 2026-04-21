# Pembagian Kerja Tim – Proyek E-Learning (Per Epic)

> **Stack:** Laravel 11 (MVC) · Blade · Tailwind CSS · MySQL · Socket.io (Node.js)  
> **Aturan Umum:** Setiap orang bertanggung jawab penuh atas satu Epic — mulai dari migrasi database, model, controller, route, view, hingga API endpoint yang berkaitan.  
> **Setup awal (WAJIB semua orang lakukan):** jalankan `composer install`, `npm install`, `cp .env.example .env`, `php artisan key:generate`, `php artisan migrate`, `php artisan storage:link`.

---
## NOTE ##
Yang COMMIT vs TIDAK ke GitHub
✅ COMMIT (sudah benar di-include)
package.json — wajib commit, ini yang dipakai tim untuk npm install
tailwind.config.js — wajib commit
vite.config.js — wajib commit
composer.json & composer.lock — wajib commit
postcss.config.js — wajib commit
Semua file di app/, routes/, resources/, database/, config/, bootstrap/

❌ TIDAK commit (sudah di .gitignore)
.env — ✅ sudah di-ignore, jangan pernah commit
/vendor/ — sudah di-ignore, nanti tiap anggota jalankan composer install
/node_modules/ — sudah di-ignore, nanti tiap anggota jalankan npm install
/public/build/ — sudah di-ignore, nanti tiap anggota jalankan npm run dev
/public/storage — sudah di-ignore
## Shared Files (Semua Orang Perlu Tahu)


## NOTE ##
File yang dipake barengan , koridnasi dulu.

| File | Keterangan |
|------|-----------|
| `routes/web.php` | Semua route web — tambahkan route di bagian Epic masing-masing |
| `routes/api.php` | Semua route API — tambahkan route di bagian Epic masing-masing |
| `routes/auth.php` | Register, login, logout — dikelola EPIC 7 |
| `app/Http/Middleware/RoleMiddleware.php` | Middleware role (user/tutor/admin) |
| `app/Http/Middleware/VerifiedTutorMiddleware.php` | Middleware tutor terverifikasi |
| `resources/views/layouts/` | Layout utama Blade |
| `resources/views/components/` | Komponen Blade bersama |
| `resources/views/landing.blade.php` | Halaman landing page |
| `socket-server/server.js` | Server WebSocket Node.js — dikelola EPIC 4 |
| `bootstrap/app.php` | Konfigurasi middleware & service provider |

---

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

---

## EPIC 3 – Layanan Tutor & Home Visit
**PBI:** PBI-7 (Daftar & Profil Tutor) · PBI-8 (Form Booking Home Visit) · PBI-9 (Rating & Ulasan)

## BACKEND (BE) 

### Database Migrations
```
database/migrations/2026_04_16_200002_create_tutors_table.php
database/migrations/2026_04_19_000002_add_status_to_tutors_table.php
database/migrations/2026_04_16_200019_create_home_visit_bookings_table.php
database/migrations/2026_04_20_100701_create_tutor_availabilities_table.php
database/migrations/2026_04_20_100702_add_booking_payment_fields_to_home_visit_bookings_table.php
database/migrations/2026_04_16_200020_create_tutor_reviews_table.php
database/migrations/2026_04_20_090736_add_bank_details_to_tutors_table.php
database/migrations/2026_04_20_100703_add_wallet_fields_to_tutors_table.php
database/migrations/2026_04_20_100703_create_tutor_withdrawals_table.php
database/migrations/2026_04_20_143522_add_user_id_to_tutor_withdrawals_table.php
```
> Tutor: `user_id`, `bio`, `specialization`, `qualifications`, `rate_per_hour`, `photo`, `status` (pending/approved/rejected).  
> HomeVisitBooking: `user_id`, `tutor_id`, `date`, `time`, `address`, `status`, `price`, `payment_proof`.  
> TutorAvailability: `tutor_id`, `day_of_week`, `start_time`, `end_time`.  
> TutorReview: `user_id`, `tutor_id`, `booking_id`, `rating` (1–5), `comment`.

### Models
```
app/Models/Tutor.php              ← belongsTo User, hasMany Course, hasMany TutorReview, hasMany TutorAvailability
app/Models/HomeVisitBooking.php   ← belongsTo User, belongsTo Tutor
app/Models/TutorReview.php        ← belongsTo User, belongsTo Tutor
app/Models/TutorAvailability.php  ← belongsTo Tutor
app/Models/TutorPayment.php       ← belongsTo Tutor
app/Models/TutorWithdrawal.php    ← belongsTo Tutor, belongsTo User
```

### Controllers
```
app/Http/Controllers/TutorController.php
    index()   → list tutor approved + filter spesialisasi
    show()    → profil lengkap tutor + kursus + rating rata-rata

app/Http/Controllers/BookingController.php
    create()        → form pemesanan home visit
    store()         → simpan booking + validasi ketersediaan
    payment()       → halaman pembayaran booking
    processPayment()→ validasi & simpan bukti bayar (upload gambar jpg/png max 2MB); status tetap pending hingga admin konfirmasi
    updateStatus()  → update status booking (selesai/batal)

app/Http/Controllers/TutorReviewController.php
    store()   → simpan rating & ulasan setelah sesi selesai

app/Http/Controllers/Tutor/ProfileController.php
    show(), edit(), update()  → tutor kelola profil sendiri

app/Http/Controllers/Tutor/AvailabilityController.php
    index(), store(), destroy() → tutor atur jadwal ketersediaan

app/Http/Controllers/Api/TutorApiController.php
    index(), show() → JSON data tutor
```

### Requests
```
app/Http/Requests/StoreBookingRequest.php   ← validasi form booking
```

## FRONTEND (FE)
### Views
```
resources/views/tutors/index.blade.php       ← daftar tutor + filter
resources/views/tutors/show.blade.php        ← profil tutor + form ulasan + tombol booking
resources/views/tutors/booking.blade.php     ← form booking home visit (tanggal, jam, alamat)
resources/views/bookings/payment.blade.php   ← halaman bayar booking
resources/views/tutor/profile.blade.php      ← tutor kelola profil (dashboard tutor)
resources/views/tutor/availability.blade.php ← tutor atur jadwal
resources/views/tutor/dashboard.blade.php    ← ringkasan booking & pendapatan tutor
resources/views/tutor/payments.blade.php     ← riwayat pembayaran masuk
resources/views/tutor/withdrawals.blade.php  ← riwayat penarikan dana
resources/views/tutor/withdrawal-create.blade.php ← form tarik dana
```

### Routes
```php
// Public
Route::get('/tutors', [TutorController::class, 'index'])->name('tutors.index');
Route::get('/tutors/{tutor}', [TutorController::class, 'show'])->name('tutors.show');

// Auth
Route::post('/tutors/{tutor}/reviews', [TutorReviewController::class, 'store'])->name('tutors.reviews.store');
Route::get('/tutors/{tutor}/book', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay');
Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

// Tutor area (middleware: auth + role:tutor)
Route::prefix('tutor')->middleware(['auth','role:tutor'])->group(function () {
    Route::get('/profile', [Tutor\ProfileController::class, 'show'])->name('tutor.profile');
    Route::get('/availability', [Tutor\AvailabilityController::class, 'index'])->name('tutor.availability');
    // ...
});
```

### Checklist PBI
- [ ] PBI-7: Daftar tutor approved tampil dengan foto, spesialisasi, rating
- [ ] PBI-8: Form booking dengan kalender jadwal + validasi slot tersedia, konfirmasi & pembayaran
- [ ] PBI-9: Form rating bintang (1–5) + kolom komentar, tampil di halaman profil tutor


### ============================================= ###

## EPIC 4 – Komunikasi & Komunitas
**PBI:** PBI-10 (Buat Topik Forum) · PBI-11 (Reply & Like) · PBI-12 (Live Chat)

## BACKEND (BE)

### Database Migrations
```
database/migrations/2026_04_16_200010_create_forum_threads_table.php
database/migrations/2026_04_16_200011_create_forum_replies_table.php
database/migrations/2026_04_16_200012_create_forum_likes_table.php
database/migrations/2026_04_16_200013_create_chats_table.php
database/migrations/2026_04_21_063644_add_attachment_to_chats_table.php
```
> ForumThread: `user_id`, `title`, `body`, `is_pinned`.  
> ForumReply: `user_id`, `thread_id`, `body`.  
> ForumLike: `user_id`, `reply_id` (unique per user per reply).  
> Chat: `sender_id`, `receiver_id`, `message`, `is_read`, `attachment` (nullable), `attachment_name` (nullable).

### Models
```
app/Models/ForumThread.php   ← belongsTo User, hasMany ForumReply
app/Models/ForumReply.php    ← belongsTo User, belongsTo ForumThread, hasMany ForumLike
app/Models/ForumLike.php     ← belongsTo User, belongsTo ForumReply
app/Models/Chat.php          ← belongsTo sender (User), belongsTo receiver (User); fillable: sender_id, receiver_id, message, is_read, attachment, attachment_name
```

### Controllers
```
app/Http/Controllers/ForumThreadController.php
    index()   → list semua thread
    show()    → detail thread + semua reply
    create()  → form buat thread baru
    store()   → simpan thread baru

app/Http/Controllers/ForumReplyController.php
    store()       → tambah reply ke thread
    toggleLike()  → toggle like/unlike sebuah reply

app/Http/Controllers/ChatController.php
    index()   → daftar kontak chat; sidebar: chat partners + tutor approved + seller aktif (belum pernah chat)
    show()    → percakapan dengan user/tutor/seller tertentu
    send()    → kirim pesan + opsional file attachment (multipart/form-data, max 10MB); return JSON

app/Http/Controllers/Api/ForumApiController.php
    threads(), replies(), like()  → endpoint AJAX forum

app/Http/Controllers/Api/ChatApiController.php
    messages()   → ambil history pesan via AJAX/polling
    send()       → kirim pesan via AJAX
```

### Requests
```
app/Http/Requests/StoreForumThreadRequest.php  ← validasi judul & isi thread
```

## FRONTEND (FE)

### Views
```
resources/views/forum/index.blade.php    ← daftar semua thread + tombol buat baru
resources/views/forum/show.blade.php     ← isi thread + form reply + tombol like
resources/views/forum/create.blade.php   ← form buat thread baru
resources/views/chat/index.blade.php     ← UI live chat full-height; sidebar: chat partners + "Chat Marketplace" (tutor & penjual); area pesan scrollable; file attachment upload; input sticky di bawah
```

### WebSocket Server (Node.js)
```
socket-server/server.js       ← server Socket.io UTAMA — dikelola epic ini
socket-server/package.json    ← dependencies: express, socket.io, cors
```

**Event Socket.io yang harus diimplementasikan:**
| Event (emit dari client) | Handler di server | Broadcast ke |
|---|---|---|
| `register` | simpan userId → socketId map | semua: `online-users` |
| `join-chat-room` | `socket.join(roomName)` | — |
| `leave-chat-room` | `socket.leave(roomName)` | — |
| `send-message` | — | room: `new-message` + notif: `message-notification` |
| `disconnect` | hapus dari map | semua: `online-users` update |

**Integrasi Blade (chat/index.blade.php):**
```html
<!-- tambahkan di bawah layout -->
<script src="http://127.0.0.1:3000/socket.io/socket.io.js"></script>
<script>
  const socket = io('http://127.0.0.1:3000');
  socket.emit('register', {{ auth()->id() }});
  // join room, send-message, listen new-message
</script>
```

### Routes
```php
// Public
Route::get('/forum', [ForumThreadController::class, 'index'])->name('forum.index');
Route::get('/forum/{thread}', [ForumThreadController::class, 'show'])->name('forum.show');

// Auth
Route::get('/forum-create', [ForumThreadController::class, 'create'])->name('forum.create');
Route::post('/forum', [ForumThreadController::class, 'store'])->name('forum.store');
Route::post('/forum/{thread}/replies', [ForumReplyController::class, 'store'])->name('forum.replies.store');
Route::post('/forum/replies/{reply}/like', [ForumReplyController::class, 'toggleLike'])->name('forum.replies.like');
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat/{receiver}', [ChatController::class, 'show'])->name('chat.show');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
```

### Cara Menjalankan Socket Server
```bash
cd socket-server
npm install
node server.js   # berjalan di port 3000
```

### Checklist PBI
- [ ] PBI-10: Form buat thread tampil & tersimpan, muncul di daftar forum
- [ ] PBI-11: Form reply berfungsi, tombol like toggle (boleh AJAX), hitungan like update
- [ ] PBI-12: UI chat real-time via Socket.io, pesan muncul tanpa reload, indikator online

---


## EPIC 5 – Marketplace Keterampilan
**PBI:** PBI-13 (Katalog Marketplace) · PBI-14 (Jual Karya/Produk) · PBI-15 (Kelola Etalase)

## BACKEND (BE)

### Database Migrations
```
database/migrations/2026_04_16_200014_create_products_table.php
database/migrations/2026_04_19_000001_add_type_to_products_table.php
```
> Product: `user_id`, `name`, `description`, `price`, `stock`, `image`, `type` (craft/supply/course-material), `status` (active/inactive), `category`.

### Models
```
app/Models/Product.php   ← belongsTo User, hasMany CartItem
```
Tambahkan scope `active()`, `byType($type)`, `search($query)`.

### Controllers
```
app/Http/Controllers/ProductController.php
    index()        → katalog publik + filter & search
    show()         → detail produk
    create()       → form upload produk baru
    store()        → simpan produk + upload gambar ke storage
    edit()         → form edit produk milik sendiri
    update()       → update produk
    destroy()      → hapus produk
    toggleStatus() → aktif/nonaktif produk

app/Http/Controllers/MyProductsController.php
    index()   → etalase toko milik user yang login

app/Http/Controllers/Api/ProductApiController.php
    index(), show() → JSON produk (untuk filter AJAX)
```

### Requests
```
app/Http/Requests/StoreProductRequest.php  ← validasi nama, harga, gambar, deskripsi
```

## FRONTEND (FE)
### Views
```
resources/views/marketplace/index.blade.php    ← katalog + search + filter tipe
resources/views/marketplace/show.blade.php     ← detail produk + tombol add to cart + tombol "Chat Penjual" (→ chat.show dengan seller)
resources/views/marketplace/create.blade.php   ← form upload produk baru (dengan upload gambar)
resources/views/marketplace/edit.blade.php     ← form edit produk
resources/views/my-products/                   ← etalase toko: list produk + edit/hapus/toggle
```

### Storage (Gambar Produk)
```
public/storage/products/   ← hasil upload gambar produk (via storage:link)
```
Di controller gunakan:
```php
$path = $request->file('image')->store('products', 'public');
```

### Routes
```php
// Public
Route::get('/marketplace', [ProductController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{product}', [ProductController::class, 'show'])->name('marketplace.show');

// Auth + role:user
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/marketplace/create', [ProductController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [ProductController::class, 'store'])->name('marketplace.store');
    Route::get('/marketplace/{product}/edit', [ProductController::class, 'edit'])->name('marketplace.edit');
    Route::put('/marketplace/{product}', [ProductController::class, 'update'])->name('marketplace.update');
    Route::delete('/marketplace/{product}', [ProductController::class, 'destroy'])->name('marketplace.destroy');
    Route::post('/marketplace/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('marketplace.toggle');
    Route::get('/my-products', [MyProductsController::class, 'index'])->name('my-products.index');
});
```

### Checklist PBI
- [ ] PBI-13: Katalog produk tampil dengan gambar, nama, harga, filter tipe/kategori
- [ ] PBI-14: Form upload foto (preview), deskripsi, harga, validasi berjalan, gambar tersimpan
- [ ] PBI-15: Halaman etalase pribadi, tombol edit & hapus berfungsi, toggle status aktif/nonaktif

---

## EPIC 6 – Transaksi & Pembayaran
**PBI:** PBI-16 (Paket Langganan) · PBI-17 (Keranjang Belanja) · PBI-18 (Checkout & Konfirmasi)


## BACKEND (BE)
### Database Migrations
```
database/migrations/2026_04_16_200015_create_carts_table.php
database/migrations/2026_04_16_200016_create_cart_items_table.php
database/migrations/2026_04_16_200017_create_subscriptions_table.php
database/migrations/2026_04_16_200018_create_transactions_table.php
database/migrations/2026_04_19_000003_add_type_to_transactions_table.php
database/migrations/2026_04_20_141934_add_shipping_address_to_transactions_table.php
database/migrations/2026_04_20_143410_add_wallet_fields_to_users_table.php
```
> Cart: `user_id`.  
> CartItem: `cart_id`, `product_id` (nullable), `course_id` (nullable), `quantity`, `price`.  
> Subscription: `user_id`, `plan` (basic/premium/pro), `price`, `starts_at`, `ends_at`, `status`.  
> Transaction: `user_id`, `type` (product/course/subscription/booking), `total`, `status` (pending/paid/failed), `payment_proof`, `shipping_address`.

### Models
```
app/Models/Cart.php          ← belongsTo User, hasMany CartItem
app/Models/CartItem.php      ← belongsTo Cart, belongsTo Product, belongsTo Course
app/Models/Subscription.php  ← belongsTo User
app/Models/Transaction.php   ← belongsTo User
```

### Controllers
```
app/Http/Controllers/SubscriptionController.php
    index()    → tampilkan pilihan paket langganan
    store()    → proses pilih & bayar langganan
    payment()  → halaman pembayaran langganan

app/Http/Controllers/CartController.php
    index()      → tampilkan isi keranjang
    addProduct() → tambah produk ke keranjang
    addCourse()  → tambah kursus ke keranjang
    remove()     → hapus item dari keranjang

app/Http/Controllers/CheckoutController.php
    index()    → ringkasan checkout + form alamat pengiriman
    process()  → buat transaksi, kosongkan keranjang, proses pembayaran

app/Http/Controllers/OrderController.php
    index(), show() → riwayat order/transaksi user

app/Http/Controllers/MyExpensesController.php
    index()   → rekapitulasi pengeluaran user

app/Http/Controllers/SellerWalletController.php
    index()   → saldo & riwayat pemasukan seller

app/Http/Controllers/Api/CartApiController.php
    add(), remove(), count() → operasi keranjang via AJAX

app/Http/Controllers/Api/SubscriptionApiController.php
    plans(), status() → JSON paket langganan

app/Http/Controllers/Admin/TransactionController.php
    index(), show() → admin kelola transaksi
    approve()      → konfirmasi transaksi; handle 3 tipe: purchase (enroll + kredit seller + auto-chat notif untuk produk digital), subscription (buat record Subscription), booking (set is_paid=true, status=confirmed, kredit tutor)
    reject()       → tolak transaksi
```

## FRONTEND (FE)

### Views
```
resources/views/subscriptions/index.blade.php    ← kartu pilihan paket (basic/premium/pro)
resources/views/subscriptions/payment.blade.php  ← halaman bayar langganan
resources/views/cart/index.blade.php             ← daftar item keranjang + total harga
resources/views/checkout/index.blade.php         ← ringkasan order + form alamat + konfirmasi
resources/views/orders/index.blade.php           ← riwayat transaksi; step progress bar (Menunggu Bayar → Verifikasi Admin → Selesai); link upload bukti bayar (pending); link "Chat Penjual" untuk produk digital yang sudah verified
resources/views/transactions/                    ← detail transaksi
resources/views/my-expenses/                     ← rekap pengeluaran
resources/views/seller/wallet.blade.php          ← dompet & pendapatan seller
resources/views/admin/transactions/              ← admin: kelola semua transaksi
```

### Routes
```php
Route::middleware(['auth','role:user'])->group(function () {
    // Langganan
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');

    // Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add-product', [CartController::class, 'addProduct'])->name('cart.addProduct');
    Route::post('/cart/add-course', [CartController::class, 'addCourse'])->name('cart.addCourse');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Order & Riwayat
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-expenses', [MyExpensesController::class, 'index'])->name('my-expenses.index');
    Route::get('/seller/wallet', [SellerWalletController::class, 'index'])->name('seller.wallet');
});
```

### Checklist PBI
- [ ] PBI-16: 3 kartu paket langganan tampil dengan fitur & harga, klik masuk ke pembayaran
- [ ] PBI-17: Item bisa ditambah/dihapus dari keranjang, jumlah & total harga update otomatis
- [ ] PBI-18: Halaman checkout tampil ringkasan, form alamat, proses pembayaran → status transaksi updated

---

## EPIC 7 – Manajemen Profil Pengguna
**PBI:** PBI-19 (Kelola Data Diri) · PBI-20 (Manajemen Alamat) · PBI-21 (Riwayat & Sertifikat)


## BACKEND (BE)

### Database Migrations
```
database/migrations/0001_01_01_000000_create_users_table.php
database/migrations/2026_04_16_200021_create_addresses_table.php
database/migrations/2026_04_16_200022_create_certificates_table.php
```
> User: `name`, `email`, `password`, `phone`, `avatar`, `role` (user/tutor/admin), `wallet_balance`.  
> Address: `user_id`, `label` (rumah/kantor), `recipient_name`, `phone`, `street`, `city`, `province`, `postal_code`, `is_default`.  
> Certificate: `user_id`, `course_id`, `issued_at`, `certificate_number`.

### Models
```
app/Models/User.php        ← hasOne Tutor, hasMany Enrollment, hasMany Address, hasMany Certificate
app/Models/Address.php     ← belongsTo User
app/Models/Certificate.php ← belongsTo User, belongsTo Course
app/Models/Admin.php       ← (model admin terpisah)
```

### Controllers
```
app/Http/Controllers/ProfileController.php
    edit()    → form ubah data diri (nama, telepon, foto profil)
    update()  → simpan perubahan profil

app/Http/Controllers/AddressController.php
    index()   → list semua alamat user
    create()  → form tambah alamat baru
    store()   → simpan alamat baru
    edit()    → form edit alamat
    update()  → simpan perubahan alamat
    destroy() → hapus alamat
    setDefault() → set alamat utama

app/Http/Controllers/CertificateController.php
    index()      → list sertifikat yang dimiliki
    download()   → generate & download PDF sertifikat

app/Http/Controllers/Api/ProfileApiController.php
    show(), update() → JSON profil user

app/Http/Controllers/Auth/
    RegisteredUserController.php
    AuthenticatedSessionController.php
    PasswordController.php
    (dan controller auth lainnya)
```

### Requests
```
app/Http/Requests/ProfileUpdateRequest.php  ← validasi update profil
app/Http/Requests/Auth/                      ← validasi login & register
```

## FRONTEND (FE)
### Views
```
resources/views/profile/edit.blade.php        ← form edit nama, telepon, upload foto profil
resources/views/profile/addresses.blade.php   ← list alamat + tombol tambah/edit/hapus/set default
resources/views/profile/history.blade.php     ← riwayat kursus selesai
resources/views/profile/partials/             ← partial forms (update-profile, update-password, dll)
resources/views/certificates/pdf.blade.php    ← template PDF sertifikat digital
resources/views/auth/                         ← login, register, forgot-password, dll
```

### Dependencies Tambahan (untuk generate PDF)
```bash
composer require barryvdh/laravel-dompdf
```
Di `CertificateController::download()`:
```php
$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', compact('certificate'));
return $pdf->download("sertifikat-{$certificate->certificate_number}.pdf");
```

### Routes
```php
// Auth routes (routes/auth.php) — register, login, logout sudah ada

// Profile (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alamat
    Route::resource('/profile/addresses', AddressController::class)->names('addresses');
    Route::patch('/profile/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');

    // Sertifikat
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
});
```

### Checklist PBI
- [ ] PBI-19: Form edit nama, telepon, upload foto profil, validasi berjalan, perubahan tersimpan
- [ ] PBI-20: List alamat tampil, tambah/edit/hapus berfungsi, bisa set alamat default
- [ ] PBI-21: List kursus selesai tampil, tombol unduh menghasilkan PDF sertifikat yang valid

---

## Buat Git

### Konvensi Branch
```
epic1/course-catalog_nama_branch
epic2/learning-system_nama_branch
epic3/tutor-homevisit_nama_branch
epic4/forum-chat_nama_branch
epic5/marketplace_nama_branch
epic6/transaction-payment_nama_branch
epic7/user-profile_nama_branch
```

### Urutan Kerja yang Disarankan
1. **Setup:** `main` branch → `php artisan migrate` (schema harus disetujui bersama dulu)
2. **Per Epic:** buat branch dari `main`, kerjakan semua file Epic masing-masing
3. **Merge:** Pull Request ke `main` setelah Epic selesai, review oleh 1 anggota tim lain

### Potensi Konflik yang Perlu Dihindari
- `routes/web.php` — tambahkan di bagian bawah, jangan timpa route orang lain
- `resources/views/layouts/` — diskusikan perubahan layout bersama
- `database/migrations/` — jangan ubah migrasi yang sudah di-commit ke main
- `app/Models/User.php` — Epic 7 yang pegang, koordinasi jika Epic lain butuh tambah relasi

---

## Struktur Lengkap File Per Epic (RINGKASAN)

| Epic | Migrations | Models | Controllers | Views | Lainnya |
|------|-----------|--------|-------------|-------|---------|
| 1 – Kursus | `courses`, `modules` | Course, Module | CourseController | `courses/` | API: CourseApiController |
| 2 – Belajar | `enrollments`, `module_progress`, `quizzes`, `quiz_questions`, `quiz_attempts` | Enrollment, ModuleProgress, Quiz, QuizQuestion, QuizAttempt | Dashboard, Enrollment, Module, Quiz, MyCourses | `dashboard`, `courses/learn`, `courses/quiz`, `my-courses/` | API: QuizApiController |
| 3 – Tutor | `tutors`, `home_visit_bookings`, `tutor_availabilities`, `tutor_reviews`, `tutor_withdrawals` | Tutor, HomeVisitBooking, TutorReview, TutorAvailability, TutorPayment, TutorWithdrawal | TutorController, BookingController, TutorReviewController, Tutor/* | `tutors/`, `bookings/`, `tutor/` | API: TutorApiController |
| 4 – Komunitas | `forum_threads`, `forum_replies`, `forum_likes`, `chats` | ForumThread, ForumReply, ForumLike, Chat | ForumThread, ForumReply, Chat | `forum/`, `chat/` | **socket-server/server.js** |
| 5 – Marketplace | `products` | Product | ProductController, MyProductsController | `marketplace/`, `my-products/` | Storage upload gambar |
| 6 – Transaksi | `carts`, `cart_items`, `subscriptions`, `transactions` | Cart, CartItem, Subscription, Transaction | Cart, Checkout, Subscription, Order, MyExpenses, SellerWallet, Admin/Transaction | `cart/`, `checkout/`, `subscriptions/`, `orders/`, `transactions/`, `seller/` | API: CartApiController, SubscriptionApiController |
| 7 – Profil | `users`, `addresses`, `certificates` | User, Address, Certificate, Admin | ProfileController, AddressController, CertificateController, Auth/* | `profile/`, `certificates/`, `auth/` | PDF: dompdf |

---

## Command (buat lokal)

```bash
# Jalankan Laravel
php artisan serve

# Jalankan Vite (asset frontend)
npm run dev

# Jalankan Socket Server (EPIC 4)
cd socket-server && node server.js

# Jalankan semua migrasi
php artisan migrate

# Reset & seed ulang database
php artisan migrate:fresh --seed

# Link storage (untuk upload gambar)
php artisan storage:link

# Buat controller baru
php artisan make:controller NamaController

# Buat model + migration sekaligus
php artisan make:model NamaModel -m

# Buat request validation
php artisan make:request NamaRequest
```
