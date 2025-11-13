<x-client-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Thanh Toán Đặt Tour</h1>
                <p class="text-gray-600">Hoàn tất thanh toán để xác nhận đặt chỗ</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <!-- Payment Methods Tabs -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Chọn Phương Thức Thanh Toán</h2>
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="showPaymentMethod('paypal')" id="tab-paypal"
                                            class="payment-tab border-b-2 border-indigo-500 py-4 px-1 text-center text-sm font-medium text-indigo-600 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.028.15a.804.804 0 01-.793.679H8.136c-.379 0-.664-.31-.575-.672l.99-6.28c.1-.645.652-1.1 1.306-1.1h1.095c3.476 0 6.176-1.413 6.964-5.502.265-1.374.172-2.52-.31-3.32a2.67 2.67 0 00-.844-.932C18.724 5.452 20.716 6.402 20.067 8.478z"/>
                                                <path d="M15.762 5.8c-.263-.092-.54-.17-.83-.233-.736-.162-1.554-.244-2.446-.244h-4.61a.975.975 0 00-.963.823l-1.405 8.904a.585.585 0 00.577.678h3.216l.807-5.122-.025.16a.975.975 0 01.963-.824h2.01c3.947 0 7.04-1.604 7.943-6.24.024-.125.043-.247.06-.366-.23-.127-.477-.238-.74-.327-.22-.075-.45-.14-.688-.194z"/>
                                            </svg>
                                            PayPal
                                        </div>
                                    </button>
                                    <button onclick="showPaymentMethod('vnpay')" id="tab-vnpay"
                                            class="payment-tab border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            VNPay
                                        </div>
                                    </button>
                                    <button onclick="showPaymentMethod('mock')" id="tab-mock"
                                            class="payment-tab border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Thẻ Tín Dụng (Demo)
                                        </div>
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <!-- PayPal Payment -->
                        <div id="payment-paypal" class="payment-content">
                            <form action="{{ route('payments.process.paypal', $booking) }}" method="POST" class="space-y-6">
                                @csrf

                                <!-- PayPal Logo & Info -->
                                <div class="text-center py-6 border-b border-gray-200">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.028.15a.804.804 0 01-.793.679H8.136c-.379 0-.664-.31-.575-.672l.99-6.28c.1-.645.652-1.1 1.306-1.1h1.095c3.476 0 6.176-1.413 6.964-5.502.265-1.374.172-2.52-.31-3.32a2.67 2.67 0 00-.844-.932C18.724 5.452 20.716 6.402 20.067 8.478z"/>
                                            <path d="M15.762 5.8c-.263-.092-.54-.17-.83-.233-.736-.162-1.554-.244-2.446-.244h-4.61a.975.975 0 00-.963.823l-1.405 8.904a.585.585 0 00.577.678h3.216l.807-5.122-.025.16a.975.975 0 01.963-.824h2.01c3.947 0 7.04-1.604 7.943-6.24.024-.125.043-.247.06-.366-.23-.127-.477-.238-.74-.327-.22-.075-.45-.14-.688-.194z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Thanh Toán qua PayPal</h3>
                                    <p class="text-gray-600 text-sm">An toàn, nhanh chóng, được bảo vệ bởi PayPal</p>
                                </div>

                                <!-- Payment Info -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-medium">Số tiền thanh toán:</span>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold text-indigo-600">{{ number_format($booking->total_amount) }} đ</span>
                                            <p class="text-sm text-gray-600 mt-1">≈ ${{ number_format($booking->total_amount / 24000, 2) }} USD</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Mã đặt chỗ:</span>
                                        <span class="font-semibold text-gray-900">{{ $booking->booking_code }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Tour:</span>
                                        <span class="font-semibold text-gray-900 text-right">{{ Str::limit($booking->tour->name, 30) }}</span>
                                    </div>
                                </div>

                                <!-- PayPal Benefits -->
                                <div class="bg-white border-2 border-blue-100 rounded-xl p-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Ưu điểm PayPal</h4>
                                    <ul class="space-y-2 text-sm text-gray-700">
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Bảo vệ người mua - Hoàn tiền 100% nếu có vấn đề</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Thanh toán quốc tế - Hỗ trợ 200+ quốc gia</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Không chia sẻ thông tin tài chính với người bán</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Mã hóa cấp độ ngân hàng và giám sát gian lận 24/7</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- How it works -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Cách thức hoạt động</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                            <p class="ml-3 text-sm text-gray-700">Nhấn nút "Thanh toán với PayPal" bên dưới</p>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                            <p class="ml-3 text-sm text-gray-700">Đăng nhập PayPal hoặc thanh toán bằng thẻ</p>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                            <p class="ml-3 text-sm text-gray-700">Xác nhận thanh toán và quay lại trang web</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Important Notice -->
                                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-amber-800">Lưu ý quan trọng</p>
                                            <ul class="text-xs text-amber-700 mt-2 space-y-1 list-disc list-inside">
                                                <li>Bạn sẽ được chuyển đến trang thanh toán PayPal an toàn</li>
                                                <li>Không cần tài khoản PayPal - có thể thanh toán bằng thẻ tín dụng/ghi nợ</li>
                                                <li>Vui lòng không đóng trình duyệt cho đến khi hoàn tất thanh toán</li>
                                                <li>Tỷ giá chuyển đổi VND/USD có thể thay đổi theo thời gian thực</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Info -->
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-green-800">Thanh toán an toàn & bảo mật tuyệt đối</p>
                                            <p class="text-xs text-green-700 mt-1">Được bảo vệ bởi PayPal với mã hóa SSL 256-bit và công nghệ chống gian lận tiên tiến.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.028.15a.804.804 0 01-.793.679H8.136c-.379 0-.664-.31-.575-.672l.99-6.28c.1-.645.652-1.1 1.306-1.1h1.095c3.476 0 6.176-1.413 6.964-5.502.265-1.374.172-2.52-.31-3.32a2.67 2.67 0 00-.844-.932C18.724 5.452 20.716 6.402 20.067 8.478z"/>
                                    </svg>
                                    Thanh Toán với PayPal
                                </button>

                                <div class="text-center space-y-2">
                                    <button type="button" onclick="showPaymentMethod('vnpay')" 
                                            class="text-sm text-gray-600 hover:text-gray-800 underline">
                                        Hoặc sử dụng VNPay (VND)
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Mock Payment Form -->
                        <div id="payment-mock" class="payment-content">
                            <form action="{{ route('payments.process.mock', $booking) }}" method="POST" class="space-y-6">
                                @csrf

                                <!-- Card Number -->
                                <div>
                                    <label for="card_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Số Thẻ <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="card_number" 
                                               id="card_number" 
                                               value="{{ old('card_number', '4111 1111 1111 1111') }}"
                                               placeholder="1234 5678 9012 3456"
                                               maxlength="19"
                                               class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                               oninput="formatCardNumber(this)">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Sử dụng số thẻ test: 4111 1111 1111 1111</p>
                                    @error('card_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Card Name -->
                                <div>
                                    <label for="card_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tên Trên Thẻ <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="card_name" 
                                           id="card_name" 
                                           value="{{ old('card_name', strtoupper(auth()->user()->name)) }}"
                                           placeholder="NGUYEN VAN A"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition uppercase">
                                    @error('card_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Expiry Date -->
                                    <div>
                                        <label for="expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Ngày Hết Hạn <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="expiry_date" 
                                               id="expiry_date" 
                                               value="{{ old('expiry_date', '12/25') }}"
                                               placeholder="MM/YY"
                                               maxlength="5"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                               oninput="formatExpiry(this)">
                                        @error('expiry_date')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- CVV -->
                                    <div>
                                        <label for="cvv" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Mã CVV <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="cvv" 
                                               id="cvv" 
                                               value="{{ old('cvv', '123') }}"
                                               placeholder="123"
                                               maxlength="3"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        @error('cvv')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Security Notice -->
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-800">Thanh toán an toàn & bảo mật</p>
                                            <p class="text-xs text-blue-700 mt-1">Đây là giao diện demo. Dữ liệu thẻ sẽ không được lưu trữ. Tích hợp VNPay thực tế sẽ ra mắt sau.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-base font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-[1.02]">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Thanh Toán {{ number_format($booking->total_amount) }} đ
                                </button>
                            </form>
                        </div>

                        <!-- VNPay Payment -->
                        <div id="payment-vnpay" class="payment-content hidden">
                            <form action="{{ route('payments.process.vnpay', $booking) }}" method="POST" class="space-y-6">
                                @csrf

                                <!-- VNPay Logo & Info -->
                                <div class="text-center py-6 border-b border-gray-200">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Thanh Toán qua VNPay</h3>
                                    <p class="text-gray-600 text-sm">Bạn sẽ được chuyển đến cổng thanh toán VNPay để hoàn tất giao dịch</p>
                                </div>

                                <!-- Payment Info -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-medium">Số tiền thanh toán:</span>
                                        <span class="text-2xl font-bold text-indigo-600">{{ number_format($booking->total_amount) }} đ</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Mã đặt chỗ:</span>
                                        <span class="font-semibold text-gray-900">{{ $booking->booking_code }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Tour:</span>
                                        <span class="font-semibold text-gray-900 text-right">{{ Str::limit($booking->tour->name, 30) }}</span>
                                    </div>
                                </div>

                                <!-- Supported Banks -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Ngân hàng hỗ trợ</h4>
                                    <div class="grid grid-cols-4 gap-3">
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">Vietcombank</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">BIDV</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">Techcombank</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">ACB</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">VietinBank</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">Agribank</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">MB Bank</div>
                                        </div>
                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 transition">
                                            <div class="text-xs font-medium text-gray-700">+ Khác</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Important Notice -->
                                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-amber-800">Lưu ý quan trọng</p>
                                            <ul class="text-xs text-amber-700 mt-2 space-y-1 list-disc list-inside">
                                                <li>Bạn sẽ được chuyển đến trang thanh toán VNPay</li>
                                                <li>Vui lòng không đóng trình duyệt cho đến khi hoàn tất thanh toán</li>
                                                <li>Sau khi thanh toán, bạn sẽ được chuyển về trang xác nhận</li>
                                                <li>Nếu có vấn đề, vui lòng liên hệ bộ phận hỗ trợ</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Info -->
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-800">Thanh toán an toàn & bảo mật</p>
                                            <p class="text-xs text-green-700 mt-1">Giao dịch được mã hóa SSL 256-bit. Thông tin của bạn hoàn toàn an toàn.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Thanh Toán qua VNPay {{ number_format($booking->total_amount) }} đ
                                </button>

                                <div class="text-center">
                                    <button type="button" onclick="showPaymentMethod('paypal')" 
                                            class="text-sm text-gray-600 hover:text-gray-800 underline">
                                        Hoặc sử dụng PayPal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Thông Tin Đặt Chỗ</h3>

                        <!-- Booking Code -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Mã đặt chỗ</p>
                            <p class="text-lg font-bold text-indigo-600">{{ $booking->booking_code }}</p>
                        </div>

                        <!-- Tour Info -->
                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tour</p>
                                <p class="font-semibold text-gray-900">{{ $booking->tour->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Khách hàng</p>
                                <p class="font-semibold text-gray-900">{{ $booking->name }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->email }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Số người</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->total_people }} người</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Ngày đặt</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Giá tour</span>
                                <span class="font-medium text-gray-900">{{ number_format($booking->total_amount / $booking->total_people) }} đ (trung bình)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Số người</span>
                                <span class="font-medium text-gray-900">×{{ $booking->total_people }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between">
                                    <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                                    <span class="text-2xl font-bold text-indigo-600">{{ number_format($booking->total_amount) }} đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Badges -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Bảo mật SSL
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Thanh toán an toàn
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('bookings.show', $booking) }}" 
                   class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay lại thông tin đặt chỗ
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Payment method switching
        function showPaymentMethod(method) {
            // Hide all content
            document.querySelectorAll('.payment-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Remove active from all tabs
            document.querySelectorAll('.payment-tab').forEach(el => {
                el.classList.remove('border-indigo-500', 'text-indigo-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected content
            document.getElementById('payment-' + method).classList.remove('hidden');
            
            // Add active to selected tab
            const tab = document.getElementById('tab-' + method);
            tab.classList.add('border-indigo-500', 'text-indigo-600');
            tab.classList.remove('border-transparent', 'text-gray-500');
        }

        // Format card number with spaces
        function formatCardNumber(input) {
            let value = input.value.replace(/\s/g, '').replace(/\D/g, '');
            let formatted = value.match(/.{1,4}/g);
            input.value = formatted ? formatted.join(' ') : '';
        }

        // Format expiry date MM/YY
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
