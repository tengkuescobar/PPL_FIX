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