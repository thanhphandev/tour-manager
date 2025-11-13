<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo Cáo Tours</title>
    <style>
        @page { margin: 2cm 1.5cm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #8B5CF6; }
        .header h1 { color: #8B5CF6; font-size: 24pt; margin: 0 0 10px 0; }
        .header p { color: #666; margin: 5px 0; }
        .section-title { font-size: 14pt; font-weight: bold; color: #1F2937; margin: 30px 0 15px 0; padding-bottom: 8px; border-bottom: 2px solid #E5E7EB; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { background: #8B5CF6; color: white; padding: 12px 8px; text-align: left; font-size: 10pt; font-weight: bold; }
        td { padding: 10px 8px; border-bottom: 1px solid #E5E7EB; }
        tr:nth-child(even) { background: #F9FAFB; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #E5E7EB; text-align: center; color: #6B7280; font-size: 9pt; }
        .star { color: #F59E0B; font-size: 12pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO TOURS</h1>
        <p><strong>Ngày xuất:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <h2 class="section-title">Top 10 Tours Phổ Biến Nhất</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">#</th>
                <th style="width: 65%">Tên Tour</th>
                <th style="width: 25%" class="text-right">Số Bookings</th>
            </tr>
        </thead>
        <tbody>
            @forelse($popularTours as $index => $tour)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tour->name }}</td>
                    <td class="text-right" style="font-weight: bold; color: #8B5CF6;">{{ $tour->bookings_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center" style="padding: 20px; color: #6B7280;">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Top 10 Tours Theo Doanh Thu</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">#</th>
                <th style="width: 60%">Tên Tour</th>
                <th style="width: 30%" class="text-right">Doanh Thu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($toursByRevenue as $index => $tour)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tour->name }}</td>
                    <td class="text-right" style="font-weight: bold; color: #059669;">{{ number_format($tour->revenue) }}đ</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center" style="padding: 20px; color: #6B7280;">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Top 10 Tours Theo Đánh Giá</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">#</th>
                <th style="width: 55%">Tên Tour</th>
                <th style="width: 20%" class="text-center">Đánh Giá TB</th>
                <th style="width: 15%" class="text-right">Reviews</th>
            </tr>
        </thead>
        <tbody>
            @forelse($toursByRating as $index => $tour)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tour->name }}</td>
                    <td class="text-center">
                        <span class="star">★</span> 
                        <strong>{{ number_format($tour->avg_rating, 1) }}</strong>
                    </td>
                    <td class="text-right">{{ $tour->review_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px; color: #6B7280;">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Tour Manager System</strong> | Báo cáo được tạo tự động</p>
    </div>
</body>
</html>
