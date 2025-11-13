@extends('emails.layout')

@section('content')
<h2 style="color: #333; margin-top: 0;">✅ Xác Nhận Thanh Toán Thành Công</h2>

<p>Xin chào <strong>{{ $payment->booking->full_name }}</strong>,</p>

<p>Thanh toán của bạn đã được xử lý thành công! Tour của bạn đã được xác nhận.</p>

<div class="info-box" style="background: #d4edda; border-left-color: #28a745;">
    <h3 style="margin-top: 0; color: #28a745;">💰 Thông Tin Thanh Toán</h3>
    
    <div class="info-row">
        <span class="info-label">Mã thanh toán:</span>
        <span class="info-value"><strong class="highlight">{{ $payment->payment_code }}</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Mã giao dịch:</span>
        <span class="info-value">{{ $payment->transaction_id ?? 'N/A' }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Phương thức:</span>
        <span class="info-value">{{ ucfirst($payment->payment_method) }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Số tiền:</span>
        <span class="info-value"><strong style="font-size: 20px; color: #28a745;">{{ number_format($payment->amount) }} đ</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Thời gian:</span>
        <span class="info-value">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Trạng thái:</span>
        <span class="info-value"><span class="badge success">✓ Thành công</span></span>
    </div>
</div>

<div class="divider"></div>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">📋 Thông Tin Tour</h3>
    
    <div class="info-row">
        <span class="info-label">Mã đặt chỗ:</span>
        <span class="info-value"><strong>{{ $payment->booking->booking_code }}</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Tour:</span>
        <span class="info-value">{{ $payment->booking->tour->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Số người:</span>
        <span class="info-value">{{ $payment->booking->total_people }} người</span>
    </div>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('bookings.show', $payment->booking) }}" class="button">
        📄 Xem Chi Tiết Đặt Chỗ
    </a>
</div>

<div class="info-box" style="background: #e7f3ff; border-left-color: #0066cc;">
    <p style="margin: 0;"><strong>🎯 Bước tiếp theo:</strong></p>
    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
        <li>Kiểm tra email để nhận thông tin chi tiết về tour</li>
        <li>Chuẩn bị hành lý và giấy tờ cần thiết</li>
        <li>Đến điểm tập trung đúng giờ</li>
        <li>Liên hệ hotline 1900-xxxx nếu cần hỗ trợ</li>
    </ul>
</div>

<p style="margin-top: 30px;">Chúc bạn có một chuyến du lịch tuyệt vời! 🌟</p>

<p style="color: #666; font-size: 14px;">
    Trân trọng,<br>
    <strong>Đội ngũ TravelGo</strong>
</p>
@endsection
