@extends('emails.layout')

@section('content')
<h2 style="color: #dc3545; margin-top: 0;">Thông Báo Hủy Đặt Tour</h2>

<p>Xin chào <strong>{{ $booking->name }}</strong>,</p>

<p>Đơn đặt tour của bạn đã được hủy thành công.</p>

<div class="info-box" style="background: #f8d7da; border-left-color: #dc3545;">
    <h3 style="margin-top: 0; color: #dc3545;">Thông Tin Đặt Tour Đã Hủy</h3>
    
    <div class="info-row">
        <span class="info-label">Mã đặt chỗ:</span>
        <span class="info-value"><strong>{{ $booking->booking_code }}</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Tour:</span>
        <span class="info-value">{{ $booking->tour->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Số tiền:</span>
        <span class="info-value">{{ number_format($booking->total_amount) }} đ</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Ngày hủy:</span>
        <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
    </div>
    
    @if($reason)
    <div class="divider"></div>
    <div class="info-row">
        <span class="info-label">Lý do:</span>
        <span class="info-value">{{ $reason }}</span>
    </div>
    @endif
</div>

@if($payment && $payment->status === 'refunded')
<div class="info-box" style="background: #d1ecf1; border-left-color: #17a2b8;">
    <p style="margin: 0;"><strong>Hoàn Tiền</strong></p>
    <p style="margin: 10px 0 0 0;">
        Số tiền <strong>{{ number_format($refundAmount) }} đ</strong> sẽ được hoàn lại vào tài khoản của bạn trong vòng 7-10 ngày làm việc.
    </p>
</div>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('tours.index') }}" class="button">
        🔍 Khám Phá Tours Khác
    </a>
</div>

<p>Chúng tôi rất tiếc vì chuyến du lịch này không thể thực hiện. Hy vọng sẽ được phục vụ bạn trong những chuyến đi tiếp theo!</p>

<p style="color: #666; font-size: 14px;">
    Trân trọng,<br>
    <strong>Đội ngũ TravelGo</strong>
</p>
@endsection
