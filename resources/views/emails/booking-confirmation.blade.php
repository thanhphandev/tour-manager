@extends('emails.layout')

@section('content')
<h2 style="color: #333; margin-top: 0;">Xác Nhận Đặt Tour</h2>

<p>Xin chào <strong>{{ $booking->name }}</strong>,</p>

<p>Cảm ơn bạn đã đặt tour tại TravelGo! Chúng tôi rất vui được đồng hành cùng bạn trong chuyến du lịch sắp tới.</p>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Thông Tin Đặt Tour</h3>
    
    <div class="info-row">
        <span class="info-label">Mã đặt chỗ:</span>
        <span class="info-value"><strong class="highlight">{{ $booking->booking_code }}</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Tour:</span>
        <span class="info-value"><strong>{{ $booking->tour->name }}</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Điểm đến:</span>
        <span class="info-value">{{ $booking->tour->destination->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Thời gian:</span>
        <span class="info-value">{{ $booking->tour->duration }} ngày {{ $booking->tour->duration - 1 }} đêm</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Số người:</span>
        <span class="info-value">{{ $booking->total_people }} người</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Ngày đặt:</span>
        <span class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
    </div>
    
    <div class="divider"></div>
    
    <div class="info-row">
        <span class="info-label">Tổng tiền:</span>
        <span class="info-value"><strong style="font-size: 18px; color: #667eea;">{{ number_format($booking->total_amount) }} đ</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Trạng thái:</span>
        <span class="info-value">
            <span class="badge warning">Chờ thanh toán</span>
        </span>
    </div>
</div>

<div style="text-align: center;">
    <p><strong>Vui lòng hoàn tất thanh toán để xác nhận đặt chỗ của bạn.</strong></p>
    <a href="{{ route('payments.show', $booking) }}" class="button">
        Thanh Toán Ngay
    </a>
</div>

<div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
    <p style="margin: 0;"><strong>📌 Lưu ý quan trọng:</strong></p>
    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
        <li>Vui lòng giữ lại mã đặt chỗ để tra cứu thông tin</li>
        <li>Đến sớm 30 phút tại điểm tập trung</li>
        <li>Mang theo CMND/CCCD và giấy tờ cần thiết</li>
        <li>Liên hệ hotline nếu cần hỗ trợ: 1900-xxxx</li>
    </ul>
</div>

<p style="margin-top: 30px;">Chúc bạn có một chuyến du lịch vui vẻ và an toàn! 🎉</p>

<p style="color: #666; font-size: 14px;">
    Trân trọng,<br>
    <strong>Đội ngũ TravelGo</strong>
</p>
@endsection
