<x-client-layout>
    <div class="min-h-screen bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Error Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6 animate-pulse">
                    <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Thanh Toán Thất Bại</h1>
                <p class="text-lg text-gray-600">Rất tiếc, giao dịch của bạn không thể hoàn tất</p>
            </div>

            <!-- Error Details Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
                <!-- Error Message -->
                <div class="bg-gradient-to-r from-red-500 to-orange-500 px-8 py-6">
                    <div class="flex items-start text-white">
                        <svg class="w-6 h-6 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h2 class="text-xl font-bold mb-1">{{ $errorMessage ?? 'Giao dịch không thành công' }}</h2>
                            @if(isset($errorCode))
                                <p class="text-sm opacity-90">Mã lỗi: {{ $errorCode }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Booking Information -->
                @if(isset($booking))
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Đặt Chỗ</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Mã đặt chỗ</p>
                            <p class="font-semibold text-gray-900">{{ $booking->booking_code }}</p>
                        </div>
                        @if(isset($booking->tour))
                        <div>
                            <p class="text-sm text-gray-600">Tour</p>
                            <p class="font-semibold text-gray-900">{{ Str::limit($booking->tour->name, 40) }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600">Số tiền</p>
                            <p class="font-semibold text-red-600 text-lg">{{ number_format($booking->total_amount) }} đ</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Trạng thái</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Chưa thanh toán
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Transaction Details -->
                @if(isset($transactionId) || isset($paymentMethod))
                <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Chi Tiết Giao Dịch</h3>
                    <div class="space-y-2 text-sm">
                        @if(isset($transactionId))
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã giao dịch:</span>
                            <span class="font-medium text-gray-900">{{ $transactionId }}</span>
                        </div>
                        @endif
                        @if(isset($paymentMethod))
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phương thức:</span>
                            <span class="font-medium text-gray-900">{{ strtoupper($paymentMethod) }}</span>
                        </div>
                        @endif
                        @if(isset($failedAt))
                        <div class="flex justify-between">
                            <span class="text-gray-600">Thời gian:</span>
                            <span class="font-medium text-gray-900">{{ $failedAt }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Possible Reasons -->
                <div class="px-8 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Nguyên Nhân Có Thể</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Thông tin thẻ hoặc tài khoản không chính xác</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Số dư tài khoản không đủ hoặc vượt quá hạn mức giao dịch</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Thẻ/Tài khoản chưa đăng ký dịch vụ thanh toán trực tuyến</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Thẻ/Tài khoản đã bị khóa hoặc hết hạn</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Nhập sai mã OTP hoặc hủy giao dịch</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Ngân hàng đang bảo trì hoặc gặp sự cố kỹ thuật</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                @if(isset($booking))
                <!-- Retry Payment -->
                <a href="{{ route('payments.show', $booking) }}" 
                   class="w-full flex justify-center items-center py-4 px-6 border-2 border-indigo-600 rounded-lg text-base font-semibold text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Thử Lại Thanh Toán
                </a>

                <!-- View Booking -->
                <a href="{{ route('bookings.show', $booking) }}" 
                   class="w-full flex justify-center items-center py-4 px-6 border-2 border-gray-300 rounded-lg text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Xem Chi Tiết Đặt Chỗ
                </a>
                @endif

                <!-- Back to Bookings -->
                <a href="{{ route('bookings.history') }}" 
                   class="w-full flex justify-center items-center py-4 px-6 text-base font-medium text-gray-600 hover:text-gray-800 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay Lại Danh Sách Đặt Chỗ
                </a>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Cần Hỗ Trợ?</h3>
                        <p class="text-blue-800 text-sm mb-4">
                            Nếu bạn cần hỗ trợ về vấn đề thanh toán, vui lòng liên hệ với chúng tôi:
                        </p>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-blue-900">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="font-medium">Hotline: 1900 xxxx (8:00 - 22:00)</span>
                            </div>
                            <div class="flex items-center text-blue-900">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Email: support@tourmanager.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-scroll to top on load
        window.addEventListener('load', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    @endpush
</x-client-layout>
