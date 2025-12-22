# 🛠️ Laravel 12 - Lead & Site Management System

A Laravel based web application with authentication, role-based access (SuperAdmin, Admin, User), and mobile-friendly UI using Tailwind CSS.

---

## 📦 Features

- Laravel 12
- Laravel Breeze (Auth scaffolding)
- Role-based access control (`superadmin`, `admin`, `user`)
- Custom `RoleMiddleware`
- Tailwind CSS layout
- Admin/SuperAdmin protected dashboards

---

## 🚀 Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/your-username/project-name.git
cd project-name
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
```
### 2. Configure .env database settings
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```
### 3.  Run 

```bash
php artisan migrate
php artisan serve
php artisan route:store
```