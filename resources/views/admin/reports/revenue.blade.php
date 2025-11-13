<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Báo Cáo Doanh Thu</h1>
        <a href="{{ route('admin.reports.revenue.pdf', request()->all()) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
            </svg>
            Xuất PDF
        </a>
    </div>

    <div class="space-y-6">
        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Từ Ngày</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Đến Ngày</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Xem Báo Cáo
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Tổng Doanh Thu</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_revenue']) }}đ</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Tổng Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Giá Trị TB/Booking</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['average_booking_value']) }}đ</p>
            </div>
        </div>

        <!-- Revenue by Tour -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Tours Theo Doanh Thu</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tour</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Doanh Thu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($revenueByTour as $index => $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-right text-gray-900">{{ number_format($item->total) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revenue by Provider -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Doanh Thu Theo Phương Thức Thanh Toán</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($revenueByProvider as $provider)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">{{ strtoupper($provider->provider) }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($provider->total) }}đ</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
