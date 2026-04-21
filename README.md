# 📚 Learn Everything

**A unified learning ecosystem** — Course platform, tutor booking, community forum, marketplace, and real-time chat in one monolithic Laravel application.

---

## 🧱 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+, MySQL |
| Auth | Laravel Breeze + Sanctum |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Realtime | Socket.IO (Node.js) |
| Architecture | Monolithic 3-tier (Presentation → Logic → Data) |

---

## 📦 Project Structure Overview

```
learn-everything/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CourseController.php
│   │   │   ├── ModuleController.php
│   │   │   ├── EnrollmentController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── QuizController.php
│   │   │   ├── TutorController.php
│   │   │   ├── BookingController.php
│   │   │   ├── TutorReviewController.php
│   │   │   ├── ForumThreadController.php
│   │   │   ├── ForumReplyController.php
│   │   │   ├── ChatController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   ├── SubscriptionController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── AddressController.php
│   │   │   ├── CertificateController.php
│   │   │   └── Api/
│   │   │       └── ChatApiController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── StoreBookingRequest.php
│   │       ├── StoreCourseRequest.php
│   │       ├── StoreProductRequest.php
│   │       ├── StoreForumThreadRequest.php
│   │       ├── StoreQuizAnswerRequest.php
│   │       ├── UpdateProfileRequest.php
│   │       └── ...
│   ├── Models/
│   │   ├── User.php
│   │   ├── Tutor.php
│   │   ├── Admin.php
│   │   ├── Course.php
│   │   ├── Module.php
│   │   ├── Quiz.php
│   │   ├── QuizQuestion.php
│   │   ├── QuizAttempt.php
│   │   ├── Enrollment.php
│   │   ├── ModuleProgress.php
│   │   ├── ForumThread.php
│   │   ├── ForumReply.php
│   │   ├── ForumLike.php
│   │   ├── Chat.php
│   │   ├── Product.php
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   ├── Subscription.php
│   │   ├── Transaction.php
│   │   ├── HomeVisitBooking.php
│   │   ├── TutorReview.php
│   │   ├── Address.php
│   │   └── Certificate.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── components/
│   │   ├── navbar.blade.php
│   │   └── footer.blade.php
│   ├── landing.blade.php
│   ├── dashboard.blade.php
│   ├── courses/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── learn.blade.php
│   ├── tutors/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── booking.blade.php
│   ├── forum/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── create.blade.php
│   ├── chat/
│   │   └── index.blade.php
│   ├── marketplace/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── cart/
│   │   └── index.blade.php
│   ├── subscriptions/
│   │   └── index.blade.php
│   ├── checkout/
│   │   └── index.blade.php
│   ├── profile/
│   │   ├── edit.blade.php
│   │   ├── addresses.blade.php
│   │   └── history.blade.php
│   └── certificates/
│       └── pdf.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── socket-server/
│   ├── package.json
│   └── server.js
└── ...
```

---

## 🎯 Product Backlog Items (PBI) — File Reference

### EPIC 1 — Course Exploration

#### PBI 1: Course Catalog
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/CourseController.php` → `index()` |
| View | `resources/views/courses/index.blade.php` |
| Model | `app/Models/Course.php` |
| Route | `GET /courses` |

#### PBI 2: Course Detail & Syllabus
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/CourseController.php` → `show()` |
| View | `resources/views/courses/show.blade.php` |
| Model | `app/Models/Module.php` |
| Route | `GET /courses/{course}` |

#### PBI 3: Search & Filter
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/CourseController.php` → `index()` (query params) |
| View | `resources/views/courses/index.blade.php` (search form) |
| Route | `GET /courses?search=&category=&min_price=&max_price=` |

---

### EPIC 2 — Learning System

#### PBI 4: Dashboard Progress
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/DashboardController.php` |
| View | `resources/views/dashboard.blade.php` |
| Model | `app/Models/Enrollment.php`, `app/Models/ModuleProgress.php` |
| Route | `GET /dashboard` |

#### PBI 5: Learning Page
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ModuleController.php` → `learn()` |
| View | `resources/views/courses/learn.blade.php` |
| Model | `app/Models/ModuleProgress.php` |
| Route | `GET /courses/{course}/learn/{module}` |

#### PBI 6: Quiz System
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/QuizController.php` |
| View | `resources/views/courses/quiz.blade.php` |
| Model | `app/Models/Quiz.php`, `app/Models/QuizQuestion.php`, `app/Models/QuizAttempt.php` |
| Request | `app/Http/Requests/StoreQuizAnswerRequest.php` |
| Route | `GET /quizzes/{quiz}`, `POST /quizzes/{quiz}/submit` |

---

### EPIC 3 — Tutor & Home Visit

#### PBI 7: Tutor List & Profile
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/TutorController.php` |
| View | `resources/views/tutors/index.blade.php`, `resources/views/tutors/show.blade.php` |
| Model | `app/Models/Tutor.php` |
| Route | `GET /tutors`, `GET /tutors/{tutor}` |

#### PBI 8: Booking System
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/BookingController.php` |
| View | `resources/views/tutors/booking.blade.php` |
| Model | `app/Models/HomeVisitBooking.php` |
| Request | `app/Http/Requests/StoreBookingRequest.php` |
| Route | `GET /tutors/{tutor}/book`, `POST /bookings` |

#### PBI 9: Tutor Review
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/TutorReviewController.php` |
| View | `resources/views/tutors/show.blade.php` (review form embedded) |
| Model | `app/Models/TutorReview.php` |
| Route | `POST /tutors/{tutor}/reviews` |



### EPIC 4 — Community & Chat

#### PBI 10: Forum Thread
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ForumThreadController.php` |
| View | `resources/views/forum/index.blade.php`, `resources/views/forum/create.blade.php` |
| Model | `app/Models/ForumThread.php` |
| Request | `app/Http/Requests/StoreForumThreadRequest.php` |
| Route | `GET /forum`, `POST /forum` |

#### PBI 11: Forum Interaction
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ForumReplyController.php` |
| View | `resources/views/forum/show.blade.php` |
| Model | `app/Models/ForumReply.php`, `app/Models/ForumLike.php` |
| Route | `POST /forum/{thread}/replies`, `POST /forum/replies/{reply}/like` |

#### PBI 12: Live Chat (Socket.IO)
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ChatController.php`, `app/Http/Controllers/Api/ChatApiController.php` |
| View | `resources/views/chat/index.blade.php` |
| Model | `app/Models/Chat.php` |
| Socket Server | `socket-server/server.js` |
| Route (Web) | `GET /chat`, `GET /chat/{user}` |
| Route (API) | `GET /api/chats/{user}`, `POST /api/chats` |


### EPIC 5 — Marketplace

#### PBI 13: Product Catalog
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ProductController.php` → `index()` |
| View | `resources/views/marketplace/index.blade.php` |
| Model | `app/Models/Product.php` |
| Route | `GET /marketplace` |

#### PBI 14: Upload Product
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ProductController.php` → `create()`, `store()` |
| View | `resources/views/marketplace/create.blade.php` |
| Request | `app/Http/Requests/StoreProductRequest.php` |
| Route | `GET /marketplace/create`, `POST /marketplace` |

#### PBI 15: Manage Product
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ProductController.php` → `edit()`, `update()`, `destroy()` |
| View | `resources/views/marketplace/edit.blade.php` |
| Route | `GET /marketplace/{product}/edit`, `PUT /marketplace/{product}`, `DELETE /marketplace/{product}` |

---

### EPIC 6 — Transaction

#### PBI 16: Subscription Packages
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/SubscriptionController.php` |
| View | `resources/views/subscriptions/index.blade.php` |
| Model | `app/Models/Subscription.php` |
| Route | `GET /subscriptions` |

#### PBI 17: Cart System
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/CartController.php` |
| View | `resources/views/cart/index.blade.php` |
| Model | `app/Models/Cart.php`, `app/Models/CartItem.php` |
| Route | `GET /cart`, `POST /cart/add`, `DELETE /cart/{item}` |

#### PBI 18: Checkout
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/CheckoutController.php` |
| View | `resources/views/checkout/index.blade.php` |
| Model | `app/Models/Transaction.php` |
| Route | `GET /checkout`, `POST /checkout/process` |

---

### EPIC 7 — User Profile

#### PBI 19: Profile Management
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/ProfileController.php` |
| View | `resources/views/profile/edit.blade.php` |
| Request | `app/Http/Requests/UpdateProfileRequest.php` |
| Route | `GET /profile`, `PUT /profile` |

#### PBI 20: Address Management
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/AddressController.php` |
| View | `resources/views/profile/addresses.blade.php` |
| Model | `app/Models/Address.php` |
| Route | `GET /addresses`, `POST /addresses`, `PUT /addresses/{address}`, `DELETE /addresses/{address}` |

#### PBI 21: History & Certificate
| Type | File |
|------|------|
| Controller | `app/Http/Controllers/CertificateController.php` |
| View | `resources/views/profile/history.blade.php`, `resources/views/certificates/pdf.blade.php` |
| Model | `app/Models/Certificate.php` |
| Route | `GET /history`, `GET /certificates/{enrollment}/download` |

---

## 🚀 Installation & Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL 8.0+
- XAMPP / Laragon (or any local server)

### Step-by-step

```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/learn-everything.git
cd learn-everything

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure .env (set DB credentials)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=learn_everything
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Create MySQL database
mysql -u root -e "CREATE DATABASE learn_everything;"

# 7. Run migrations
php artisan migrate

# 8. Seed database (optional)
php artisan db:seed

# 9. Create storage link
php artisan storage:link

# 10. Install Node dependencies
npm install

# 11. Build assets
npm run build
# or for development:
npm run dev

# 12. Start Socket.IO server (separate terminal)
cd socket-server
npm install
node server.js

# 13. Start Laravel server
php artisan serve
```

Open: http://localhost:8000

---

## 🗄️ Database Schema (ERD Summary)

```
users ─────────── 1:1 ──── tutors
  │                          │
  │                          ├── courses ──── modules ──── quizzes ──── quiz_questions
  │                          │                  │
  │                          │           module_progress
  │                          │
  │                          ├── tutor_reviews
  │                          └── home_visit_bookings
  │
  ├── enrollments ──── courses
  ├── quiz_attempts
  ├── forum_threads ──── forum_replies ──── forum_likes
  ├── chats (sender/receiver)
  ├── products (seller)
  ├── carts ──── cart_items
  ├── transactions
  ├── subscriptions
  ├── addresses
  └── certificates
```

---

## 🔐 Roles & Access

| Role | Access |
|------|--------|
| `user` | Browse courses, enroll, learn, forum, chat, marketplace (buy), profile |
| `tutor` | All user features + create/manage courses & modules, manage bookings, view students |
| `admin` | Full access + manage all courses/modules, verify tutors, manage users, view transactions & revenue dashboard |

### Admin Panel Routes (prefix: `/admin`)
- `/admin/courses` — CRUD courses & modules, auto-generate quiz per module
- `/admin/tutors` — View & verify/reject tutor applications
- `/admin/users` — List & manage all users
- `/admin/transactions` — View all transactions & revenue

### Tutor Panel Routes (prefix: `/tutor`)
- `/tutor/courses/create` — Create new course
- `/tutor/courses/{course}/modules` — Manage modules (auto quiz generation)
- `/tutor/courses/{course}/publish` — Publish/unpublish course

### Subscription Payment Flow
- `/subscriptions` — View packages (Basic Rp 99.000 / Premium Rp 199.000)
- `/subscriptions/payment/{package}` — Choose payment method
- `/subscriptions/process` — Process payment → create transaction → activate subscription

### Test Accounts (after `php artisan db:seed`)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@learn.test` (or via `.env` ADMIN_EMAIL) | `admin123` (or via `.env` ADMIN_PASSWORD) |
| Tutor | `budi@learn.test` | `password` |
| Tutor | `siti@learn.test` | `password` |
| User | `dewi@learn.test` | `password` |
| User | `rizky@learn.test` | `password` |

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 📡 API Endpoints (Sanctum)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Get token |
| GET | `/api/chats/{user}` | Get chat messages |
| POST | `/api/chats` | Send chat message |
| GET | `/api/courses` | List courses |

---

## 💡 Git Workflow & Commit Tips

### Initial Setup

```bash
# Initialize git
git init

# Create .gitignore (Laravel already includes one)
# Add all files
git add .
git commit -m "chore: initial Laravel project setup"

# Create remote repository on GitHub
# Then link it:
git remote add origin https://github.com/YOUR_USERNAME/learn-everything.git
git branch -M main
git push -u origin main
```

### Commit Convention (Recommended)

Use **Conventional Commits** format:

```
<type>(<scope>): <description>

Types:
  feat     → New feature
  fix      → Bug fix
  docs     → Documentation
  style    → Formatting (no code change)
  refactor → Code restructuring
  test     → Adding tests
  chore    → Maintenance tasks
```

### Recommended Commit Flow per PBI

```bash
# PBI 1: Course Catalog
git add database/migrations/*courses* app/Models/Course.php
git commit -m "feat(db): add courses migration and model"

git add app/Http/Controllers/CourseController.php routes/web.php
git commit -m "feat(course): add course controller and routes"

git add resources/views/courses/index.blade.php
git commit -m "feat(ui): add course catalog page"

# PBI 2: Course Detail
git add app/Models/Module.php database/migrations/*modules*
git commit -m "feat(db): add modules migration and model"

git add resources/views/courses/show.blade.php
git commit -m "feat(ui): add course detail page with syllabus"

# ... continue per PBI
```

### Branching Strategy

```bash
# Create feature branch per EPIC
git checkout -b feature/epic-1-course-exploration

# Work on PBI 1, 2, 3...
git add . && git commit -m "feat(course): complete PBI 1 - course catalog"

# When EPIC is done, merge to main
git checkout main
git merge feature/epic-1-course-exploration
git push origin main

# Repeat for each EPIC
git checkout -b feature/epic-2-learning-system
# ...
```

### Tips

1. **Commit often** — Small, focused commits are easier to review
2. **Never commit `.env`** — It's already in `.gitignore`
3. **Write meaningful messages** — Future you will thank present you
4. **Tag releases** — `git tag v1.0.0` after major milestones
5. **Use `.gitignore`** — Ensure `vendor/`, `node_modules/`, `.env` are excluded

---

## 📝 License

This project is for educational purposes.

---

## 👥 Authors

- **Your Name** — Full Stack Developer

---

*Built with ❤️ using Laravel, Tailwind CSS, Alpine.js & Socket.IO*
