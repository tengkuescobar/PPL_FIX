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