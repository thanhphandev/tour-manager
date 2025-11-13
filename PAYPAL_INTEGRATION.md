# Hướng Dẫn Tích Hợp PayPal và Xử Lý Lỗi Thanh Toán

## 🎯 Tổng Quan

Dự án đã được tích hợp:
- ✅ **PayPal Payment Gateway** với PayPal Server SDK mới nhất
- ✅ **Trang Error Callback** cho các lỗi thanh toán
- ✅ **Xử lý an toàn và chuyên nghiệp** với logging, validation, và error handling

## 📦 Các Thay Đổi Đã Thực Hiện

### 1. **Cài Đặt PayPal SDK**
```bash
composer require paypal/paypal-server-sdk
```

### 2. **Các File Mới**

#### `app/Services/PayPalService.php`
Service xử lý tất cả tương tác với PayPal API:
- `createOrder()` - Tạo PayPal order
- `captureOrder()` - Hoàn tất thanh toán
- `getOrderDetails()` - Lấy chi tiết order
- `refundCapture()` - Hoàn tiền
- `verifyWebhookSignature()` - Xác thực webhook

**Tính năng bảo mật:**
- ✅ Validation đầu vào
- ✅ Try-catch toàn diện
- ✅ Logging chi tiết
- ✅ Sanitize error messages
- ✅ Exchange rate conversion
- ✅ Webhook signature verification

#### `resources/views/payments/error.blade.php`
Trang hiển thị lỗi thanh toán với:
- Thông tin chi tiết lỗi
- Mã booking và giao dịch
- Các nguyên nhân có thể
- Nút thử lại thanh toán
- Thông tin hỗ trợ

#### `.env.paypal.example`
Template cấu hình PayPal với hướng dẫn chi tiết

### 3. **Các File Đã Cập Nhật**

#### `app/Http/Controllers/PaymentController.php`
Thêm các method mới:
- `processPayPal()` - Xử lý khởi tạo PayPal payment
- `paypalCallback()` - Callback khi thanh toán thành công
- `paypalCancel()` - Callback khi người dùng hủy
- `error()` - Hiển thị trang lỗi
- `vnpayCallback()` - Cập nhật xử lý lỗi VNPay

**Cải tiến:**
- ✅ Error handling toàn diện với DB transaction
- ✅ Logging chi tiết mọi bước
- ✅ Redirect đến trang error thay vì message đơn giản
- ✅ Session data cho error page

#### `routes/web.php`
Thêm routes mới:
```php
Route::post('/{booking}/paypal', [PaymentController::class, 'processPayPal']);
Route::get('/paypal/callback', [PaymentController::class, 'paypalCallback']);
Route::get('/paypal/cancel', [PaymentController::class, 'paypalCancel']);
Route::get('/{booking}/error', [PaymentController::class, 'error']);
```

#### `resources/views/payments/show.blade.php`
- Thêm tab PayPal với UI đầy đủ
- Thay đổi thứ tự: PayPal → VNPay → Mock
- PayPal features và benefits
- Hướng dẫn sử dụng từng bước

#### `config/services.php`
Thêm cấu hình PayPal:
```php
'paypal' => [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'exchange_rate' => env('PAYPAL_EXCHANGE_RATE', 24000),
    'return_url' => env('PAYPAL_RETURN_URL'),
    'cancel_url' => env('PAYPAL_CANCEL_URL'),
    'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
]
```

## 🚀 Cách Sử Dụng

### Bước 1: Cấu Hình PayPal

1. **Tạo PayPal Developer Account**
   - Truy cập: https://developer.paypal.com
   - Đăng ký/Đăng nhập

2. **Tạo App**
   - Vào "My Apps & Credentials"
   - Nhấn "Create App"
   - Chọn "Merchant" type
   - Lưu Client ID và Secret

3. **Thêm vào `.env`**
```env
# PayPal Configuration
PAYPAL_CLIENT_ID=your-sandbox-client-id
PAYPAL_CLIENT_SECRET=your-sandbox-client-secret
PAYPAL_MODE=sandbox
PAYPAL_CURRENCY=USD
PAYPAL_EXCHANGE_RATE=24000
```

### Bước 2: Test với Sandbox

1. Truy cập: http://localhost:8000
2. Đăng nhập và đặt tour
3. Chọn phương thức "PayPal"
4. Sử dụng tài khoản PayPal Sandbox để test:
   - Email: sb-buyer@personal.example.com
   - Password: (xem trong PayPal Sandbox Accounts)

### Bước 3: Chuyển sang Production

```env
PAYPAL_MODE=live
PAYPAL_CLIENT_ID=your-live-client-id
PAYPAL_CLIENT_SECRET=your-live-client-secret
```

## 🔒 Tính Năng Bảo Mật

### 1. **Input Validation**
- Kiểm tra amount > 0
- Validate email format
- Sanitize user input

### 2. **Transaction Safety**
- Database transactions cho mọi thao tác
- Rollback khi có lỗi
- Check duplicate payment

### 3. **Error Handling**
- Try-catch cho mọi API call
- Logging chi tiết errors
- User-friendly error messages
- Không lộ thông tin nhạy cảm

### 4. **Logging**
```php
Log::info('PayPal order created', ['order_id' => $orderId]);
Log::error('PayPal capture failed', ['error' => $error]);
```

### 5. **Authorization**
- Verify user owns booking
- Check payment status
- Prevent duplicate processing

## 📊 Flow Thanh Toán PayPal

```
1. User chọn PayPal → processPayPal()
2. Tạo Payment record (status: pending)
3. Call PayPalService->createOrder()
4. Redirect to PayPal approval URL
5. User đăng nhập PayPal và xác nhận
6. PayPal redirect về paypalCallback()
7. Call PayPalService->captureOrder()
8. Update Payment (status: success)
9. Send confirmation email
10. Redirect to success page
```

## ⚠️ Xử Lý Lỗi

### Các Loại Lỗi Được Xử Lý

1. **PAYPAL_CREATE_FAILED** - Không tạo được order
2. **PAYPAL_CAPTURE_FAILED** - Capture thất bại
3. **USER_CANCELLED** - User hủy thanh toán
4. **SYSTEM_ERROR** - Lỗi hệ thống
5. **AUTHENTICATION_FAILURE** - Sai credentials
6. **INVALID_REQUEST** - Request không hợp lệ
7. **INSTRUMENT_DECLINED** - Thẻ bị từ chối
8. **INSUFFICIENT_FUNDS** - Không đủ tiền

### Error Page Features

- ✅ Hiển thị error message và code
- ✅ Thông tin booking
- ✅ Chi tiết transaction
- ✅ Danh sách nguyên nhân có thể
- ✅ Nút "Thử lại thanh toán"
- ✅ Link "Xem chi tiết đặt chỗ"
- ✅ Thông tin hỗ trợ (hotline, email)

## 🔄 Migration từ VNPay

Không cần thay đổi VNPay hiện tại:
- PayPal hoạt động song song với VNPay
- User có thể chọn giữa PayPal, VNPay, hoặc Mock
- VNPay callback đã được cập nhật xử lý lỗi tốt hơn

## 📝 Best Practices Đã Áp Dụng

1. ✅ **Service Layer Pattern** - Logic PayPal tách riêng
2. ✅ **Repository Pattern** - Sử dụng Eloquent models
3. ✅ **Transaction Management** - DB::beginTransaction/commit/rollback
4. ✅ **Logging** - Log::info/error cho audit trail
5. ✅ **Error Messages** - User-friendly và không lộ thông tin nhạy cảm
6. ✅ **Configuration Management** - Dùng config() và env()
7. ✅ **Validation** - Validate input trước khi xử lý
8. ✅ **Authorization** - Check ownership và permissions
9. ✅ **Email Notifications** - Send confirmation emails
10. ✅ **Idempotency** - Prevent duplicate processing

## 🧪 Testing

### Manual Testing Checklist

- [ ] PayPal order creation
- [ ] PayPal payment success flow
- [ ] PayPal payment cancellation
- [ ] Error handling khi API fail
- [ ] Error page display
- [ ] Email notifications
- [ ] Concurrent payments prevention
- [ ] Exchange rate conversion
- [ ] Logging accuracy

### Test Scenarios

1. **Happy Path**: User thanh toán thành công
2. **User Cancel**: User hủy tại PayPal
3. **Network Error**: API timeout/fail
4. **Invalid Credentials**: Sai client ID/secret
5. **Duplicate Payment**: User thử thanh toán 2 lần
6. **Concurrent Access**: Nhiều users cùng lúc

## 📚 Tài Liệu Tham Khảo

- [PayPal Server SDK Documentation](https://github.com/paypal/PayPal-PHP-Server-SDK)
- [PayPal REST API Reference](https://developer.paypal.com/api/rest/)
- [PayPal Sandbox Testing](https://developer.paypal.com/tools/sandbox/)
- [PayPal Webhooks](https://developer.paypal.com/api/rest/webhooks/)

## 🆘 Troubleshooting

### Issue: "AUTHENTICATION_FAILURE"
**Solution**: Kiểm tra PAYPAL_CLIENT_ID và PAYPAL_CLIENT_SECRET trong .env

### Issue: "Invalid amount"
**Solution**: Kiểm tra exchange rate và format số tiền (2 decimal places)

### Issue: Không nhận được callback
**Solution**: 
- Kiểm tra PAYPAL_RETURN_URL và PAYPAL_CANCEL_URL
- Đảm bảo APP_URL đúng
- Kiểm tra routes có conflict không

### Issue: Error "Class not found"
**Solution**: Chạy `composer dump-autoload`

## 📞 Hỗ Trợ

Nếu gặp vấn đề:
1. Kiểm tra logs: `storage/logs/laravel.log`
2. Xem PayPal Developer Dashboard → Activity
3. Test với PayPal Sandbox trước
4. Đọc error message trong error page

## ✨ Next Steps

Các cải tiến có thể thêm:
- [ ] Webhook handler cho real-time notifications
- [ ] Admin panel để xem PayPal transactions
- [ ] Automatic refund từ admin
- [ ] Multi-currency support
- [ ] Dynamic exchange rate API
- [ ] Payment retry mechanism
- [ ] Unit tests cho PayPalService
- [ ] Feature tests cho payment flows

---

**Version**: 1.0.0  
**Last Updated**: November 14, 2025  
**Author**: Tour Manager Development Team
