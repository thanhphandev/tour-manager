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
                        <img src="{{ $booking->tour->getThumbnailUrl() ?? 'https://via.placeholder.com/400x300' }}" 
                             alt="{{ $booking->tour->name }}" 
                             class="w-full h-64 object-cover">
                    </div>
                    <div class="md:w-2/3 p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->tour->name }}</h3>
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
                            <div class="flex items-center">
                                <svg fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5 mr-2 text-indigo-600">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                </svg>

                                <a href="{{ route('tours.show', $booking->tour) }}" class="underline">
                                    Thông tin tour
                                </a>
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

            <!-- Payment History -->
            @if($booking->payments->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Lịch sử thanh toán
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $booking->payments->count() }} lần)</span>
                </h3>
                
                <div class="space-y-4">
                    @foreach($booking->payments as $payment)
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 transition-colors
                        {{ $payment->status === 'success' ? 'bg-green-50' : '' }}
                        {{ $payment->status === 'pending' ? 'bg-yellow-50' : '' }}
                        {{ $payment->status === 'failed' ? 'bg-red-50' : '' }}
                        {{ $payment->status === 'refunded' ? 'bg-purple-50' : '' }}">
                        
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $payment->status === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    @if($payment->status === 'success')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Thành công
                                    @elseif($payment->status === 'pending')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Đang xử lý
                                    @elseif($payment->status === 'failed')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Thất bại
                                    @elseif($payment->status === 'refunded')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                        </svg>
                                        Đã hoàn tiền
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ number_format($payment->amount) }} VNĐ</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Mã thanh toán:</span>
                                <span class="font-mono font-semibold text-gray-900 ml-1">{{ $payment->payment_code }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Phương thức:</span>
                                <span class="font-semibold text-gray-900 ml-1 capitalize">{{ $payment->payment_method }}</span>
                            </div>
                            @if($payment->transaction_id)
                            <div class="col-span-2">
                                <span class="text-gray-600">Mã giao dịch:</span>
                                <span class="font-mono font-semibold text-gray-900 ml-1">{{ $payment->transaction_id }}</span>
                            </div>
                            @endif
                            @if($payment->paid_at)
                            <div class="col-span-2">
                                <span class="text-gray-600">Thanh toán lúc:</span>
                                <span class="font-semibold text-gray-900 ml-1">{{ $payment->paid_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            @endif
                            @if($payment->notes)
                            <div class="col-span-2">
                                <span class="text-gray-600">Ghi chú:</span>
                                <span class="text-gray-900 ml-1">{{ $payment->notes }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
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
