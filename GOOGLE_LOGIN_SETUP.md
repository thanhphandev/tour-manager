# Hướng dẫn Cài đặt Google Login

## 🎯 Tính năng đã triển khai

✅ **Đăng nhập bằng Google OAuth 2.0**
✅ **Xử lý callback URL an toàn** - Redirect về trang trước đó sau khi đăng nhập
✅ **Tạo tài khoản tự động** từ Google
✅ **Kiểm tra bảo mật callback URL** - Chỉ chấp nhận URL nội bộ

---

## 📋 Các bước đã thực hiện

### 1. Cài đặt Laravel Socialite
```bash
composer require laravel/socialite
```

### 2. Cấu hình Database
- ✅ Đã thêm cột `google_id` và `avatar` vào bảng `users`
- ✅ Đã set `password` nullable để hỗ trợ đăng nhập Google

### 3. Cấu hình dịch vụ
- ✅ Đã thêm cấu hình Google OAuth trong `config/services.php`
- ✅ Đã thêm biến môi trường trong `.env`

### 4. Tạo Controller & Routes
- ✅ `GoogleAuthController` - Xử lý OAuth flow
- ✅ Routes: `/auth/google` và `/auth/google/callback`

### 5. Bảo mật Callback URL
- ✅ Middleware `Authenticate` tự động lưu intended URL
- ✅ Validation callback URL ngăn chặn open redirect
- ✅ Chỉ chấp nhận URL cùng domain hoặc relative path

### 6. Giao diện
- ✅ Nút "Đăng nhập với Google" với icon Google chính thức
- ✅ Nút "Đăng ký với Google" trên trang register
- ✅ Responsive và có hiệu ứng hover

---

## 🔧 Cấu hình Google OAuth

### Bước 1: Tạo Google Cloud Project

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project hiện có
3. Vào **APIs & Services** > **Credentials**

### Bước 2: Tạo OAuth 2.0 Client ID

1. Click **Create Credentials** > **OAuth client ID**
2. Chọn **Application type**: Web application
3. Đặt tên: `Tour Manager App`
4. **Authorized JavaScript origins**:
   ```
   http://localhost:8000
   http://127.0.0.1:8000
   ```
5. **Authorized redirect URIs**:
   ```
   http://localhost:8000/auth/google/callback
   http://127.0.0.1:8000/auth/google/callback
   ```
6. Click **Create** và lưu lại:
   - Client ID
   - Client Secret

### Bước 3: Cập nhật file .env

Thay thế các giá trị placeholder trong `.env`:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-actual-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-actual-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

⚠️ **LƯU Ý**: Đừng quên thay `your-google-client-id` và `your-google-client-secret` bằng giá trị thực từ Google Cloud Console!

### Bước 4: Clear cache (nếu cần)

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🚀 Cách sử dụng

### Đăng nhập với Google

1. User click nút **"Đăng nhập với Google"** trên trang login
2. Redirect đến Google để xác thực
3. User chọn tài khoản Google và cho phép truy cập
4. Google redirect về `/auth/google/callback`
5. Hệ thống:
   - Tìm user theo `google_id` hoặc `email`
   - Nếu chưa có → Tạo user mới
   - Nếu đã có → Cập nhật `google_id` và `avatar`
   - Đăng nhập user
   - Redirect về trang ban đầu (nếu có) hoặc trang chủ

### Flow callback URL an toàn

**Scenario 1: User chưa đăng nhập truy cập trang protected**
```
1. User truy cập: /tours/123
2. Middleware redirect → /login (lưu /tours/123 vào session)
3. User click "Đăng nhập với Google"
4. Sau khi auth → Redirect về /tours/123
```

**Scenario 2: User click Google Login từ trang login**
```
1. User vào /login
2. Click "Đăng nhập với Google"
3. Sau khi auth → Redirect về trang chủ
```

---

## 🔒 Bảo mật

### Các biện pháp đã triển khai:

#### 1. **Validation Callback URL**
```php
private function isValidCallbackUrl(string $url): bool
{
    // Chỉ chấp nhận:
    // - Relative path: /tours/123
    // - Same domain: http://localhost:8000/tours/123
    // - Không chấp nhận protocol-relative: //evil.com
    // - Không chấp nhận external domain
}
```

#### 2. **Session-based Storage**
- Callback URL được lưu trong session (server-side)
- Không dựa vào query parameter để redirect (tránh open redirect)

#### 3. **Whitelist Route Protection**
```php
// Không lưu callback URL cho các route auth
$excludedPaths = [
    '/login', '/register', '/forgot-password',
    '/reset-password', '/auth/google', '/logout'
];
```

#### 4. **Password Security**
- User đăng ký qua Google có password ngẫu nhiên (32 ký tự)
- Password được hash bằng bcrypt

---

## 📁 Cấu trúc File

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       ├── GoogleAuthController.php     ← OAuth handler
│   │       └── AuthenticatedSessionController.php (updated)
│   └── Middleware/
│       └── Authenticate.php                  ← Callback URL handler
├── Models/
│   └── User.php                              ← Added google_id, avatar
├── database/
│   └── migrations/
│       └── 2025_11_14_081549_add_google_id_to_users_table.php
config/
├── services.php                              ← Google config
routes/
└── auth.php                                  ← Google routes
resources/
└── views/
    └── auth/
        ├── login.blade.php                   ← Google button
        └── register.blade.php                ← Google button
```

---

## 🧪 Test

### Test Local

1. Khởi động server:
```bash
php artisan serve
```

2. Truy cập: http://localhost:8000/login
3. Click **"Đăng nhập với Google"**
4. Kiểm tra flow OAuth

### Test Callback URL

**Test Case 1: Protected Route**
```
1. Logout (nếu đang đăng nhập)
2. Truy cập: http://localhost:8000/admin/dashboard
3. Redirect → /login
4. Click Google Login
5. Verify: Sau khi đăng nhập redirect về /admin/dashboard
```

**Test Case 2: Direct Login**
```
1. Truy cập: http://localhost:8000/login
2. Click Google Login
3. Verify: Sau khi đăng nhập redirect về trang chủ
```

---

## 🐛 Troubleshooting

### Error: "redirect_uri_mismatch"
**Nguyên nhân**: Redirect URI không khớp với Google Console

**Giải pháp**:
1. Kiểm tra lại **Authorized redirect URIs** trong Google Console
2. Đảm bảo có đúng: `http://localhost:8000/auth/google/callback`
3. Kiểm tra `.env`: `GOOGLE_REDIRECT_URI` phải khớp

### Error: "invalid_client"
**Nguyên nhân**: Client ID hoặc Secret sai

**Giải pháp**:
1. Kiểm tra lại `GOOGLE_CLIENT_ID` và `GOOGLE_CLIENT_SECRET` trong `.env`
2. Copy lại từ Google Cloud Console
3. Chạy: `php artisan config:clear`

### Error: Cannot find user after Google login
**Nguyên nhân**: Database chưa có cột `google_id`

**Giải pháp**:
```bash
php artisan migrate
```

### Callback URL không hoạt động
**Nguyên nhân**: Session không được lưu

**Giải pháp**:
1. Kiểm tra `SESSION_DRIVER` trong `.env` (nên dùng `database` hoặc `redis`)
2. Nếu dùng `database`:
```bash
php artisan session:table
php artisan migrate
```

---

## 🌐 Production Deployment

### 1. Cập nhật Google Cloud Console

Thêm production URLs vào:
- **Authorized JavaScript origins**: `https://yourdomain.com`
- **Authorized redirect URIs**: `https://yourdomain.com/auth/google/callback`

### 2. Cập nhật .env Production

```env
APP_URL=https://yourdomain.com

GOOGLE_CLIENT_ID=production-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=production-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### 3. Security Checklist

- [ ] HTTPS enabled
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Session driver: `redis` hoặc `database`
- [ ] Cache config: `php artisan config:cache`

---

## 📚 Tài liệu tham khảo

- [Laravel Socialite](https://laravel.com/docs/11.x/socialite)
- [Google OAuth 2.0](https://developers.google.com/identity/protocols/oauth2)
- [OWASP Open Redirect](https://cheatsheetseries.owasp.org/cheatsheets/Unvalidated_Redirects_and_Forwards_Cheat_Sheet.html)

---

## ✅ Checklist hoàn thành

- [x] Cài đặt Laravel Socialite
- [x] Migration thêm google_id và avatar
- [x] Cấu hình services.php
- [x] Tạo GoogleAuthController
- [x] Thêm routes Google OAuth
- [x] Implement callback URL security
- [x] Update Authenticate middleware
- [x] Update AuthenticatedSessionController
- [x] Thêm Google button vào login page
- [x] Thêm Google button vào register page
- [x] Test security validation
- [x] Viết documentation

---

**🎉 Hoàn thành! Tính năng Google Login đã sẵn sàng sử dụng.**

Nhớ cập nhật Google Cloud Console credentials trước khi test!
