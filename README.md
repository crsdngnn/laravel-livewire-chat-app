# Laravel Livewire Chat App

A realtime chat application built with **Laravel**, **Livewire**, and **Pusher**.  
This README includes complete setup instructions — from cloning to deployment — designed for developers who want a ready-to-run chat system using Laravel’s modern stack.

---

## Features
- Realtime chat using **Pusher** and **Laravel Echo**
- Reactive components powered by **Livewire**
- Authentication via **Jetstream**
- Database migrations and seeders
- Queue support for broadcasting
- Clean, modular Laravel architecture

---

## Tech Stack
- **PHP** (Laravel 10+)
- **Laravel Livewire**
- **Pusher**
- **MySQL / PostgreSQL**
- **Composer**
- **Node.js & npm**
- **TailwindCSS**

---

## Requirements

Before starting, ensure you have installed:

| Tool | Version |
| PHP | ≥ 8.1 |
| Composer | latest |
| Node.js | ≥ 16 |
| npm | ≥ 8 |
| MySQL | latest |
| Git | latest |
| Pusher Account | App ID, Key, Secret, Cluster |

---

## 🪜 Installation Steps

### Step 1 – Clone the Repository
```bash
git clone https://github.com/crsdngnn/laravel-livewire-chat-app.git
cd laravel-livewire-chat-app
```
### Step 2 – Install PHP Dependencies
```bash
composer install
```

### Step 3 – Install Node Dependencies
```bash
npm install
```

### Step 4 – Create Environment File
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4 – Create Environment File
```bash
cp .env.example .env
php artisan key:generate
```

```bash
Then open .env and configure your environment:
APP_NAME="Laravel Livewire Chat"
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=pusher

PUSHER_APP_ID=2071971
PUSHER_APP_KEY=34cce07541fc96e90c3c
PUSHER_APP_SECRET=6f9f27187ca83809aec1
PUSHER_APP_CLUSTER=ap1
PUSHER_SCHEME=https
PUSHER_PORT=443
PUSHER_HOST=
```

### Step 5 – Migrate and Seed the Database
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```
### Step 6 – Migrate and Seed the Database
```bash
npm run dev
```

### Step 7 – Run the Development Server
```bash
php artisan optimize:clear
php artisan serve

