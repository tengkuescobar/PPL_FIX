# EPIC 4 – Komunikasi & Komunitas
##--##
**PBI:** PBI-10 (Buat Topik Forum) · PBI-11 (Reply & Like) · PBI-12 (Live Chat)

---

## PBI-10 – Buat Topik Forum

### BE

**Migrations**
```
database/migrations/2026_04_16_200010_create_forum_threads_table.php
```
> ForumThread: `user_id`, `title`, `body`, `is_pinned`.

**Models**
```
app/Models/ForumThread.php   ← belongsTo User, hasMany ForumReply
```

**Controllers**
```
app/Http/Controllers/ForumThreadController.php
    index()   → list semua thread
    create()  → form buat thread baru
    store()   → simpan thread baru

app/Http/Controllers/Api/ForumApiController.php
    threads() → endpoint AJAX list thread
```

**Requests**
```
app/Http/Requests/StoreForumThreadRequest.php   ← validasi judul & isi thread
```

### FE

**Views**
```
resources/views/forum/index.blade.php    ← daftar semua thread + tombol buat baru
resources/views/forum/create.blade.php   ← form buat thread baru (judul + isi)
```

**Routes**
```php
// Public
Route::get('/forum', [ForumThreadController::class, 'index'])->name('forum.index');

// Auth
Route::get('/forum-create', [ForumThreadController::class, 'create'])->name('forum.create');
Route::post('/forum', [ForumThreadController::class, 'store'])->name('forum.store');
```

### Checklist
- [ ] Form buat thread tampil & tersimpan, muncul di daftar forum

---

## PBI-11 – Reply & Like

### BE

**Migrations**
```
database/migrations/2026_04_16_200011_create_forum_replies_table.php
database/migrations/2026_04_16_200012_create_forum_likes_table.php
```
> ForumReply: `user_id`, `thread_id`, `body`.  
> ForumLike: `user_id`, `reply_id` (unique per user per reply).

**Models**
```
app/Models/ForumReply.php   ← belongsTo User, belongsTo ForumThread, hasMany ForumLike
app/Models/ForumLike.php    ← belongsTo User, belongsTo ForumReply
```

**Controllers**
```
app/Http/Controllers/ForumThreadController.php
    show()        → detail thread + semua reply

app/Http/Controllers/ForumReplyController.php
    store()       → tambah reply ke thread
    toggleLike()  → toggle like/unlike sebuah reply

app/Http/Controllers/Api/ForumApiController.php
    replies(), like() → endpoint AJAX reply & like
```

### FE

**Views**
```
resources/views/forum/show.blade.php   ← isi thread + form reply + tombol like (toggle, boleh AJAX)
```

**Routes**
```php
// Public
Route::get('/forum/{thread}', [ForumThreadController::class, 'show'])->name('forum.show');

// Auth
Route::post('/forum/{thread}/replies', [ForumReplyController::class, 'store'])->name('forum.replies.store');
Route::post('/forum/replies/{reply}/like', [ForumReplyController::class, 'toggleLike'])->name('forum.replies.like');
```

### Checklist
- [ ] Form reply berfungsi, tombol like toggle (boleh AJAX), hitungan like update

---

## PBI-12 – Live Chat

### BE

**Migrations**
```
database/migrations/2026_04_16_200013_create_chats_table.php
database/migrations/2026_04_21_063644_add_attachment_to_chats_table.php
```
> Chat: `sender_id`, `receiver_id`, `message`, `is_read`, `attachment` (nullable), `attachment_name` (nullable).

**Models**
```
app/Models/Chat.php   ← belongsTo sender (User), belongsTo receiver (User)
                        fillable: sender_id, receiver_id, message, is_read, attachment, attachment_name
```

**Controllers**
```
app/Http/Controllers/ChatController.php
    index()   → daftar kontak chat; sidebar: chat partners + tutor approved + seller aktif
    show()    → percakapan dengan user/tutor/seller tertentu
    send()    → kirim pesan + opsional file attachment (multipart/form-data, max 10MB); return JSON

app/Http/Controllers/Api/ChatApiController.php
    messages() → ambil history pesan via AJAX/polling
    send()     → kirim pesan via AJAX
```

**WebSocket Server (Node.js)**
```
socket-server/server.js      ← server Socket.io utama
socket-server/package.json   ← dependencies: express, socket.io, cors
```

| Event (emit dari client) | Handler di server            | Broadcast ke                          |
|--------------------------|------------------------------|---------------------------------------|
| `register`               | simpan userId → socketId map | semua: `online-users`                 |
| `join-chat-room`         | `socket.join(roomName)`      | —                                     |
| `leave-chat-room`        | `socket.leave(roomName)`     | —                                     |
| `send-message`           | —                            | room: `new-message` + `message-notification` |
| `disconnect`             | hapus dari map               | semua: `online-users` update          |

### FE

**Views**
```
resources/views/chat/index.blade.php   ← UI live chat full-height
                                         sidebar: chat partners + "Chat Marketplace" (tutor & penjual)
                                         area pesan scrollable, file attachment upload, input sticky di bawah
```

**Integrasi Socket.io di Blade**
```html
<script src="http://127.0.0.1:3000/socket.io/socket.io.js"></script>
<script>
  const socket = io('http://127.0.0.1:3000');
  socket.emit('register', {{ auth()->id() }});
  // join room, send-message, listen new-message
</script>
```

**Routes**
```php
// Auth
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat/{receiver}', [ChatController::class, 'show'])->name('chat.show');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
```

**Menjalankan Socket Server**
```bash
cd socket-server
npm install
node server.js   # berjalan di port 3000
```

### Checklist
- [ ] UI chat real-time via Socket.io, pesan muncul tanpa reload, indikator online

