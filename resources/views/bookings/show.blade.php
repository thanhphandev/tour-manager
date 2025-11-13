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
                                {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : '' }}
                                {{ $booking->payment_status === 'pending' ? 'Chưa thanh toán' : '' }}
                                {{ $booking->payment_status === 'failed' ? 'Thanh toán thất bại' : '' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
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
            @if($booking->payment)
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
                        <p class="font-mono font-semibold text-gray-900">{{ $booking->payment->payment_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phương thức</label>
                        <p class="font-semibold text-gray-900 capitalize">{{ $booking->payment->payment_method }}</p>
                    </div>
                    @if($booking->payment->transaction_id)
                    <div>
                        <label class="text-sm text-gray-600">Mã giao dịch</label>
                        <p class="font-mono font-semibold text-gray-900">{{ $booking->payment->transaction_id }}</p>
                    </div>
                    @endif
                    @if($booking->payment->paid_at)
                    <div>
                        <label class="text-sm text-gray-600">Thời gian thanh toán</label>
                        <p class="font-semibold text-gray-900">{{ $booking->payment->paid_at->format('d/m/Y H:i') }}</p>
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
            @if($booking->status !== 'cancelled')
            <div class="mt-8 flex gap-4">
                @if($booking->payment_status !== 'paid')
                <a href="{{ route('payments.show', $booking) }}" 
                   class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-xl font-semibold text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                    Thanh toán ngay
                </a>
                @endif
                
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="flex-1" onsubmit="return confirm('Bạn có chắc muốn hủy đặt tour này?')">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-6 py-4 rounded-xl font-semibold hover:bg-red-700 transition-all duration-300">
                        Hủy đặt tour
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-client-layout>
