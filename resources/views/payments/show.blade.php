<x-client-layout>
    <div class="min-h-screen bg-gray-50 py-12" x-data="{ paymentMethod: 'paypal' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Thanh Toán Đặt Chỗ</h1>
                <p class="mt-3 text-lg text-gray-500">Hoàn tất bước cuối cùng để bắt đầu hành trình của bạn</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                <!-- Left Column: Payment Methods -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Method Selection -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Chọn phương thức thanh toán
                            </h2>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <!-- PayPal Option -->
                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="paymentMethod === 'paypal' ? 'border-indigo-600 bg-indigo-50/30 ring-1 ring-indigo-600' : 'border-gray-200 hover:border-indigo-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="paypal" class="sr-only" x-model="paymentMethod">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-10 flex items-center justify-center bg-white rounded-lg border border-gray-200 shadow-sm">
                                            <img src="{{ asset('images/paypal.svg') }}" class="h-6 w-auto" alt="PayPal">
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-gray-900">PayPal</p>
                                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Quốc tế</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-0.5">Thanh toán an toàn, bảo vệ người mua</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                            :class="paymentMethod === 'paypal' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-white" x-show="paymentMethod === 'paypal'"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- VNPay Option -->
                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="paymentMethod === 'vnpay' ? 'border-indigo-600 bg-indigo-50/30 ring-1 ring-indigo-600' : 'border-gray-200 hover:border-indigo-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="vnpay" class="sr-only" x-model="paymentMethod">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-10 flex items-center justify-center bg-white rounded-lg border border-gray-200 shadow-sm">
                                            <img src="{{ asset('images/vnpay.png') }}" class="h-5 w-auto" alt="VNPay">
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-gray-900">VNPay QR</p>
                                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">Phổ biến</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-0.5">Quét mã QR từ ứng dụng ngân hàng</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                            :class="paymentMethod === 'vnpay' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-white" x-show="paymentMethod === 'vnpay'"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Credit Card (Mock) Option -->
                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="paymentMethod === 'mock' ? 'border-indigo-600 bg-indigo-50/30 ring-1 ring-indigo-600' : 'border-gray-200 hover:border-indigo-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="mock" class="sr-only" x-model="paymentMethod">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-10 flex items-center justify-center bg-white rounded-lg border border-gray-200 shadow-sm">
                                            <img src="{{ asset('images/visa.png') }}" class="h-6 w-auto" alt="Visa">
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-gray-900">Thẻ Quốc Tế</p>
                                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">Demo</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-0.5">Visa, MasterCard, JCB</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                            :class="paymentMethod === 'mock' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-white" x-show="paymentMethod === 'mock'"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Details Form Area -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                        
                        <!-- PayPal Content -->
                        <div x-show="paymentMethod === 'paypal'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <form action="{{ route('payments.process.paypal', $booking) }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="text-center py-4">
                                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <img src="{{ asset('images/paypal.svg') }}" alt="PayPal" class="h-8 w-auto">
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Thanh toán với PayPal</h3>
                                    <p class="text-gray-500 mt-2 max-w-md mx-auto">Bạn sẽ được chuyển hướng đến trang thanh toán an toàn của PayPal để hoàn tất giao dịch.</p>
                                </div>

                                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        Tỷ giá chuyển đổi sẽ được tính theo quy định của PayPal tại thời điểm thanh toán.
                                    </p>
                                </div>

                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-[#0070BA] hover:bg-[#003087] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                    Thanh toán ngay
                                </button>
                            </form>
                        </div>

                        <!-- VNPay Content -->
                        <div x-show="paymentMethod === 'vnpay'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            <form action="{{ route('payments.process.vnpay', $booking) }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="text-center py-4">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                        <img src="{{ asset('images/vnpay.png') }}" alt="VNPay" class="h-8 w-auto">
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Thanh toán qua VNPay</h3>
                                    <p class="text-gray-500 mt-2 max-w-md mx-auto">Quét mã QR hoặc sử dụng thẻ ATM nội địa/quốc tế thông qua cổng thanh toán VNPay.</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Hỗ trợ tất cả ngân hàng tại Việt Nam
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Xác nhận thanh toán tức thì
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Bảo mật chuẩn quốc tế
                                        </li>
                                    </ul>
                                </div>

                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                    Tiếp tục tới VNPay
                                </button>
                            </form>
                        </div>

                        <!-- Mock Card Content -->
                        <div x-show="paymentMethod === 'mock'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            <form action="{{ route('payments.process.mock', $booking) }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-bold text-gray-900">Thông tin thẻ</h3>
                                    <div class="flex gap-2">
                                        <img src="{{ asset('images/visa.png') }}" class="h-6 w-auto opacity-70">
                                    </div>
                                </div>

                                <div class="space-y-5">
                                    <!-- Card Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Số thẻ</label>
                                        <div class="relative">
                                            <input type="text" name="card_number" 
                                                value="{{ old('card_number', '4781 1111 3131 8323') }}"
                                                placeholder="0000 0000 0000 0000"
                                                class="block w-full pl-12 pr-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                oninput="formatCardNumber(this)"
                                                maxlength="19">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('card_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Name on Card -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tên chủ thẻ</label>
                                        <input type="text" name="card_name" 
                                            value="{{ old('card_name', strtoupper(auth()->user()->name)) }}"
                                            placeholder="NGUYEN VAN A"
                                            class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-colors uppercase">
                                        @error('card_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Expiry -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Ngày hết hạn</label>
                                            <input type="text" name="expiry_date" 
                                                value="{{ old('expiry_date', '12/25') }}"
                                                placeholder="MM/YY"
                                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                oninput="formatExpiry(this)"
                                                maxlength="5">
                                            @error('expiry_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- CVV -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CVV / CVC</label>
                                            <div class="relative">
                                                <input type="text" name="cvv" 
                                                    value="{{ old('cvv', '123') }}"
                                                    placeholder="123"
                                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                    maxlength="3">
                                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none group">
                                                    <svg class="w-5 h-5 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('cvv')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:-translate-y-0.5">
                                    Thanh toán {{ number_format($booking->total_amount) }} đ
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Security Note -->
                    <div class="flex items-center justify-center gap-4 text-sm text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Bảo mật SSL</span>
                        </div>
                        <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Giao dịch được mã hóa</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-5">
                    <div class="sticky top-24 space-y-6">
                        <!-- Summary Card -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                            <!-- Header -->
                            <div class="bg-gray-900 p-6 text-white">
                                <h3 class="text-lg font-bold">Thông tin đơn hàng</h3>
                                <div class="mt-2 flex items-center justify-between text-sm text-gray-300">
                                    <span>Mã đặt chỗ</span>
                                    <span class="font-mono font-bold text-white bg-gray-800 px-2 py-1 rounded">{{ $booking->booking_code }}</span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-6">
                                <!-- Tour Info -->
                                <div class="flex gap-4 mb-6">
                                    <div class="w-20 h-20 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                                        @if($booking->tour->getThumbnailUrl())
                                            <img src="{{ $booking->tour->getThumbnailUrl() }}" alt="{{ $booking->tour->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 line-clamp-2">{{ $booking->tour->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ $booking->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="space-y-3 text-sm border-t border-gray-100 pt-4">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Khách hàng</span>
                                        <span class="font-medium text-gray-900">{{ $booking->name }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Email</span>
                                        <span class="font-medium text-gray-900">{{ $booking->email }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Số lượng khách</span>
                                        <span class="font-medium text-gray-900">{{ $booking->total_people }} người</span>
                                    </div>
                                </div>

                                <!-- Price Calculation -->
                                <div class="mt-6 pt-4 border-t border-dashed border-gray-200 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Đơn giá</span>
                                        <span class="font-medium text-gray-900">{{ number_format($booking->total_amount / $booking->total_people) }} đ</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-3">
                                        <span class="font-bold text-gray-900 text-lg">Tổng cộng</span>
                                        <span class="font-bold text-2xl text-indigo-600">{{ number_format($booking->total_amount) }} đ</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Footer Action -->
                            <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
                                <a href="{{ route('bookings.show', $booking) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                    Xem lại chi tiết đặt chỗ
                                </a>
                            </div>
                        </div>

                        <!-- Help Box -->
                        <div class="bg-indigo-50 rounded-xl p-5 border border-indigo-100">
                            <h4 class="font-bold text-indigo-900 mb-2">Cần hỗ trợ?</h4>
                            <p class="text-sm text-indigo-700 mb-3">Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giúp đỡ bạn 24/7.</p>
                            <div class="flex items-center gap-4 text-sm font-medium text-indigo-800">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    1900-xxxx
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    support@travelgo.vn
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function formatCardNumber(input) {
            let value = input.value.replace(/\s/g, '').replace(/\D/g, '');
            let formatted = value.match(/.{1,4}/g);
            input.value = formatted ? formatted.join(' ') : '';
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
        }
    </script>
    @endpush
</x-client-layout>
