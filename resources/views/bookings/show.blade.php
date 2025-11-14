<x-client-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay lại
                </a>
                <h1 class="text-4xl font-bold text-gray-900">Chi tiết đặt tour</h1>
                <p class="text-gray-600 mt-2">Mã đặt chỗ: <span class="font-mono font-semibold text-indigo-600">{{ $booking->booking_code }}</span></p>
            </div>

            <!-- Booking Status -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Trạng thái đặt chỗ</h2>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                <span class="w-2 h-2 rounded-full mr-2
                                    {{ $booking->status === 'confirmed' ? 'bg-green-600' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-600' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-600' : '' }}"></span>
                                {{ $booking->status === 'confirmed' ? 'Đã xác nhận' : '' }}
                                {{ $booking->status === 'pending' ? 'Chờ xác nhận' : '' }}
                                {{ $booking->status === 'cancelled' ? 'Đã hủy' : '' }}
                            </span>
                            
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->payment_status === 'awaiting_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->payment_status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $booking->payment_status === 'payment_failed' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $booking->payment_status === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $booking->payment_status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                {{ $booking->status_label }}
                            </span>
                        </div>
                    </div>
                    
                    @if($booking->canPay())
                        <a href="{{ route('payments.show', $booking) }}" 
                           class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-xl transition-all duration-300 hover:scale-105">
                            Thanh toán ngay
                        </a>
                    @endif
                </div>
            </div>

            <!-- Tour Information -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
                <div class="md:flex">
                    <div class="md:w-1/3">
                        <img src="{{ $booking->tour->thumbnail ? asset('storage/' . $booking->tour->thumbnail) : 'https://via.placeholder.com/400x300' }}" 
                             alt="{{ $booking->tour->title }}" 
                             class="w-full h-64 object-cover">
                    </div>
                    <div class="md:w-2/3 p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->tour->title }}</h3>
                        <div class="space-y-2 text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $booking->tour->destination->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $booking->tour->duration_days }} ngày {{ $booking->tour->duration_days - 1 }} đêm</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($booking->tour->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->tour->end_date)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Thông tin khách hàng
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Họ và tên</label>
                            <p class="font-semibold text-gray-900">{{ $booking->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Email</label>
                            <p class="font-semibold text-gray-900">{{ $booking->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Số điện thoại</label>
                            <p class="font-semibold text-gray-900">{{ $booking->phone }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Thông tin đoàn
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Người lớn</span>
                            <span class="font-semibold text-gray-900">{{ $booking->adults }} người</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Trẻ em</span>
                            <span class="font-semibold text-gray-900">{{ $booking->children }} người</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Em bé</span>
                            <span class="font-semibold text-gray-900">{{ $booking->infants }} người</span>
                        </div>
                        <div class="pt-3 border-t">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900">Tổng số người</span>
                                <span class="font-bold text-indigo-600">{{ $booking->total_people }} người</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Request -->
            @if($booking->special_requests)
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Yêu cầu đặc biệt
                </h3>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $booking->special_request }}</p>
            </div>
            @endif

            <!-- Payment Information -->
            @php
                $successfulPayment = $booking->getSuccessfulPayment();
                $latestPayment = $booking->getLatestPayment();
                $displayPayment = $successfulPayment ?? $latestPayment;
            @endphp
            @if($displayPayment)
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Thông tin thanh toán
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Mã thanh toán</label>
                        <p class="font-mono font-semibold text-gray-900">{{ $displayPayment->payment_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phương thức</label>
                        <p class="font-semibold text-gray-900 capitalize">{{ $displayPayment->payment_method }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Trạng thái</label>
                        <p class="font-semibold text-gray-900">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs
                                {{ $displayPayment->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $displayPayment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $displayPayment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $displayPayment->status === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ ucfirst($displayPayment->status) }}
                            </span>
                        </p>
                    </div>
                    @if($displayPayment->transaction_id)
                    <div>
                        <label class="text-sm text-gray-600">Mã giao dịch</label>
                        <p class="font-mono font-semibold text-gray-900">{{ $displayPayment->transaction_id }}</p>
                    </div>
                    @endif
                    @if($displayPayment->paid_at)
                    <div>
                        <label class="text-sm text-gray-600">Thời gian thanh toán</label>
                        <p class="font-semibold text-gray-900">{{ $displayPayment->paid_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($displayPayment->notes)
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">Ghi chú</label>
                        <p class="text-gray-900">{{ $displayPayment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Price Summary -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Tổng chi phí</h3>
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-indigo-100">
                        <span>Chi tiết giá tour</span>
                        <span>{{ number_format($booking->total_amount) }} VNĐ</span>
                    </div>
                </div>
                <div class="border-t border-indigo-400 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold">Tổng cộng</span>
                        <span class="text-3xl font-bold">{{ number_format($booking->total_amount) }} VNĐ</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-4">
                @if($booking->canPay())
                <a href="{{ route('payments.show', $booking) }}" 
                   class="flex-1 min-w-[200px] bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-xl font-semibold text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                    Thanh toán ngay
                </a>
                @endif

                @if($canReview)
                <a href="{{ route('reviews.create', $booking->tour) }}" 
                   class="flex-1 min-w-[200px] bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white px-6 py-4 rounded-xl font-semibold text-center hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Viết Đánh Giá
                </a>
                @endif

                @if($booking->isPaid() && !$canReview && !auth()->user()->isAdmin())
                <a href="{{ route('tours.show', $booking->tour) }}" 
                   class="flex-1 min-w-[200px] bg-purple-600 hover:bg-purple-700 text-white px-6 py-4 rounded-xl font-semibold text-center transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Xem tour
                </a>
                @endif
                
                @if($booking->canCancel())
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="flex-1 min-w-[200px]" onsubmit="return confirm('Bạn có chắc muốn hủy đặt tour này?')">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-6 py-4 rounded-xl font-semibold hover:bg-red-700 transition-all duration-300">
                        Hủy đặt tour
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
