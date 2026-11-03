# 🏛️ Kavian — Miras-e Iran

> A bilingual (Persian / English) cultural web portal dedicated to Iranian heritage, art, sports, technology, exports, and landmarks.

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Sub-Applications Status](#-sub-applications-status)
- [Project Structure](#-project-structure)
- [Tech Stack](#️-tech-stack)
- [Database](#️-database)
- [Authentication](#-authentication)
- [Roles & Permissions (RBAC)](#-roles--permissions-rbac)
- [Admin Dashboard](#-admin-dashboard)
- [Dashboard APIs](#-dashboard-apis)
- [Mashahir Sub-App](#-mashahir-sub-app)
- [Error Pages](#-error-pages)
- [Local Setup](#-local-setup)

---

## 🌐 Overview

**Kavian** is a bilingual (Persian / English) web application built with PHP, vanilla JavaScript, MySQL, and Bootstrap 5. It serves as a comprehensive cultural reference portal for Iranian heritage, organized into one main portal and **6 thematic sub-applications**.

| Layer | Path | Description |
|---|---|---|
| **Portal** | `/Kavian/` | Main landing site with auth and category navigation |
| **Mashahir** | `/Kavian/mashahir/` | ✅ Iranian celebrities — complete |
| **Art** | `/Kavian/art/` | 🚧 Iranian art & culture — in development |
| **Sports** | `/Kavian/sports/` | 🔲 Iranian sports — planned |
| **Technology** | `/Kavian/technology/` | 🔲 Iranian tech & innovation — planned |
| **Exports** | `/Kavian/exports/` | 🔲 Iranian exports — planned |
| **Places** | `/Kavian/places/` | 🔲 Historic & natural landmarks — planned |

---

## 🗺️ Sub-Applications Status

### ✅ Mashahir — `/mashahir/`
The most complete sub-app. Fully bilingual (Persian/English) with 8 pages per language version, a book store, shopping cart, simulated payment, PDF download, reviews, favorites, and visit history. All data is DB-driven via 20+ APIs.

### 🚧 Art — `/art/`
The Persian layout is complete (header, hero, filters, categories, reviews, footer). No English version yet. Not yet connected to the database.

### 🔲 Sports / Technology / Exports / Places
Only icons and cards exist on the homepage. No content or pages built yet.

---

## 📁 Project Structure

```
Kavian/
├── index.php                      ← Main portal homepage (Persian, RTL)
├── login.php                      ← Login + register page (tabbed)
├── terms.php                      ← Terms and conditions
├── kavian.sql                     ← Database schema + seed data
├── ERD.png                        ← Entity-relationship diagram
│
├── app/                           ← Shared backend logic
│   ├── config/config.php          ← PDO connection (returns $pdo)
│   ├── auth/
│   │   ├── login.php              ← Authenticates user, loads permissions
│   │   ├── register.php           ← Creates account + auto-login
│   │   ├── logout.php             ← Destroys session + redirect
│   │   └── check_login.php        ← JSON: current session status
│   ├── user/get_user.php          ← JSON: user record from DB
│   ├── profile/update.php         ← Update profile / change password
│   └── errors_helper.php          ← Central abort($code) function
│
├── views/                         ← Shared portal layout
│   ├── header.php
│   ├── hero.php
│   └── footer.php
│
├── public/                        ← Portal static assets
│   ├── css/style.css
│   ├── js/script.js
│   ├── images/                    ← Logo, category icons, hero images
│   └── uploads/avatars/           ← User avatar uploads
│
├── english/                       ← LTR English version of the portal
│   └── index.php
│
├── dashboard/                     ← Unified admin panel (SPA)
│   ├── index.php                  ← SPA for both admins and regular users
│   ├── api/
│   │   ├── auth.php               ← RBAC functions and API guards
│   │   ├── sections_config.php    ← Config for 6 sections + fixed panels
│   │   ├── roles_api.php          ← CRUD roles and permissions
│   │   ├── users_api.php          ← User management + role assignment
│   │   ├── logs_api.php           ← Site log management
│   │   ├── subsites_api.php       ← Sub-site management
│   │   ├── orders_api.php         ← User orders
│   │   ├── mashahir_categories_api.php
│   │   ├── mashahir_celebrities_api.php
│   │   ├── mashahir_products_api.php
│   │   ├── mashahir_reviews_api.php
│   │   ├── mashahir_helpers.php
│   │   ├── generic_api.php
│   │   ├── roles_migration.sql    ← Roles migration v1
│   │   └── roles_v2_migration.sql ← Roles migration v2
│   ├── css/admin.css, profile.css
│   └── js/admin.js, profile.js
│
├── mashahir/                      ← ✅ Celebrities sub-app (complete)
│   ├── index.php                  ← Language router (preferred_language cookie)
│   ├── persian/                   ← RTL Persian version (8 pages)
│   │   ├── index.php, profile.php, pages.php
│   │   ├── cart.php, checkout.php, payment-result.php
│   │   ├── product.php, products.php
│   │   └── script.js, style.css
│   ├── english/                   ← LTR English version (8 pages)
│   ├── includes/                  ← Shared components
│   │   ├── config.php             ← Language detection + t() helper
│   │   ├── header.php, footer.php
│   │   ├── hero.php, filters.php
│   │   ├── categories.php
│   │   └── products.php, reviews.php
│   ├── api/                       ← 20+ Mashahir API files
│   ├── public/                    ← Images and local fonts
│   ├── sendmessage.php            ← Contact form email via PHPMailer
│   └── PHPMailer-master/          ← PHPMailer library
│
├── art/                           ← 🚧 Art sub-app
│   ├── persian/index.php          ← Persian homepage (layout complete)
│   ├── includes/                  ← config, header, hero, filters, ...
│   └── public/                    ← Static assets
│
├── errors/                        ← Custom error pages
│   ├── 400.php ... 504.php        ← 9 error pages
│   └── nginx_errors.conf          ← Nginx error page config
│
└── .htaccess                      ← Apache config
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Server-side | PHP 8.2 |
| Database | MySQL / MariaDB (via PDO) |
| CSS Framework | Bootstrap 5.3 (RTL-capable) |
| Icons | Bootstrap Icons, Font Awesome 6 |
| Fonts (Portal) | Vazirmatn (Google Fonts) |
| Fonts (Mashahir) | ASuls, W_laleh, W_thulth1 (local) |
| Client-side | Vanilla JavaScript (no frameworks) |
| Email | PHPMailer (Mashahir contact form) |
| Web Server | Nginx / Apache |

---

## 🗄️ Database

Database name: `kavian`

### Table: `users`

| Column | Type | Notes |
|---|---|---|
| `id` | `INT` PK, AUTO_INCREMENT | |
| `name` | `VARCHAR(100)` | First name |
| `family` | `VARCHAR(100)` | Last name |
| `email` | `VARCHAR(100)` UNIQUE | |
| `phone` | `VARCHAR(20)` | |
| `password` | `VARCHAR(255)` | bcrypt hash |
| `avatar` | `VARCHAR(255)` | Relative path to avatar file |
| `role` | `VARCHAR(20)` | `'admin'` or `'user'` (legacy field) |
| `custom_role_id` | `INT` FK | References `roles.id` |
| `status` | `TINYINT` | Account status |
| `email_verified` | `TINYINT` | Email verification flag |
| `last_login` | `DATETIME` | Timestamp of last login |
| `created_at` | `TIMESTAMP` | Registration timestamp |
| `updated_at` | `TIMESTAMP` | Last update timestamp |

### Table: `roles`

| Column | Type | Notes |
|---|---|---|
| `id` | `INT` PK | |
| `name` | `VARCHAR(50)` | Role key (e.g. `admin`) |
| `label` | `VARCHAR(100)` | Display label |
| `description` | `TEXT` | |

### Table: `role_permissions`

| Column | Type | Notes |
|---|---|---|
| `role_id` | `INT` FK | References `roles.id` |
| `permission_key` | `VARCHAR(100)` | e.g. `section:celebrities:products` |

---

## 🔐 Authentication

All auth logic lives under `app/auth/`.

### Login Flow

```
POST /app/auth/login.php
        ↓
SELECT users WHERE email = ?
        ↓
password_verify($input, $bcrypt_hash)
        ↓
Success → session_regenerate_id(true)
        → SET SESSION: logged_in, user_id, user_permissions
        → UPDATE users SET last_login = NOW()
        → redirect to destination (or /)
        ↓
Failure → redirect login.php?error=1
```

### Registration Flow

```
POST /app/auth/register.php
        ↓
Validate password match
        ↓
Check for duplicate email
        ↓
password_hash($password, PASSWORD_BCRYPT)
        ↓
INSERT users → auto-login → redirect /
```

### Endpoints

| File | Method | Description |
|---|---|---|
| `app/auth/login.php` | POST | Authenticates with bcrypt |
| `app/auth/register.php` | POST | Creates account + auto-login |
| `app/auth/logout.php` | GET | Destroys session |
| `app/auth/check_login.php` | GET | JSON: `{ logged_in, user }` |
| `app/user/get_user.php` | GET | JSON: `{ success, user }` |
| `app/profile/update.php` | POST | Update profile / change password |

---

## 🎭 Roles & Permissions (RBAC)

The project implements a full database-driven Role-Based Access Control system. **No role is hardcoded** — all access checks read from the `role_permissions` table.

### Permission Key Structure

```
special:*                            ← Full access (super admin)
special:users                        ← Manage users
special:logs                         ← Manage logs
special:subsites                     ← Manage sub-sites
special:roles                        ← Manage roles
special:orders_global                ← Manage all orders

section:celebrities                  ← All celebrities section
section:celebrities:categories       ← Categories
section:celebrities:celebrities      ← Celebrity records
section:celebrities:products         ← View products
section:celebrities:product_register ← Register products
section:celebrities:reviews          ← Reviews
section:celebrities:tickets          ← Tickets

section:art / section:sport / section:places
section:exports / section:technology   ← Each with sub-keys:
    :orders / :comments / :slides / :products / :tickets
```

### Hierarchy Rules

- `special:*` grants access to everything
- Having `section:celebrities` grants access to all its children
- Having `section:celebrities:products` also satisfies a check for `section:celebrities`

### Core Functions (`dashboard/api/auth.php`)

| Function | Description |
|---|---|
| `admin_has_permission($key)` | Checks access with two-way hierarchy |
| `admin_is_super()` | Returns true if user has `special:*` |
| `admin_load_role_permissions($pdo, $roleId)` | Loads permissions from DB into session |
| `admin_require_login_api()` | Guard: must be logged in |
| `admin_require_permission_api($key)` | Guard: must have specific permission |
| `admin_require_super_api()` | Guard: must have `special:*` |

---

## 🖥️ Admin Dashboard

`dashboard/index.php` is a unified SPA that serves:
- **Regular users**: tabs for profile, password change, orders, favorites, visit history
- **Admins**: full management sidebar + content panels

### Sidebar Structure (3 levels)

```
👤 My Profile
    ├── Edit Profile
    ├── Change Password
    ├── My Orders
    ├── Favorites
    └── Visit History

⚙️ Management
    ├── Site Management  (requires special:*)
    │   ├── Users
    │   ├── Logs
    │   ├── Sub-sites
    │   ├── Roles
    │   └── Global Orders
    │
    ├── Celebrities Management
    │   ├── Categories
    │   ├── Celebrities
    │   ├── Products (view / register)
    │   ├── Reviews
    │   └── Tickets
    │
    └── Other Sections (Art, Sports, ...)
        ├── Orders
        ├── Comments
        ├── Slides
        ├── Products
        └── Tickets
```

Each sidebar item is rendered only if the user holds the corresponding permission key (`can_see()`). On every page load, `ensure_base_roles()` runs an automatic migration to create the base `admin` and `user` roles if they don't yet exist.

---

## 📡 Dashboard APIs

| File | Guard | Description |
|---|---|---|
| `roles_api.php` | `special:roles` | CRUD roles, assign to users, permission schema |
| `users_api.php` | `special:users` | List / edit / delete users, assign roles |
| `logs_api.php` | `special:*` | Read and clear `site_logs` |
| `subsites_api.php` | `special:*` | CRUD sub-sites |
| `orders_api.php` | login | Current user's orders |
| `mashahir_categories_api.php` | login | CRUD celebrity categories |
| `mashahir_celebrities_api.php` | login | CRUD celebrities + category links |
| `mashahir_products_api.php` | login | CRUD books (title, publisher, price, stock, PDF) |
| `mashahir_reviews_api.php` | login | Manage reviews (pending / approved / rejected) |

---

## ⭐ Mashahir Sub-App

### Language Router

`mashahir/index.php` reads the `preferred_language` cookie:
- `en` → `english/index.php`
- anything else → `persian/index.php` (default)

### Pages (per language version)

| Page | Description |
|---|---|
| `index.php` | Homepage: hero, filters, categories, products, reviews |
| `profile.php` | Celebrity browser — list view + full detail view |
| `products.php` | Book store |
| `product.php` | Single product page + reviews + Add to Cart |
| `cart.php` | Shopping cart |
| `checkout.php` | Checkout |
| `payment-result.php` | Payment result |
| `pages.php` | Static pages (About, Contact) |

### Mashahir APIs (`mashahir/api/`)

20+ files including: `get-celebrities`, `get-products`, `cart`, `checkout`, `payment-simulate`, `download-pdf`, `favorites`, `history`, `search`, `submit-review`, `get-product-reviews`, `user-stats`, `sendmessage`, and more.

### Shared Includes

| File | Responsibility |
|---|---|
| `config.php` | Language detection from URI, `$dir` var, `t()` translation helper |
| `header.php` | Bootstrap 5 navbar with search, auth buttons, offcanvas menu |
| `hero.php` | Auto-rotating carousel with featured figures |
| `categories.php` | 12-card category icon grid |
| `filters.php` | Collapsible filter bar with checkboxes and price range |
| `products.php` | Featured book carousel |
| `reviews.php` | User review carousel with stars and likes |

---

## 🚨 Error Pages

Custom error pages in `errors/` with a consistent design:

| Code | File | Description |
|---|---|---|
| 400 | `400.php` | Bad Request |
| 401 | `401.php` | Unauthorized (includes post-login redirect link) |
| 403 | `403.php` | Forbidden |
| 404 | `404.php` | Not Found |
| 429 | `429.php` | Too Many Requests |
| 500 | `500.php` | Internal Server Error |
| 502 | `502.php` | Bad Gateway |
| 503 | `503.php` | Service Unavailable |
| 504 | `504.php` | Gateway Timeout |

The `abort(int $code)` function in `app/errors_helper.php` is used across the entire project to redirect to these pages.

---

## 🚀 Local Setup

**Requirements:** PHP 8.x, MySQL / MariaDB, Apache or Nginx

```bash
# 1. Place the project under your web root
#    e.g. /srv/http/Kavian  or  C:\wamp64\www\Kavian

# 2. Import the database
#    phpMyAdmin → create database "kavian" → import kavian.sql

# 3. Verify DB credentials in app/config/config.php
$host     = 'localhost';
$dbname   = 'kavian';
$username = 'root';
$password = '';

# 4. Run role migrations (optional — auto-runs on first dashboard load)
#    dashboard/api/roles_migration.sql
#    dashboard/api/roles_v2_migration.sql

# 5. Access the project
http://localhost/Kavian/               ← Persian portal
http://localhost/Kavian/english/       ← English portal
http://localhost/Kavian/mashahir/      ← ✅ Mashahir (complete)
http://localhost/Kavian/art/persian/   ← 🚧 Art (in development)
http://localhost/Kavian/dashboard/     ← Admin dashboard
```

### Upload directory permissions

```bash
chmod 755 public/uploads/
chmod 755 public/uploads/avatars/
```

---

<div align="center">
  <sub>Built with ❤️ for Iranian culture and heritage · © 1405</sub>
</div>

---

## 👥 Contributors

<br />

<p align="center">People who built this project together.</p>

<br />

<table align="center">
  <tr>
    <td align="center" style="padding: 16px;">
      <a href="https://github.com/bootless64">
        <img src="https://github.com/bootless64.png?size=120" width="120" style="border-radius:50%" /><br /><br />
        <b>bootless64</b><br />
        <sub>🛠️ Lead Developer</sub>
      </a>
    </td>
    <td align="center" style="padding: 16px;">
      <a href="https://github.com/sajdavy-hub">
        <img src="https://github.com/sajdavy-hub.png?size=120" width="120" style="border-radius:50%" /><br /><br />
        <b>sajdavy-hub</b><br />
        <sub>🩹 Developer</sub>
      </a>
    </td>
    <td align="center" style="padding: 16px;">
      <a href="https://github.com/niloufar-kashefi">
        <img src="https://github.com/niloufar-kashefi.png?size=120" width="120" style="border-radius:50%" /><br /><br />
        <b>niloufar-kashefi</b><br />
        <sub>👑 Owner</sub>
      </a>
    </td>
  </tr>
</table>

<br />
