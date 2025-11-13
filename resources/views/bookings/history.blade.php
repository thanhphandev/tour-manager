<x-client-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Lịch sử đặt tour</h1>
                        <p class="text-gray-600">Quản lý và theo dõi tất cả các booking của bạn</p>
                    </div>
                    <a href="{{ route('tours.index') }}" 
                       class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Đặt tour mới
                    </a>
                </div>
            </div>

            @if($bookings->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Chưa có booking nào</h2>
                    <p class="text-gray-600 mb-8">Bạn chưa đặt tour nào. Khám phá các tour du lịch tuyệt vời của chúng tôi!</p>
                    <a href="{{ route('tours.index') }}" 
                       class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold hover:shadow-xl transition-all duration-300 hover:scale-105">
                        Khám phá tours
                    </a>
                </div>
            @else
                <!-- Summary Cards -->
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tổng booking</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $bookings->total() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Đã xác nhận</p>
                                <p class="text-3xl font-bold text-green-600">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Chờ xử lý</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ $bookings->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Đã hủy</p>
                                <p class="text-3xl font-bold text-red-600">{{ $bookings->where('status', 'cancelled')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings List -->
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="md:flex">
                            <!-- Tour Image -->
                            <div class="md:w-1/4">
                                <img src="{{ $booking->tour->thumbnail ? asset('storage/' . $booking->tour->thumbnail) : 'https://via.placeholder.com/400x300' }}" 
                                     alt="{{ $booking->tour->title }}" 
                                     class="w-full h-64 md:h-full object-cover">
                            </div>

                            <!-- Booking Details -->
                            <div class="md:w-3/4 p-6">
                                <div class="flex flex-col h-full">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->tour->title }}</h3>
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $booking->tour->destination->name }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $booking->tour->duration_days }} ngày {{ $booking->tour->duration_days - 1 }} đêm
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    {{ $booking->total_people }} người
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Status Badges -->
                                        <div class="flex flex-col gap-2 items-end">
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                <span class="w-2 h-2 rounded-full mr-2
                                                    {{ $booking->status === 'confirmed' ? 'bg-green-600' : '' }}
                                                    {{ $booking->status === 'pending' ? 'bg-yellow-600' : '' }}
                                                    {{ $booking->status === 'cancelled' ? 'bg-red-600' : '' }}"></span>
                                                {{ $booking->status === 'confirmed' ? 'Đã xác nhận' : '' }}
                                                {{ $booking->status === 'pending' ? 'Chờ xử lý' : '' }}
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

                                    <!-- Booking Info -->
                                    <div class="grid md:grid-cols-2 gap-4 mb-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Mã booking:</span>
                                            <span class="font-mono font-semibold text-indigo-600 ml-2">{{ $booking->booking_code }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Ngày đặt:</span>
                                            <span class="font-semibold text-gray-900 ml-2">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Khách hàng:</span>
                                            <span class="font-semibold text-gray-900 ml-2">{{ $booking->name ?? $booking->full_name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Tổng tiền:</span>
                                            <span class="font-bold text-indigo-600 ml-2">{{ number_format($booking->total_amount) }} VNĐ</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-gray-200">
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold text-center transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Xem chi tiết
                                        </a>

                                        @if($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                                        <a href="{{ route('payments.show', $booking) }}" 
                                           class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold text-center transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Thanh toán ngay
                                        </a>
                                        @endif

                                        @if($booking->payment_status === 'paid' && $booking->status === 'confirmed')
                                        <a href="{{ route('tours.show', $booking->tour) }}" 
                                           class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold text-center transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                            </svg>
                                            Xem tour
                                        </a>
                                        @endif

                                        @if($booking->status !== 'cancelled')
                                        <form action="{{ route('bookings.cancel', $booking) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn hủy booking này?')"
                                              class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Hủy booking
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</x-client-layout>
