# Panduan Kolaborasi Tim - Learn Everything Project

## 📋 Struktur Kerja Epic & PBI

Project ini dikembangkan menggunakan metodologi **Epic-based workflow**, di mana setiap Epic dipecah menjadi beberapa PBI (Product Backlog Item) yang dikerjakan oleh anggota tim.

---

## 🗂️ Folder & File yang Perlu Diisi Per Jenis PBI

### **1. PBI: Fitur CRUD Baru (Contoh: Manajemen Produk)**

#### **Backend:**
- **Migration** → `database/migrations/YYYY_MM_DD_create_xxx_table.php`
- **Model** → `app/Models/XxxModel.php`
- **Controller** → `app/Http/Controllers/XxxController.php`
- **Form Request (opsional)** → `app/Http/Requests/StoreXxxRequest.php`, `UpdateXxxRequest.php`
- **Routes** → `routes/web.php` atau `routes/api.php`

#### **Frontend:**
- **Views** → `resources/views/xxx/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`
- **CSS (jika perlu)** → `resources/css/app.css`
- **JavaScript (jika perlu)** → `resources/js/app.js` atau Alpine.js di Blade

#### **Testing:**
- **Feature Test** → `tests/Feature/XxxTest.php`

---

### **2. PBI: Update Fitur Existing (Contoh: Tambah Field di Users)**

#### **Backend:**
- **Migration** → `database/migrations/YYYY_MM_DD_add_xxx_to_users_table.php`
- **Model** → Update `app/Models/User.php` (tambahkan field di `$fillable`, `$casts`, dll)
- **Controller** → Update controller terkait (misal `UserController.php`)
- **Seeder (jika perlu)** → `database/seeders/DatabaseSeeder.php`

#### **Frontend:**
- **Views** → Update form di `resources/views/users/edit.blade.php` atau `profile.blade.php`

---

### **3. PBI: Fitur API (Contoh: API untuk Mobile App)**

#### **Backend:**
- **API Controller** → `app/Http/Controllers/Api/XxxController.php`
- **API Resource** → `app/Http/Resources/XxxResource.php`
- **Routes** → `routes/api.php`
- **Middleware** → `app/Http/Middleware/` (jika butuh custom middleware)

#### **Testing:**
- **API Test** → `tests/Feature/Api/XxxApiTest.php`

---

### **4. PBI: UI/UX Update (Tanpa Perubahan Backend)**

#### **Frontend:**
- **Views** → File Blade yang relevan di `resources/views/`
- **CSS** → `resources/css/app.css` atau Tailwind classes
- **JavaScript** → `resources/js/` atau Alpine.js components

---

### **5. PBI: Role & Permission Update**

#### **Backend:**
- **Middleware** → `app/Http/Middleware/RoleMiddleware.php`
- **Routes** → `routes/web.php` (wrap routes dengan middleware `role:xxx`)
- **Navigation** → `resources/views/layouts/navigation.blade.php` (conditional menu)
- **Dashboard** → Update controller & view terkait role

---

## 🔧 Git Workflow untuk Tim

### **1. Clone Repository (Sekali di Awal)**
```bash
git clone <repository-url>
cd 1try_ppl
```

### **2. Setup Awal di Local (HANYA DI LOCAL, JANGAN PUSH!)**
```bash
# Install dependencies
composer install
npm install

# Copy environment file
copy .env.example .env

# Generate app key
php artisan key:generate

# Setup database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=learn_everything
DB_USERNAME=root
DB_PASSWORD=

# Run migrations & seed
php artisan migrate:fresh --seed

# Build assets
npm run dev
```

### **3. Workflow Per PBI**

#### **A. Mulai PBI Baru:**
```bash
# Pastikan di branch main dan up-to-date
git checkout main
git pull origin main

# Buat branch baru untuk PBI
git checkout -b feature/epic-1-pbi-3-tambah-produk
```

#### **B. Kerjakan PBI:**
1. Buat/edit file sesuai kebutuhan PBI (lihat panduan folder di atas)
2. **JIKA ADA MIGRATION BARU** (di local saja):
   ```bash
   php artisan migrate
   # atau kalau mau reset total:
   php artisan migrate:fresh --seed
   ```
3. Test fitur di browser (`php artisan serve`)

#### **C. Commit & Push:**
```bash
# Check status file yang berubah
git status

# Add file yang relevan (JANGAN add .env, vendor/, node_modules/)
git add app/Models/Product.php
git add app/Http/Controllers/ProductController.php
git add resources/views/products/
git add database/migrations/2026_04_19_create_products_table.php

# Commit dengan pesan jelas
git commit -m "[Epic 1 - PBI 3] Tambah fitur CRUD produk marketplace"

# Push ke GitHub
git push origin feature/epic-1-pbi-3-tambah-produk
```

#### **D. Create Pull Request di GitHub:**
1. Buka repository di GitHub
2. Klik "Compare & pull request"
3. Review changes → pastikan tidak ada file `.env`, `vendor/`, `node_modules/`
4. Assign reviewer
5. Tunggu approval & merge

---

## ⚠️ **PENTING: File yang JANGAN PERNAH DI-PUSH**

Sudah ada di `.gitignore`, tapi tetap waspada:
- `.env` (berisi kredensial database)
- `vendor/` (dependency Composer)
- `node_modules/` (dependency NPM)
- `public/hot`
- `public/storage`
- `storage/*.key`

---

## 🧪 Testing & Local Operations

### **Perintah yang HANYA JALAN DI LOCAL (Tidak bisa via GitHub):**

#### **1. Install Package Laravel (Contoh: Breeze, Sanctum)**
```bash
# Di local terminal
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
php artisan migrate
```
**Yang di-commit ke Git:**
- File `composer.json` & `composer.lock` (sudah otomatis update)
- File hasil instalasi (misal: routes/auth.php, resources/views/auth/)
- File `package.json` & `package-lock.json`

**Yang TIDAK di-commit:**
- Folder `vendor/` (anggota lain run `composer install` di local mereka)
- Folder `node_modules/` (anggota lain run `npm install` di local mereka)

#### **2. Run Migrations**
```bash
# Setiap kali pull branch yang ada migration baru
php artisan migrate

# Atau reset total database (hati-hati, data hilang!)
php artisan migrate:fresh --seed
```

#### **3. Run Tests**
```bash
php artisan test
# atau spesifik
php artisan test --filter ProductTest
```

#### **4. Clear Cache (Kalau ada masalah)**
```bash
php artisan optimize:clear
```

#### **5. Run Development Server**
```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Vite (untuk hot reload CSS/JS)
npm run dev

# Terminal 3 (opsional): Socket Server (untuk chat realtime)
cd socket-server
node server.js
```

---

## 👥 Skenario Kolaborasi Tim

### **Skenario 1: Anggota Tim Pull Branch dengan Migration Baru**

**Anggota A** push migration `create_products_table.php`

**Anggota B** pull branch tersebut:
```bash
git checkout feature/tambah-produk
git pull origin feature/tambah-produk

# Run migration di local
php artisan migrate

# Atau kalau mau reset total:
php artisan migrate:fresh --seed
```

---

### **Skenario 2: Anggota Tim Install Package Baru**

**Anggota A** install Breeze:
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev

# Commit hasil instalasi
git add composer.json composer.lock package.json package-lock.json
git add routes/auth.php app/Http/Controllers/Auth/ resources/views/auth/
git commit -m "Install Laravel Breeze untuk authentication"
git push
```

**Anggota B** pull dan setup:
```bash
git pull origin feature/install-breeze

# Install dependencies (BARU JALAN SETELAH PULL)
composer install
npm install
npm run dev
```

---

### **Skenario 3: Conflict di Migration**

**Jika 2 anggota buat migration berbeda di waktu sama:**

1. Laravel auto-generate timestamp di nama file migration
2. Merge conflict jarang terjadi di migration
3. Jika terjadi conflict, biasanya di `database/seeders/DatabaseSeeder.php`:
   - Resolve manual dengan gabungkan kedua perubahan
   - Test dengan `php artisan migrate:fresh --seed`

---

## 📝 Checklist Sebelum Push

- [ ] Code sudah di-test di local (`php artisan serve`)
- [ ] Tidak ada error di terminal
- [ ] File `.env` TIDAK masuk commit
- [ ] Folder `vendor/` dan `node_modules/` TIDAK masuk commit
- [ ] Migration sudah di-test dengan `php artisan migrate:fresh --seed`
- [ ] Pesan commit jelas dan deskriptif
- [ ] File yang di-commit hanya yang relevan dengan PBI

---

## 🎯 Tips Best Practices

### **1. Naming Convention Branch:**
```
feature/epic-<nomor>-pbi-<nomor>-<deskripsi-singkat>
fix/bug-<deskripsi>
hotfix/critical-<deskripsi>
```

**Contoh:**
- `feature/epic-1-pbi-2-crud-produk`
- `fix/bug-tutor-payment-calculation`
- `hotfix/critical-login-error`

### **2. Commit Message Format:**
```
[Epic X - PBI Y] Deskripsi perubahan

Detail tambahan jika perlu:
- Tambah migration products
- Tambah ProductController dengan method index, create, store
- Tambah view untuk CRUD produk
```

### **3. Pull Request Title:**
```
[Epic 1 - PBI 3] Implementasi CRUD Produk Marketplace
```

### **4. Komunikasi Tim:**
- Gunakan GitHub Issues untuk tracking PBI
- Comment di PR untuk diskusi code review
- Mention anggota tim dengan `@username` untuk notifikasi

### **5. Merge Strategy:**
- Gunakan **Squash and merge** untuk PR besar dengan banyak commit kecil
- Gunakan **Create a merge commit** untuk PR sederhana
- **JANGAN** merge branch sendiri tanpa review (kecuali hotfix critical)

---

## 🚨 Troubleshooting

### **Error: "Class not found" setelah pull**
```bash
composer dump-autoload
php artisan optimize:clear
```

### **Error: "Table doesn't exist" setelah pull**
```bash
php artisan migrate
# atau
php artisan migrate:fresh --seed
```

### **Error: "Mix manifest not found"**
```bash
npm install
npm run dev
```

### **Error: "No application encryption key has been specified"**
```bash
php artisan key:generate
```

---

## 📚 Resource Tambahan

- **Laravel Docs:** https://laravel.com/docs/11.x
- **Git Cheat Sheet:** https://education.github.com/git-cheat-sheet-education.pdf
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Alpine.js:** https://alpinejs.dev/

---

## 🔍 Contoh PBI Mapping ke Folder

| PBI | Files yang Diubah/Dibuat |
|-----|--------------------------|
| CRUD Produk | `Product.php`, `ProductController.php`, `create_products_table.php`, `resources/views/products/*`, `web.php` |
| Tutor Payment Admin | `TutorPayment.php`, `Admin/TutorPaymentController.php`, `create_tutor_payments_table.php`, `resources/views/admin/tutor-payments/*`, `web.php`, `navigation.blade.php` |
| Chat Realtime | `Chat.php`, `ChatController.php`, `resources/views/chat/*`, `socket-server/server.js`, `web.php` |
| Role Middleware | `RoleMiddleware.php`, `bootstrap/app.php`, `web.php` (wrap routes), `navigation.blade.php` (conditional menu) |
| Dashboard User | `DashboardController.php`, `resources/views/dashboard.blade.php` |

---

**Happy Coding! 🚀**

*Last Updated: April 19, 2026*
