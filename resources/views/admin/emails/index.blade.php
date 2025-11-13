<x-admin-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Hệ Thống Email</h1>
        <p class="text-gray-600">Quản lý và gửi email tự động</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Email Stats -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Email Templates</p>
                    <h3 class="text-4xl font-bold">{{ \App\Models\EmailTemplate::count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Active Templates</p>
                    <h3 class="text-4xl font-bold">{{ \App\Models\EmailTemplate::where('is_active', true)->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Users</p>
                    <h3 class="text-4xl font-bold">{{ \App\Models\User::where('is_admin', false)->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Quick Actions
            </h2>
            <div class="space-y-4">
                <a href="{{ route('admin.emails.compose') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg hover:shadow-md transition group">
                    <div class="p-3 bg-blue-600 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Compose Email</h3>
                        <p class="text-sm text-gray-600">Gửi email tới khách hàng</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ route('admin.email-templates.index') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg hover:shadow-md transition group">
                    <div class="p-3 bg-green-600 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-green-600">Email Templates</h3>
                        <p class="text-sm text-gray-600">Quản lý mẫu email</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Automated Emails -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Automated Emails
            </h2>
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">Booking Confirmation</h3>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Tự động gửi khi khách hàng đặt tour</p>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Triggered: On booking creation
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">Payment Confirmation</h3>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Tự động gửi khi thanh toán thành công</p>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Triggered: On payment success
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">Tour Reminder</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Scheduled</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Gửi 3 ngày trước khi tour khởi hành</p>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Command: php artisan tour:send-reminders
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">Review Request</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Scheduled</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Gửi 2 ngày sau khi tour kết thúc</p>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Command: php artisan tour:send-review-requests
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Command Examples -->
    <div class="mt-6 bg-gray-900 rounded-xl shadow-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Artisan Commands
        </h2>
        <div class="space-y-3 font-mono text-sm">
            <div class="flex items-center bg-gray-800 rounded-lg p-3">
                <span class="text-green-400 mr-3">$</span>
                <code class="flex-1">php artisan tour:send-reminders --days=3</code>
                <span class="text-gray-500 text-xs ml-4">Gửi email nhắc nhở tour</span>
            </div>
            <div class="flex items-center bg-gray-800 rounded-lg p-3">
                <span class="text-green-400 mr-3">$</span>
                <code class="flex-1">php artisan tour:send-review-requests --days=2</code>
                <span class="text-gray-500 text-xs ml-4">Gửi yêu cầu đánh giá</span>
            </div>
        </div>
        <div class="mt-4 p-4 bg-yellow-900/30 border border-yellow-600 rounded-lg">
            <p class="text-yellow-200 text-sm">
                <strong>💡 Tip:</strong> Bạn có thể thêm các command này vào Cron Job hoặc Task Scheduler để tự động chạy hàng ngày.
            </p>
        </div>
    </div>
</x-admin-layout>
