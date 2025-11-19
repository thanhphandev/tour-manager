@extends('emails.layout')

@section('content')
<h2 style="color: #333; margin-top: 0;">⭐ Chia Sẻ Trải Nghiệm Của Bạn</h2>

<p>Xin chào <strong>{{ $booking->full }}</strong>,</p>

<p>Cảm ơn bạn đã lựa chọn TravelGo cho chuyến du lịch của mình! Chúng tôi hy vọng bạn đã có những trải nghiệm tuyệt vời.</p>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">📋 Tour Bạn Đã Tham Gia</h3>
    
    <div class="info-row">
        <span class="info-label">Tour:</span>
        <span class="info-value"><strong>{{ $booking->tour->name }}</strong></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Điểm đến:</span>
        <span class="info-value">{{ $booking->tour->destination->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Mã đặt chỗ:</span>
        <span class="info-value">{{ $booking->booking_code }}</span>
    </div>
</div>

<div style="text-align: center; margin: 30px 0;">
    <p style="font-size: 16px; margin-bottom: 20px;">
        <strong>Bạn có hài lòng với chuyến đi của mình không?</strong><br>
        Hãy dành vài phút để chia sẻ đánh giá của bạn!
    </p>
    
    <div style="margin: 20px 0;">
        <span style="font-size: 48px;">⭐⭐⭐⭐⭐</span>
    </div>
    
    <a href="{{ route('reviews.create', $booking->tour) }}" class="button" style="font-size: 16px; padding: 16px 40px;">
        ✍️ Viết Đánh Giá Ngay
    </a>
</div>

<div class="info-box" style="background: #e7f3ff; border-left-color: #0066cc;">
    <p style="margin: 0;"><strong>💡 Đánh giá của bạn giúp:</strong></p>
    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
        <li>Khách hàng khác có thêm thông tin hữu ích</li>
        <li>Chúng tôi cải thiện chất lượng dịch vụ</li>
        <li>Chia sẻ những khoảnh khắc đáng nhớ</li>
    </ul>
</div>

<div class="divider"></div>

<div style="text-align: center;">
    <p style="font-size: 16px; color: #667eea; font-weight: 600;">🎁 Ưu Đãi Đặc Biệt</p>
    <p>Đánh giá ngay hôm nay để nhận <strong class="highlight">mã giảm giá 10%</strong> cho chuyến du lịch tiếp theo!</p>
    
    <a href="{{ route('tours.index') }}" class="button" style="background: #28a745; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
        🔍 Khám Phá Tours Mới
    </a>
</div>

<p style="margin-top: 30px;">Cảm ơn bạn đã tin tưởng và đồng hành cùng TravelGo! 💚</p>

<p style="color: #666; font-size: 14px;">
    Trân trọng,<br>
    <strong>Đội ngũ TravelGo</strong>
</p>
@endsection
