<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hóa Đơn - {{ $booking->booking_code }}</title>
<style>
    /* Paper & font */
    body {
        font-family: "DejaVu Sans", monospace, Arial, sans-serif;
        font-size: 10pt;
        color: #000;
        width: 80mm;
        margin: 0 auto;
        padding: 5px 0;
        text-align: center;
    }
    .container { width: 100%; margin: 0 auto; }
    .bold { font-weight: bold; }
    hr { border: 0; border-top: 1px dashed #000; margin: 5px 0; }

    .section-title {
        font-weight: bold;
        margin: 5px 0 2px 0;
        text-transform: uppercase;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        margin: 2px 0;
        width: 100%;
    }
    .right { text-align: right; }
    .table { width: 100%; margin: 3px 0; }
    .table td { padding: 2px 0; }

    /* Footer */
    .footer { margin-top: 5px; font-size: 9pt; }
</style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="bold">TOUR MANAGER SYSTEM</div>
    <div class="bold">HÓA ĐƠN TOUR</div>
    <div style="font-size:9pt;">Hotline: (028) 1234-5678 | info@tourmanager.vn</div>
    <hr>

    <!-- Booking Info -->
    <div class="section-title">Thông tin booking</div>
    <div class="info-row"><span>Mã Booking:</span><span>{{ $booking->booking_code }}</span></div>
    <div class="info-row"><span>Ngày đặt:</span><span>{{ $booking->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="info-row"><span>Trạng thái:</span>
        <span>
            @if($booking->status_label === 'confirmed') Đã xác nhận
            @elseif($booking->status_label === 'pending') Chờ
            @elseif($booking->status_label === 'completed') Hoàn thành
            @else Hủy
            @endif
        </span>
    </div>
    <div class="info-row"><span>Khách hàng:</span><span>{{ $booking->name }}</span></div>
    <div class="info-row"><span>Điện thoại:</span><span>{{ $booking->phone }}</span></div>
    <div class="info-row"><span>Email:</span><span>{{ $booking->email }}</span></div>
    <hr>

    <!-- Tour Info -->
    <div class="section-title">Thông tin tour</div>
    <div class="info-row"><span>Tên tour:</span><span>{{ $booking->tour->name }}</span></div>
    <div class="info-row"><span>Điểm đến:</span><span>{{ $booking->tour->destination->name }}</span></div>
    <div class="info-row"><span>Thời gian:</span><span>{{ $booking->tour->duration_days }} ngày {{ $booking->tour->duration_nights }} đêm</span></div>
    <div class="info-row"><span>Khởi hành:</span><span>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span></div>
    <hr>

    <!-- Pricing -->
    <div class="section-title">Chi tiết giá</div>
    <table class="table">
        @if($booking->adults > 0)
        <tr><td>Người lớn x{{ $booking->adults }}</td><td class="right">{{ number_format($booking->tour->price_adult * $booking->adults,0,',','.') }} đ</td></tr>
        @endif
        @if($booking->children > 0)
        <tr><td>Trẻ em x{{ $booking->children }}</td><td class="right">{{ number_format($booking->tour->price_child * $booking->children,0,',','.') }} đ</td></tr>
        @endif
        @if($booking->infants > 0)
        <tr><td>Em bé x{{ $booking->infants }}</td><td class="right">{{ number_format($booking->tour->price_infant * $booking->infants,0,',','.') }} đ</td></tr>
        @endif
    </table>
    <hr>

    <!-- Total -->
    <div class="info-row bold"><span>Tổng số khách:</span><span>{{ $booking->total_people }} người</span></div>
    <div class="info-row bold"><span>Tổng tiền:</span><span>{{ number_format($booking->total_amount,0,',','.') }} VNĐ</span></div>
    <hr>

    <!-- Footer -->
    <div class="footer">Cảm ơn quý khách!<br>Tour Manager System</div>

</div>
</body>
</html>
