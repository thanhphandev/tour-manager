<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo Cáo Doanh Thu</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
        }
        .header h1 {
            color: #4F46E5;
            font-size: 24pt;
            margin: 0 0 10px 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 2px solid #E5E7EB;
            background: #F9FAFB;
        }
        .stat-label {
            font-size: 9pt;
            color: #6B7280;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 18pt;
            font-weight: bold;
            color: #4F46E5;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1F2937;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #E5E7EB;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th {
            background: #4F46E5;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 10pt;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        tr:nth-child(even) {
            background: #F9FAFB;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 9pt;
        }
        .amount {
            color: #059669;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO DOANH THU</h1>
        <p><strong>Thời gian:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        <p><strong>Ngày xuất:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Tổng Doanh Thu</div>
            <div class="stat-value">{{ number_format($stats['total_revenue']) }}đ</div>
        </div>
        <div class="stat-box" style="border-left: none; border-right: none;">
            <div class="stat-label">Tổng Bookings</div>
            <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Giá Trị TB</div>
            <div class="stat-value">{{ number_format($stats['average_booking_value']) }}đ</div>
        </div>
    </div>

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
            @forelse($revenueByTour as $index => $tour)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tour->name }}</td>
                    <td class="text-right amount">{{ number_format($tour->total) }}đ</td>
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

    <h2 class="section-title">Doanh Thu Theo Ngày</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 50%">Ngày</th>
                <th style="width: 50%" class="text-right">Doanh Thu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenueData as $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                    <td class="text-right amount">{{ number_format($data->total) }}đ</td>
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

    <div class="footer">
        <p><strong>Tour Manager System</strong> | Báo cáo được tạo tự động</p>
    </div>
</body>
</html>
