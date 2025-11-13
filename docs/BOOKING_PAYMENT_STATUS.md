# 📊 Quản Lý Trạng Thái Booking & Payment

## 1. KIẾN TRÚC

```
┌─────────────────────────────────────────────────────────────────┐
│                         BOOKING                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │ Status: pending → confirmed → cancelled                  │  │
│  │ • Trạng thái tổng thể của đơn đặt tour                  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                             ↓ has many                          │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │               PAYMENTS (lịch sử thanh toán)              │  │
│  │  Payment 1: pending → failed                            │  │
│  │  Payment 2: pending → success  ← Thành công             │  │
│  │  Payment 3: success → refunded (nếu có hoàn tiền)       │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. MA TRẬN TRẠNG THÁI

### **Booking Status (3 trạng thái cơ bản)**

| Status | Ý nghĩa | Trigger |
|--------|---------|---------|
| `pending` | Chờ thanh toán | Mặc định khi tạo booking |
| `confirmed` | Đã xác nhận | Khi payment.status = success |
| `cancelled` | Đã hủy | User/Admin hủy đơn |

### **Payment Status (4 trạng thái)**

| Status | Ý nghĩa | Trigger |
|--------|---------|---------|
| `pending` | Đang xử lý | Vừa tạo payment record |
| `success` | Thành công | Gateway confirm thành công |
| `failed` | Thất bại | Gateway từ chối |
| `refunded` | Đã hoàn tiền | Admin hoàn tiền |

### **Payment Status (Composite) - Hiển thị cho User**

| Code | Label | Màu | Điều kiện |
|------|-------|-----|-----------|
| `awaiting_payment` | Chờ Thanh Toán | Yellow | booking.pending + no payment |
| `processing` | Đang Xử Lý | Blue | booking.pending + payment.pending |
| `payment_failed` | Thanh Toán Thất Bại | Red | booking.pending + payment.failed |
| `paid` | Đã Thanh Toán | Green | booking.confirmed + payment.success |
| `refunded` | Đã Hoàn Tiền | Purple | booking.confirmed + payment.refunded |
| `cancelled` | Đã Hủy | Gray | booking.cancelled |

---

## 3. CÁC TRƯỜNG HỢP THỰC TẾ

### **Case 1: Luồng bình thường - Thanh toán thành công ngay lần đầu ✅**

```
┌─────────────┬──────────────────────┬───────────────────────┐
│ Thời điểm   │ Booking Status       │ Payment Status        │
├─────────────┼──────────────────────┼───────────────────────┤
│ T0: Tạo     │ pending              │ (chưa có)             │
│ T1: Submit  │ pending              │ pending               │
│ T2: Success │ confirmed ✅         │ success ✅            │
└─────────────┴──────────────────────┴───────────────────────┘

Hiển thị: "Đã Thanh Toán" (badge xanh)
Actions: [Xem chi tiết] [Hủy đơn] [Đánh giá]
```

---

### **Case 2: Thanh toán thất bại, retry thành công ⚠️**

```
┌─────────────┬──────────────────────┬───────────────────────┐
│ Thời điểm   │ Booking Status       │ Payment Records       │
├─────────────┼──────────────────────┼───────────────────────┤
│ T0: Tạo     │ pending              │ (chưa có)             │
│ T1: Try #1  │ pending              │ Payment 1: pending    │
│ T2: Failed  │ pending (giữ)        │ Payment 1: failed ❌  │
│ T3: Try #2  │ pending              │ Payment 2: pending    │
│ T4: Success │ confirmed ✅         │ Payment 2: success ✅ │
└─────────────┴──────────────────────┴───────────────────────┘

Lưu ý: Booking giữ nguyên pending cho đến khi có payment success
Hiển thị tại T2: "Thanh toán Thất Bại" + nút [Thử lại]
```

---

### **Case 3: Thanh toán thành công, sau đó hoàn tiền 💰**

```
┌─────────────┬──────────────────────┬───────────────────────┐
│ Thời điểm   │ Booking Status       │ Payment Records       │
├─────────────┼──────────────────────┼───────────────────────┤
│ T1: Success │ confirmed            │ Payment 1: success    │
│ T2: Refund  │ confirmed (giữ)      │ Payment 1: refunded   │
└─────────────┴──────────────────────┴───────────────────────┘

Hiển thị: "Đã Hoàn Tiền" (badge tím)
Actions: [Xem chi tiết] (không cho đặt lại)
```

---

### **Case 4: Hủy đơn trước khi thanh toán ❌**

```
┌─────────────┬──────────────────────┬───────────────────────┐
│ Thời điểm   │ Booking Status       │ Payment Records       │
├─────────────┼──────────────────────┼───────────────────────┤
│ T0: Tạo     │ pending              │ (chưa có)             │
│ T1: Cancel  │ cancelled ❌         │ (không có)            │
└─────────────┴──────────────────────┴───────────────────────┘

Hiển thị: "Đã Hủy" (badge xám)
Actions: Không có action
```

---

## 4. HELPER METHODS

### **Trong Booking Model:**

```php
// Check trạng thái
$booking->isPaid()              // bool: Đã thanh toán?
$booking->canPay()              // bool: Có thể thanh toán?
$booking->canCancel()           // bool: Có thể hủy?
$booking->canRequestRefund()    // bool: Có thể yêu cầu hoàn tiền?

// Lấy thông tin
$booking->payment_status        // string: awaiting_payment, processing, etc.
$booking->status_label          // string: "Chờ Thanh Toán", "Đã Thanh Toán", etc.
$booking->status_color          // string: yellow, green, red, etc.
$booking->getSuccessfulPayment() // Payment|null: Payment thành công

// Relations
$booking->payments              // Collection: Tất cả payments
```

---

## 5. HIỂN THỊ TRONG VIEW

### **Sử dụng Component:**

```blade
<x-booking-status-badge :booking="$booking" />
```

### **Điều kiện hiển thị nút:**

```blade
@if($booking->canPay())
    <a href="{{ route('payments.show', $booking) }}" class="btn-primary">
        Thanh Toán Ngay
    </a>
@endif

@if($booking->canCancel())
    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
        @csrf
        <button type="submit" class="btn-danger">Hủy Đơn</button>
    </form>
@endif

@if($booking->isPaid() && !$booking->review)
    <a href="{{ route('reviews.create', $booking->tour) }}" class="btn-secondary">
        Đánh Giá Tour
    </a>
@endif
```

---

## 6. WORKFLOW XỬ LÝ THANH TOÁN

```
┌────────────────────────────────────────────────────────────────┐
│ 1. User tạo booking                                            │
│    → Booking: status = pending                                 │
│    → Redirect: payments.show                                   │
└────────────────────────────────────────────────────────────────┘
                           ↓
┌────────────────────────────────────────────────────────────────┐
│ 2. User submit payment form                                    │
│    → Create Payment: status = pending                          │
│    → Call payment gateway (Mock/VNPay)                         │
└────────────────────────────────────────────────────────────────┘
                           ↓
              ┌────────────┴────────────┐
              ↓                         ↓
┌─────────────────────┐    ┌─────────────────────┐
│ 3a. Success         │    │ 3b. Failed          │
│ → Payment: success  │    │ → Payment: failed   │
│ → Booking: confirmed│    │ → Booking: pending  │
│ → Send email        │    │ → Show retry button │
│ → Redirect: success │    │ → Stay on payment   │
└─────────────────────┘    └─────────────────────┘
```

---

## 7. LƯU Ý QUAN TRỌNG

### ✅ **DO's:**
- Luôn check `$booking->canPay()` trước khi cho thanh toán
- Lưu lại lịch sử tất cả payment attempts (retry)
- Không xóa payment records (để audit trail)
- Dùng transactions khi update booking + payment
- Gửi email notification sau mỗi status change

### ❌ **DON'Ts:**
- Không hard-delete payments
- Không update booking.status thủ công (để Payment model xử lý)
- Không cho phép thanh toán khi booking đã cancelled
- Không cho cancel booking sau khi đã refunded

---

## 8. EMAIL NOTIFICATIONS

| Event | Template | Gửi cho |
|-------|----------|---------|
| Booking created | `booking_confirmation` | Customer |
| Payment success | `payment_confirmation` | Customer |
| Payment failed | `payment_failed` | Customer |
| Booking cancelled | `booking_cancellation` | Customer |
| Refund processed | `refund_confirmation` | Customer |

---

## 9. ADMIN ACTIONS

| Booking Status | Payment Status | Admin có thể |
|----------------|----------------|--------------|
| pending | - | Cancel, Manually confirm |
| pending | failed | Cancel, Resend payment link |
| confirmed | success | Cancel, Refund |
| confirmed | refunded | View only |
| cancelled | - | View only |

---

## 10. DATABASE QUERIES HIỆU QUẢ

```php
// Lấy bookings với payment info (eager loading)
$bookings = Booking::with([
    'payments' => fn($q) => $q->latest()
])->get();

// Chỉ lấy bookings đã thanh toán
$paidBookings = Booking::whereHas('payments', function($q) {
    $q->where('status', 'success');
})->get();

// Lấy bookings pending chưa thanh toán
$unpaidBookings = Booking::where('status', 'pending')
    ->whereDoesntHave('payments', function($q) {
        $q->where('status', 'success');
    })->get();

// Thống kê theo status
$stats = Booking::selectRaw('
    COUNT(*) as total,
    SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled
')->first();
```

---

## 11. TESTING SCENARIOS

```php
// Test thanh toán thành công
test('successful payment confirms booking', function () {
    $booking = Booking::factory()->create(['status' => 'pending']);
    
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'status' => 'pending',
    ]);
    
    $payment->markAsSuccess('TXN123');
    
    expect($booking->fresh()->status)->toBe('confirmed');
    expect($booking->isPaid())->toBeTrue();
});

// Test retry payment
test('can retry failed payment', function () {
    $booking = Booking::factory()->create(['status' => 'pending']);
    
    // First attempt fails
    Payment::create(['booking_id' => $booking->id, 'status' => 'failed']);
    
    // Second attempt succeeds
    $payment2 = Payment::create(['booking_id' => $booking->id, 'status' => 'pending']);
    $payment2->markAsSuccess('TXN456');
    
    expect($booking->fresh()->status)->toBe('confirmed');
    expect($booking->payments)->toHaveCount(2);
});
```
