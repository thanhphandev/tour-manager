@extends('emails.layout')

@section('content')
<h2 style="color: #333; margin-top: 0;">🔔 Nhắc Nhở Tour Sắp Diễn Ra</h2>

<p>Xin chào <strong>{{ $booking->full_name }}</strong>,</p>

<p>Chuyến du lịch của bạn sắp bắt đầu! Đây là một số thông tin quan trọng bạn cần lưu ý.</p>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">🎯 Thông Tin Tour</h3>
    
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
</div>

<div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
    <p style="margin: 0;"><strong>📝 Danh Sách Chuẩn Bị:</strong></p>
    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
        <li>✓ CMND/CCCD (bản gốc)</li>
        <li>✓ Vé máy bay/xe (nếu có)</li>
        <li>✓ Bảo hiểm du lịch</li>
        <li>✓ Thuốc men cá nhân</li>
        <li>✓ Quần áo phù hợp với thời tiết</li>
        <li>✓ Thiết bị điện tử, sạc pin</li>
        <li>✓ Tiền mặt và thẻ ngân hàng</li>
    </ul>
</div>

<div class="info-box" style="background: #e7f3ff; border-left-color: #0066cc;">
    <p style="margin: 0;"><strong>⏰ Lưu Ý Quan Trọng:</strong></p>
    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
        <li>Có mặt tại điểm tập trung trước giờ khởi hành <strong>30 phút</strong></li>
        <li>Kiểm tra kỹ hành lý trước khi khởi hành</li>
        <li>Liên hệ hotline <strong>1900-xxxx</strong> nếu cần hỗ trợ</li>
        <li>Mang theo mã đặt chỗ: <strong class="highlight">{{ $booking->booking_code }}</strong></li>
    </ul>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('bookings.show', $booking) }}" class="button">
        📄 Xem Chi Tiết Booking
    </a>
</div>

<p style="margin-top: 30px;">Chúc bạn có một chuyến du lịch an toàn và đầy ý nghĩa! 🌟</p>

<p style="color: #666; font-size: 14px;">
    Trân trọng,<br>
    <strong>Đội ngũ TravelGo</strong>
</p>
@endsection
