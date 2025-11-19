# 🌍 Tour Manager - Hệ Thống Quản Lý Tour Du Lịch

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Hệ thống quản lý tour du lịch toàn diện với các tính năng đặt tour, thanh toán trực tuyến, quản lý booking, đánh giá và báo cáo chi tiết.

## 📋 Mục Lục

- [Tính Năng](#-tính-năng)
- [Yêu Cầu Hệ Thống](#-yêu-cầu-hệ-thống)
- [Cài Đặt](#-cài-đặt)
- [Cấu Hình](#-cấu-hình)
- [Chạy Ứng Dụng](#-chạy-ứng-dụng)
- [Queue & Jobs](#-queue--jobs)
- [Email Configuration](#-email-configuration)
- [Payment Gateway](#-payment-gateway)
- [Database Seeding](#-database-seeding)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)

## ✨ Tính Năng

### 🎯 Khách Hàng
- 🔍 Tìm kiếm và lọc tour theo điểm đến, giá, thời gian
- 📅 Đặt tour online với nhiều loại vé (người lớn, trẻ em, em bé)
- 💳 Thanh toán qua VNPay, PayPal, hoặc Mock Payment
- 📧 Nhận email xác nhận booking và thanh toán
- ⭐ Đánh giá và review tour đã tham gia
- 📱 Quản lý lịch sử booking cá nhân
- 🔐 Đăng nhập với Google OAuth

### 👨‍💼 Admin
- 📊 Dashboard tổng quan với thống kê real-time
- 🗺️ Quản lý điểm đến (CRUD với Markdown support)
- 🎫 Quản lý tour (CRUD, images, itinerary)
- 📝 Quản lý bookings (xác nhận, hủy, xuất invoice)
- 💰 Quản lý payments (theo dõi, hoàn tiền)
- 👥 Quản lý users (phân quyền admin/customer)
- ⭐ Kiểm duyệt reviews
- 📈 Báo cáo chi tiết (doanh thu, bookings, tours, customers)
- 📄 Xuất PDF reports
- 📧 Email templates và bulk email
- 🔔 Activity logs đầy đủ
- ⚙️ Settings quản lý cấu hình

### 🔧 Kỹ Thuật
- 🎨 Tailwind CSS với Alpine.js
- 🔒 Route model binding với custom keys (booking_code, slug)
- 📨 Queue jobs cho email và background tasks
- 🗄️ Database optimization với indexes
- 🔐 Middleware authentication & authorization
- 📝 Markdown parser cho nội dung
- 🤖 Gemini AI integration (tùy chọn)
- 📊 PDF generation với DomPDF

## 💻 Yêu Cầu Hệ Thống

- **PHP:** >= 8.2
- **Composer:** >= 2.x
- **Node.js:** >= 18.x
- **NPM/Yarn:** Latest
- **MySQL:** >= 8.0 (hoặc MariaDB >= 10.3)
- **Extensions:** BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🚀 Cài Đặt

### 1. Clone Repository

```bash
git clone https://github.com/thanhphandev/tour-manager.git
cd tour-manager
```

### 2. Cài Đặt Dependencies

```bash
# Backend dependencies
composer install

# Frontend dependencies
npm install
```

### 3. Tạo File Environment

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Tạo Database

Tạo database MySQL:

```sql
CREATE DATABASE tour_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Cấu Hình Database trong `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tour_manager
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 7. Chạy Migrations

```bash
php artisan migrate
```

### 8. (Tùy chọn) Seed Database với Dữ Liệu Mẫu

```bash
php artisan db:seed
```

### 9. Tạo Symbolic Link cho Storage

```bash
php artisan storage:link
```

### 10. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

## ⚙️ Cấu Hình

### 📧 Email Configuration

#### Option 1: Mailtrap (Development)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tourmanager.com"
MAIL_FROM_NAME="Tour Manager"
```

#### Option 2: Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="Tour Manager"
```

> **Lưu ý:** Với Gmail, bạn cần tạo [App Password](https://myaccount.google.com/apppasswords)

#### Option 3: SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tourmanager.com"
MAIL_FROM_NAME="Tour Manager"
```

### 💳 Payment Gateway Configuration

#### VNPay

1. Đăng ký tài khoản tại [VNPay Sandbox](https://sandbox.vnpayment.vn/)
2. Lấy thông tin `TMN Code` và `Hash Secret`
3. Cấu hình trong `.env`:

```env
# VNPay Configuration
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL=http://localhost:8000/payments/vnpay/callback
VNPAY_API_URL=https://sandbox.vnpayment.vn/merchant_webapi/api/transaction
```

**Production:**
```env
VNPAY_URL=https://vnpayment.vn/paymentv2/vpcpay.html
VNPAY_API_URL=https://vnpayment.vn/merchant_webapi/api/transaction
```

#### PayPal

1. Đăng ký tài khoản tại [PayPal Developer](https://developer.paypal.com/)
2. Tạo App và lấy `Client ID` và `Secret`
3. Cấu hình trong `.env`:

```env
# PayPal Configuration
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_CURRENCY=USD
PAYPAL_EXCHANGE_RATE=24000
PAYPAL_RETURN_URL=http://localhost:8000/payments/paypal/callback
PAYPAL_CANCEL_URL=http://localhost:8000/payments/paypal/cancel
```

**Production:**
```env
PAYPAL_MODE=live
```

### 🔐 Google OAuth (Đăng Nhập với Google)

1. Tạo project tại [Google Cloud Console](https://console.cloud.google.com/)
2. Bật Google+ API
3. Tạo OAuth 2.0 credentials
4. Cấu hình trong `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Authorized redirect URIs trong Google Console:**
```
http://localhost:8000/auth/google/callback
https://yourdomain.com/auth/google/callback
```

### 📦 Queue Configuration

```env
# Queue
QUEUE_CONNECTION=database

# Tùy chọn: Redis (khuyến nghị cho production)
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 🗄️ Session & Cache

```env
# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Tùy chọn: Redis (khuyến nghị cho production)
CACHE_STORE=redis
```

## 🏃 Chạy Ứng Dụng

### Development Mode (Tất Cả trong 1 Terminal)

```bash
composer dev
```

Lệnh này sẽ chạy đồng thời:
- 🌐 Laravel server (`php artisan serve`)
- ⚡ Vite dev server (`npm run dev`)
- 📨 Queue worker (`php artisan queue:listen`)

### Hoặc Chạy Riêng Từng Service

**Terminal 1: Laravel Server**
```bash
php artisan serve
```

**Terminal 2: Vite (Frontend Hot Reload)**
```bash
npm run dev
```

**Terminal 3: Queue Worker**
```bash
php artisan queue:work
# Hoặc với retry và timeout
php artisan queue:work --tries=3 --timeout=90
```

### Production Mode

```bash
# Build assets
npm run build

# Start server (với process manager như Supervisor)
php artisan serve --host=0.0.0.0 --port=8000

# Queue worker (nên dùng Supervisor)
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Ứng dụng sẽ chạy tại: http://localhost:8000

## 📨 Queue & Jobs

Hệ thống sử dụng Laravel Queue để xử lý các tác vụ nền:

### Jobs Được Sử Dụng

- ✉️ **SendBookingConfirmationEmail**: Gửi email xác nhận booking
- ✉️ **SendPaymentConfirmationEmail**: Gửi email xác nhận thanh toán
- ✉️ **SendBookingCancellationEmail**: Gửi email hủy booking
- ✉️ **SendTourReminderEmail**: Gửi email nhắc nhở trước tour
- ✉️ **SendReviewRequestEmail**: Gửi email yêu cầu đánh giá

### Chạy Queue Worker

#### Development

```bash
# Chạy và tự động restart khi có code changes
php artisan queue:listen

# Xem logs chi tiết
php artisan queue:listen --verbose
```

#### Production (với Supervisor)

Tạo file `/etc/supervisor/conf.d/tour-manager-worker.conf`:

```ini
[program:tour-manager-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/tour-manager/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/tour-manager/storage/logs/worker.log
stopwaitsecs=3600
```

Reload Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tour-manager-worker:*
```

### Kiểm Tra Queue

```bash
# Xem jobs trong queue
php artisan queue:monitor

# Xem failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry {job_id}

# Retry tất cả failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

### Cron Job (cho scheduled tasks)

Thêm vào crontab:

```bash
crontab -e
```

Thêm dòng:
```
* * * * * cd /path/to/tour-manager && php artisan schedule:run >> /dev/null 2>&1
```

## 📧 Email Configuration Chi Tiết

### Test Email Configuration

```bash
php artisan tinker
```

```php
Mail::raw('Test email from Tour Manager', function($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

### Email Templates

Hệ thống có các template email sau:

1. **booking-confirmation.blade.php** - Xác nhận booking
2. **payment-confirmation.blade.php** - Xác nhận thanh toán
3. **booking-cancellation.blade.php** - Thông báo hủy
4. **tour-reminder.blade.php** - Nhắc nhở trước tour
5. **review-request.blade.php** - Yêu cầu đánh giá

Templates nằm trong: `resources/views/emails/`

### Customize Email Templates

Admin có thể tùy chỉnh template qua giao diện:
- **URL**: `/admin/email-templates`
- Hỗ trợ Markdown và variables động

## 💾 Database Seeding

### Tạo Admin User

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin User',
    'email' => 'admin@tourmanager.com',
    'password' => Hash::make('password'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

### Seed Đầy Đủ (Dữ Liệu Mẫu)

```bash
# Tạo tất cả seeders
php artisan db:seed

# Hoặc seeder cụ thể
php artisan db:seed --class=DestinationSeeder
php artisan db:seed --class=TourSeeder
php artisan db:seed --class=UserSeeder
```

### Reset và Seed Lại

```bash
# ⚠️ Xóa toàn bộ data và seed lại
php artisan migrate:fresh --seed
```

## 🧪 Testing

### Chạy Tests

```bash
# Tất cả tests
php artisan test

# Test cụ thể
php artisan test --filter=BookingTest

# Với coverage
php artisan test --coverage
```

### Test Với Pest

```bash
# Chạy Pest
./vendor/bin/pest

# Với parallel
./vendor/bin/pest --parallel
```

## 🚢 Deployment

### Checklist Trước Khi Deploy

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY`
- [ ] Cấu hình database production
- [ ] Cấu hình email service
- [ ] Setup payment gateways (production keys)
- [ ] Build production assets (`npm run build`)
- [ ] Setup queue worker (Supervisor)
- [ ] Setup cron jobs
- [ ] Configure SSL certificate
- [ ] Setup backups
- [ ] Configure logging

### Production Environment

```env
APP_NAME="Tour Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_PORT=3306
DB_DATABASE=tour_manager_prod
DB_USERNAME=your_db_user
DB_PASSWORD=strong_password

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=your_redis_host
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379
```

### Optimization Commands

```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Clear all caches khi update
php artisan optimize:clear
```

### Deployment Script Example

```bash
#!/bin/bash

# deployment.sh

echo "🚀 Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
sudo supervisorctl restart tour-manager-worker:*

# Restart PHP-FPM (if using)
sudo systemctl restart php8.2-fpm

echo "✅ Deployment completed!"
```

## 🔧 Troubleshooting

### Lỗi Storage Permission

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Hoặc (development)
chmod -R 777 storage bootstrap/cache
```

### Lỗi Composer Memory

```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### Lỗi NPM

```bash
# Clear cache
npm cache clean --force

# Delete node_modules và reinstall
rm -rf node_modules package-lock.json
npm install
```

### Lỗi Database Connection

```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();
```

### Lỗi Queue Not Processing

```bash
# Check failed jobs
php artisan queue:failed

# Restart queue
php artisan queue:restart

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Lỗi Vite

```bash
# Clear Vite cache
rm -rf node_modules/.vite

# Rebuild
npm run build
```

### Debug Mode

```bash
# Enable query logging
DB::enableQueryLog();
// your code
dd(DB::getQueryLog());

# Tail logs
tail -f storage/logs/laravel.log

# Or use Laravel Pail
php artisan pail
```

## 📚 Tài Liệu Bổ Sung

### API Endpoints

- Tất cả routes: `php artisan route:list`
- API documentation: Xem file `routes/api.php` (đang phát triển)

### Architecture

```
tour-manager/
├── app/
│   ├── Http/Controllers/      # Controllers
│   ├── Models/                # Eloquent Models
│   ├── Mail/                  # Email Classes
│   ├── Services/              # Business Logic
│   └── Repositories/          # Data Access Layer
├── database/
│   ├── migrations/            # Database Migrations
│   └── seeders/               # Database Seeders
├── resources/
│   ├── views/                 # Blade Templates
│   ├── css/                   # Styles
│   └── js/                    # JavaScript
├── routes/
│   ├── web.php               # Web Routes
│   └── auth.php              # Auth Routes
└── public/                    # Public Assets
```

### Default Credentials

**Admin (sau khi seed):**
- Email: `admin@tourmanager.com`
- Password: `password`

**Customer (sau khi seed):**
- Email: `user@example.com`
- Password: `password`

> ⚠️ **Quan trọng**: Đổi passwords này trong production!

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Contact & Support

- **Email**: support@tourmanager.com
- **Documentation**: https://docs.tourmanager.com
- **Issues**: https://github.com/thanhphandev/tour-manager/issues

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- PayPal SDK
- VNPay API
- All contributors

---

**Made with ❤️ by Tour Manager Team**

**Version**: 1.0.0  
**Last Updated**: November 2025
