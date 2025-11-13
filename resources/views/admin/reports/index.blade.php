<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Báo Cáo & Thống Kê</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Report -->
        <a href="{{ route('admin.reports.revenue') }}" class="bg-gradient-to-br from-green-500 to-green-700 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Báo Cáo</p>
                    <h3 class="text-2xl font-bold mt-2">Doanh Thu</h3>
                    <p class="text-green-100 text-sm mt-2">Theo ngày, tháng, tour</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <!-- Bookings Report -->
        <a href="{{ route('admin.reports.bookings') }}" class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Báo Cáo</p>
                    <h3 class="text-2xl font-bold mt-2">Đặt Chỗ</h3>
                    <p class="text-blue-100 text-sm mt-2">Thống kê booking</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </a>

        <!-- Tours Report -->
        <a href="{{ route('admin.reports.tours') }}" class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Báo Cáo</p>
                    <h3 class="text-2xl font-bold mt-2">Tours</h3>
                    <p class="text-purple-100 text-sm mt-2">Hiệu suất tours</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <!-- Customers Report -->
        <a href="{{ route('admin.reports.customers') }}" class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Báo Cáo</p>
                    <h3 class="text-2xl font-bold mt-2">Khách Hàng</h3>
                    <p class="text-orange-100 text-sm mt-2">Top khách hàng</p>
                </div>
                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Thống Kê Nhanh</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Tổng Tours</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Tour::count() }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Tổng Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Booking::count() }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Tổng Khách Hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('is_admin', false)->count() }}</p>
            </div>
        </div>
    </div>
</x-admin-layout>
