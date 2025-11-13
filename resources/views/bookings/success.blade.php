<x-client-layout>
    <!-- Success Animation -->
    <section class="py-20 bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Success Icon -->
            <div class="mb-8 animate-bounce">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full shadow-2xl">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                🎉 Thanh Toán Thành Công!
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Cảm ơn bạn đã đặt tour với TravelGo. Chúng tôi sẽ liên hệ với bạn sớm nhất!
            </p>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 text-left">
                <h2 class="text-2xl font-black text-gray-900 mb-6 text-center">
                    Thông Tin Đặt Tour
                </h2>

                <div class="space-y-4">
                    <!-- Booking Code -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Mã đặt tour:</span>
                        <span class="font-bold text-indigo-600 text-lg font-mono">{{ $booking->booking_code }}</span>
                    </div>

                    <!-- Tour Name -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Tour:</span>
                        <span class="font-bold text-gray-900">{{ $booking->tour->name }}</span>
                    </div>

                    <!-- Customer Info -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Khách hàng:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->name }}</span>
                    </div>

                    <!-- Email -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->email }}</span>
                    </div>

                    <!-- Phone -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Điện thoại:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->phone }}</span>
                    </div>

                    <!-- Participants -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Số người:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->total_people }} người ({{ $booking->adults }} NL + {{ $booking->children }} TE + {{ $booking->infants }} EB)</span>
                    </div>

                    <!-- Payment Info -->
                    @if($booking->payment)
                    <div class="bg-green-50 rounded-lg p-4 mb-4">
                        <h3 class="font-bold text-green-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Thông tin thanh toán
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã thanh toán:</span>
                                <span class="font-mono font-semibold text-gray-900">{{ $booking->payment->payment_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phương thức:</span>
                                <span class="font-semibold text-gray-900 capitalize">{{ $booking->payment->payment_method }}</span>
                            </div>
                            @if($booking->payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã giao dịch:</span>
                                <span class="font-mono font-semibold text-gray-900">{{ $booking->payment->transaction_id }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Thời gian:</span>
                                <span class="font-semibold text-gray-900">{{ $booking->payment->paid_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Total Price -->
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-xl font-bold text-gray-900">Tổng tiền:</span>
                        <span class="text-3xl font-black text-indigo-600">
                            {{ number_format($booking->total_amount) }}đ
                        </span>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center bg-green-100 text-green-800 px-6 py-3 rounded-full font-bold text-lg">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Đã thanh toán & xác nhận
                    </span>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-2xl p-6 mb-8 text-left">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Bước Tiếp Theo
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Chúng tôi sẽ gửi email xác nhận đến <strong>{{ $booking->email }}</strong>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Nhân viên sẽ liên hệ với bạn qua số <strong>{{ $booking->phone }}</strong> trong 24h
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Vui lòng kiểm tra email (cả thư mục spam) để biết thêm chi tiết
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Chuẩn bị hành trang và giấy tờ cần thiết trước ngày khởi hành
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-white hover:bg-gray-50 text-gray-800 border-2 border-gray-300 px-8 py-4 rounded-xl font-bold text-center transition-colors">
                    ← Về Trang Chủ
                </a>
                <a href="{{ route('tours.index') }}" 
                   class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    Xem Thêm Tours
                </a>
            </div>

            <!-- Contact Info -->
            <div class="mt-12 pt-8 border-t border-gray-300">
                <p class="text-gray-600 mb-4">
                    Có thắc mắc? Liên hệ với chúng tôi:
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-gray-700">
                    <a href="tel:1900xxxx" class="flex items-center hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="font-semibold">1900-xxxx</span>
                    </a>
                    <a href="mailto:contact@travelgo.vn" class="flex items-center hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold">contact@travelgo.vn</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-bounce {
            animation: bounce 2s infinite;
        }
    </style>
    @endpush
</x-client-layout>
