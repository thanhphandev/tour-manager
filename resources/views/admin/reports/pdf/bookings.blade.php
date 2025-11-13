<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo Cáo Bookings</title>
    <style>
        @page { margin: 2cm 1.5cm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #10B981; }
        .header h1 { color: #10B981; font-size: 24pt; margin: 0 0 10px 0; }
        .header p { color: #666; margin: 5px 0; }
        .stats-grid { display: table; width: 100%; margin-bottom: 30px; }
        .stat-box { display: table-cell; width: 33.33%; padding: 15px; text-align: center; border: 2px solid #E5E7EB; background: #F9FAFB; }
        .stat-label { font-size: 9pt; color: #6B7280; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .stat-value { font-size: 18pt; font-weight: bold; color: #10B981; }
        .section-title { font-size: 14pt; font-weight: bold; color: #1F2937; margin: 30px 0 15px 0; padding-bottom: 8px; border-bottom: 2px solid #E5E7EB; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { background: #10B981; color: white; padding: 12px 8px; text-align: left; font-size: 10pt; font-weight: bold; }
        td { padding: 10px 8px; border-bottom: 1px solid #E5E7EB; }
        tr:nth-child(even) { background: #F9FAFB; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #E5E7EB; text-align: center; color: #6B7280; font-size: 9pt; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 9pt; font-weight: bold; }
        .badge-pending { background: #FEF3C7; color: #92400E; }
        .badge-confirmed { background: #D1FAE5; color: #065F46; }
        .badge-cancelled { background: #FEE2E2; color: #991B1B; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO BOOKINGS</h1>
        <p><strong>Thời gian:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        <p><strong>Ngày xuất:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Tổng Bookings</div>
            <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
        </div>
        <div class="stat-box" style="border-left: none; border-right: none;">
            <div class="stat-label">Đã Xác Nhận</div>
            <div class="stat-value">{{ number_format($stats['confirmed_bookings']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Đã Hủy</div>
            <div class="stat-value">{{ number_format($stats['cancelled_bookings']) }}</div>
        </div>
    </div>

    <h2 class="section-title">Thống Kê Theo Trạng Thái</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 50%">Trạng Thái</th>
                <th style="width: 50%" class="text-right">Số Lượng</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookingsByStatus as $status)
                <tr>
                    <td>
                        @if($status->status == 'pending')
                            <span class="badge badge-pending">Chờ Xác Nhận</span>
                        @elseif($status->status == 'confirmed')
                            <span class="badge badge-confirmed">Đã Xác Nhận</span>
                        @elseif($status->status == 'cancelled')
                            <span class="badge badge-cancelled">Đã Hủy</span>
                        @else
                            {{ ucfirst($status->status) }}
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($status->count) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center" style="padding: 20px; color: #6B7280;">
                        Không có dữ liệu
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Top 10 Tours Theo Số Booking</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">#</th>
                <th style="width: 65%">Tên Tour</th>
                <th style="width: 25%" class="text-right">Số Bookings</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookingsByTour as $index => $tour)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tour->name }}</td>
                    <td class="text-right" style="font-weight: bold; color: #10B981;">{{ number_format($tour->count) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center" style="padding: 20px; color: #6B7280;">
                        Không có dữ liệu
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Tour Manager System</strong> | Báo cáo được tạo tự động</p>
    </div>
</body>
</html>
