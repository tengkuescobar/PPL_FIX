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