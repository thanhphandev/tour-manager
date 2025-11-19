<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Chi Tiết Đặt Chỗ #{{ $booking->id }}</h2>
                <p class="mt-1 text-sm text-gray-600">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.bookings.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay Lại
                </a>
                @if($booking->status === 'confirmed')
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    In Hóa Đơn
                </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Booking Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Thông Tin Đặt Chỗ</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">Mã Đặt Chỗ</dt>
                            <dd class="text-lg font-bold text-gray-900">#{{ $booking->booking_code }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">Trạng Thái</dt>
                            <dd class="mt-1">
                                @if($booking->status === 'confirmed')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Đã Xác Nhận
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1.5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Đang Chờ Xác Nhận
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Đã Hủy
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Ngày Đặt
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('d/m/Y - H:i') }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Số Người
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">
                                <div class="space-y-1">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">👨‍👩‍👧‍👦 Người lớn:</span>
                                        <span class="font-bold text-indigo-600">{{ $booking->adults }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">👶 Trẻ em:</span>
                                        <span class="font-bold text-blue-600">{{ $booking->children }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">🍼 Em bé:</span>
                                        <span class="font-bold text-green-600">{{ $booking->infants }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-gray-300">
                                        <span class="text-gray-900 font-semibold">Tổng:</span>
                                        <span class="font-bold text-gray-900">{{ $booking->total_people }}</span>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        <div class="md:col-span-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border-2 border-green-200">
                            <dt class="text-xs font-medium text-green-700 uppercase mb-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tổng Tiền
                            </dt>
                            <dd class="text-2xl font-black text-green-700">
                                {{ number_format($booking->total_amount, 0, ',', '.') }} <span class="text-lg">VND</span>
                            </dd>
                        </div>
                    </dl>

                    @if($booking->special_requests)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Yêu Cầu Đặc Biệt
                        </dt>
                        <dd class="text-sm text-gray-900 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            {{ $booking->special_requests }}
                        </dd>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Thông Tin Khách Hàng</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900">{{ $booking->user->name }}</h4>
                            <p class="text-sm text-gray-500">Khách hàng</p>
                        </div>
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <dt class="text-xs font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900 truncate">{{ $booking->email }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div class="flex-1">
                                <dt class="text-xs font-medium text-gray-500">Số Điện Thoại</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->phone ?? 'Chưa cập nhật' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <dt class="text-xs font-medium text-gray-500">Ngày Đăng Ký</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->user->created_at->format('d/m/Y') }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div class="flex-1">
                                <dt class="text-xs font-medium text-gray-500">Tổng Bookings</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $booking->user->bookings->count() }} lần đặt</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Tour Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Thông Tin Tour</h3>
                </div>
                <div class="p-6">
                    <div class="flex gap-6">
                        @if($booking->tour->thumbnail)
                        <img src="{{ asset('storage/' . $booking->tour->thumbnail) }}" 
                             alt="{{ $booking->tour->name }}" 
                             class="w-48 h-32 object-cover rounded-xl shadow-md">
                        @endif
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $booking->tour->name }}</h4>
                            <p class="text-sm text-gray-600 flex items-center mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $booking->tour->destination->name }}
                            </p>
                            <dl class="grid grid-cols-2 gap-4">
                                <div class="bg-purple-50 rounded-lg p-3">
                                    <dt class="text-xs font-medium text-purple-700">Thời Gian</dt>
                                    <dd class="text-sm font-semibold text-purple-900">{{ $booking->tour->duration_days }} ngày {{ $booking->tour->duration_nights }} đêm</dd>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-3">
                                    <dt class="text-xs font-medium text-purple-700">Giá Trung Bình</dt>
                                    <dd class="text-sm font-semibold text-purple-900">{{ number_format($booking->total_amount / $booking->total_people, 0, ',', '.') }} VND/người</dd>
                                </div>
                            </dl>
                            <a href="{{ route('admin.tours.show', $booking->tour) }}" 
                               class="mt-4 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Xem Chi Tiết Tour
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            @if($booking->status === 'pending')
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl shadow-sm border-2 border-yellow-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Chờ Xác Nhận
                </h3>
                <p class="text-sm text-gray-600 mb-4">Booking này đang chờ được xác nhận. Hãy kiểm tra và thực hiện hành động.</p>
                <div class="space-y-3">
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-all shadow-md hover:shadow-lg font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Xác Nhận Đặt Chỗ
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.bookings.cancel', $booking) }}" 
                          method="POST"
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đặt chỗ này?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-all shadow-md hover:shadow-lg font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Hủy Đặt Chỗ
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Trạng Thái</h3>
                <div class="text-center py-6">
                    @if($booking->status === 'confirmed')
                        <svg class="w-16 h-16 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-lg font-semibold text-green-700">Đã Xác Nhận</p>
                        <p class="text-sm text-gray-500 mt-1">Booking đã được xác nhận</p>
                    @else
                        <svg class="w-16 h-16 mx-auto text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-lg font-semibold text-red-700">Đã Hủy</p>
                        <p class="text-sm text-gray-500 mt-1">Booking đã bị hủy</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lịch Sử
                </h3>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Đặt chỗ được tạo</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $booking->created_at->format('d/m/Y - H:i') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    @if($booking->status !== 'pending')
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 {{ $booking->status === 'confirmed' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                            @if($booking->status === 'confirmed')
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $booking->status === 'confirmed' ? 'Đã xác nhận' : 'Đã hủy' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $booking->updated_at->format('d/m/Y - H:i') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $booking->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            @if($booking->payments && $booking->payments->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Thanh Toán
                </h3>
                <div class="space-y-3">
                    @foreach($booking->payments as $payment)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-500">Mã GD: #{{ $payment->payment_code }}</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-gray-900">{{ number_format($payment->amount, 0, ',', '.') }} VND</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
