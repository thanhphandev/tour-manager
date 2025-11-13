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
                                    <button onclick="showPaymentMethod('mock')" id="tab-mock"
                                            class="payment-tab border-b-2 border-indigo-500 py-4 px-1 text-center text-sm font-medium text-indigo-600 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Thẻ Tín Dụng (Mock)
                                        </div>
                                    </button>
                                    <button onclick="showPaymentMethod('vnpay')" id="tab-vnpay"
                                            class="payment-tab border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            VNPay
                                            <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Sắp ra mắt</span>
                                        </div>
                                    </button>
                                </nav>
                            </div>
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

                        <!-- VNPay Payment (Coming Soon) -->
                        <div id="payment-vnpay" class="payment-content hidden">
                            <div class="text-center py-12">
                                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">VNPay Đang Phát Triển</h3>
                                <p class="text-gray-600 mb-6">Tính năng thanh toán qua VNPay sẽ sớm ra mắt. Hiện tại vui lòng sử dụng phương thức Mock Payment.</p>
                                <button onclick="showPaymentMethod('mock')" 
                                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Chuyển sang Mock Payment
                                </button>
                            </div>
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
