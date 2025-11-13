<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo Cáo Khách Hàng</title>
    <style>
        @page { margin: 2cm 1.5cm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #3B82F6; }
        .header h1 { color: #3B82F6; font-size: 24pt; margin: 0 0 10px 0; }
        .header p { color: #666; margin: 5px 0; }
        .stats-grid { display: table; width: 100%; margin-bottom: 30px; }
        .stat-box { display: table-cell; width: 33.33%; padding: 15px; text-align: center; border: 2px solid #E5E7EB; background: #F9FAFB; }
        .stat-label { font-size: 9pt; color: #6B7280; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .stat-value { font-size: 18pt; font-weight: bold; color: #3B82F6; }
        .section-title { font-size: 14pt; font-weight: bold; color: #1F2937; margin: 30px 0 15px 0; padding-bottom: 8px; border-bottom: 2px solid #E5E7EB; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th { background: #3B82F6; color: white; padding: 12px 8px; text-align: left; font-size: 10pt; font-weight: bold; }
        td { padding: 10px 8px; border-bottom: 1px solid #E5E7EB; }
        tr:nth-child(even) { background: #F9FAFB; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #E5E7EB; text-align: center; color: #6B7280; font-size: 9pt; }
        .amount { color: #059669; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO KHÁCH HÀNG</h1>
        <p><strong>Ngày xuất:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Tổng Khách Hàng</div>
            <div class="stat-value">{{ number_format($totalCustomers) }}</div>
        </div>
        <div class="stat-box" style="border-left: none; border-right: none;">
            <div class="stat-label">Mới Tháng Này</div>
            <div class="stat-value">{{ number_format($newCustomers) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Khách Tích Cực</div>
            <div class="stat-value">{{ number_format($activeCustomers) }}</div>
        </div>
    </div>

    <h2 class="section-title">Top 10 Khách Hàng Theo Số Booking</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 8%">#</th>
                <th style="width: 40%">Tên Khách Hàng</th>
                <th style="width: 37%">Email</th>
                <th style="width: 15%" class="text-right">Bookings</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCustomers as $index => $customer)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td style="font-size: 9pt; color: #6B7280;">{{ $customer->email }}</td>
                    <td class="text-right" style="font-weight: bold; color: #3B82F6;">{{ $customer->bookings_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px; color: #6B7280;">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Top 10 Khách Hàng Theo Chi Tiêu</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 8%">#</th>
                <th style="width: 40%">Tên Khách Hàng</th>
                <th style="width: 37%">Email</th>
                <th style="width: 15%" class="text-right">Tổng Chi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topSpenders as $index => $customer)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td style="font-size: 9pt; color: #6B7280;">{{ $customer->email }}</td>
                    <td class="text-right amount">{{ number_format($customer->total_spent) }}đ</td>
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
