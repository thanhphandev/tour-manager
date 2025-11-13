<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Báo Cáo Bookings</h1>
        <a href="{{ route('admin.reports.bookings.pdf', request()->all()) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
            </svg>
            Xuất PDF
        </a>
    </div>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Tổng Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Đã Xác Nhận</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['confirmed_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Đã Hủy</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['cancelled_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">TB Người/Booking</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ round($stats['average_people_per_booking'], 1) }}</p>
            </div>
        </div>

        <!-- Bookings by Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Bookings Theo Trạng Thái</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($bookingsByStatus as $status)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">{{ ucfirst($status->status) }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $status->count }} bookings</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Tours by Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Tours Phổ Biến</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tour</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Số Bookings</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookingsByTour as $index => $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-right text-gray-900">{{ $item->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
