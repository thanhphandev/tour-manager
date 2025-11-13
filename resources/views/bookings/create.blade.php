<x-client-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('tours.show', $tour) }}" class="hover:text-indigo-600 transition">{{ $tour->name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-indigo-600 font-semibold">Đặt tour</span>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Đặt tour</h1>
                <p class="text-gray-600">Vui lòng điền đầy đủ thông tin để hoàn tất đặt tour</p>
            </div>

            <form action="{{ route('bookings.store', $tour) }}" method="POST" x-data="bookingForm()">
                @csrf

                <div class="grid lg:grid-cols-3 gap-6">
                    <!-- Main Form - 2 columns -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Progress Steps -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-bold shadow-lg">
                                        1
                                    </div>
                                    <div class="flex-1 h-1 bg-gradient-to-r from-indigo-600 to-purple-600 mx-2"></div>
                                </div>
                                <div class="flex-1 flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 bg-gray-200 text-gray-500 rounded-full font-bold">
                                        2
                                    </div>
                                    <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                                </div>
                                <div class="flex items-center justify-center w-10 h-10 bg-gray-200 text-gray-500 rounded-full font-bold">
                                    3
                                </div>
                            </div>
                            <div class="flex justify-between mt-3 text-sm font-semibold">
                                <span class="text-indigo-600">Thông tin đặt tour</span>
                                <span class="text-gray-500">Thanh toán</span>
                                <span class="text-gray-500">Hoàn tất</span>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Thông tin khách hàng
                            </h2>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Họ và tên <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="name" 
                                               value="{{ old('name', auth()->user()->name ?? '') }}"
                                               required
                                               placeholder="Nguyễn Văn A"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    </div>
                                    @error('name')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="email" 
                                               name="email" 
                                               value="{{ old('email', auth()->user()->email ?? '') }}"
                                               required
                                               placeholder="email@example.com"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    </div>
                                    @error('email')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <input type="tel" 
                                               name="phone" 
                                               value="{{ old('phone') }}"
                                               required
                                               placeholder="0912345678"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    </div>
                                    @error('phone')
                                        <p class="text-red-600 text-sm mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Participant Details -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Số lượng khách
                            </h2>
                            
                            <div class="grid md:grid-cols-3 gap-6">
                                <!-- Adults -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Người lớn <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <button type="button" 
                                                @click="adults > 1 && adults--"
                                                class="w-10 h-10 rounded-lg bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               name="adults" 
                                               x-model="adults"
                                               min="1" 
                                               max="{{ $tour->max_people }}"
                                               required
                                               class="flex-1 text-center px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-semibold text-lg">
                                        <button type="button" 
                                                @click="totalPeople < {{ $tour->max_people }} && adults++"
                                                class="w-10 h-10 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">≥ 12 tuổi</p>
                                </div>

                                <!-- Children -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Trẻ em
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <button type="button" 
                                                @click="children > 0 && children--"
                                                class="w-10 h-10 rounded-lg bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               name="children" 
                                               x-model="children"
                                               min="0" 
                                               max="{{ $tour->max_people }}"
                                               class="flex-1 text-center px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-semibold text-lg">
                                        <button type="button" 
                                                @click="totalPeople < {{ $tour->max_people }} && children++"
                                                class="w-10 h-10 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">2-11 tuổi</p>
                                </div>

                                <!-- Infants -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Em bé
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <button type="button" 
                                                @click="infants > 0 && infants--"
                                                class="w-10 h-10 rounded-lg bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               name="infants" 
                                               x-model="infants"
                                               min="0" 
                                               max="{{ $tour->max_people }}"
                                               class="flex-1 text-center px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-semibold text-lg">
                                        <button type="button" 
                                                @click="totalPeople < {{ $tour->max_people }} && infants++"
                                                class="w-10 h-10 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">< 2 tuổi</p>
                                </div>
                            </div>

                            <!-- Total People Display -->
                            <div class="mt-4 p-4 bg-indigo-50 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-semibold">Tổng số khách:</span>
                                    <span class="text-2xl font-bold text-indigo-600" x-text="totalPeople + ' người'">1 người</span>
                                </div>
                                <input type="hidden" name="total_people" x-model="totalPeople">
                            </div>
                        </div>

                        <!-- Special Request -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                Yêu cầu đặc biệt
                            </h2>
                            <textarea name="special_requests" 
                                      rows="5"
                                      placeholder="Nhập yêu cầu đặc biệt của bạn (ví dụ: ăn chay, dị ứng thực phẩm, phòng tách biệt, ...)"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ old('special_request') }}</textarea>
                        </div>

                        <!-- Terms -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" 
                                       name="terms" 
                                       required
                                       class="mt-1 w-5 h-5 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300">
                                <span class="ml-3 text-gray-700">
                                    Tôi đã đọc và đồng ý với <a href="#" class="text-indigo-600 hover:underline font-semibold">Điều khoản & Điều kiện</a> cũng như <a href="#" class="text-indigo-600 hover:underline font-semibold">Chính sách hủy tour</a> của TravelGo
                                </span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <a href="{{ route('tours.show', $tour) }}" 
                               class="flex-1 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-800 px-8 py-4 rounded-xl font-bold text-center transition-all hover:shadow-lg">
                                ← Quay lại
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-bold shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                                Tiếp tục thanh toán →
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar - Order Summary - 1 column -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Tóm tắt đặt tour</h3>
                            
                            <!-- Tour Image -->
                            <img src="{{ $tour->thumbnail ? asset('storage/' . $tour->thumbnail) : 'https://via.placeholder.com/400x300' }}" 
                                 alt="{{ $tour->title }}" 
                                 class="w-full h-48 object-cover rounded-xl mb-4">
                            
                            <!-- Tour Info -->
                            <h4 class="font-bold text-gray-900 mb-3">{{ $tour->title }}</h4>
                            
                            <div class="space-y-3 text-sm mb-6">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span>{{ $tour->destination->name }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $tour->duration_days }} ngày {{ $tour->duration_days - 1 }} đêm</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-gray-600" x-show="adults > 0">
                                    <span>👨 Người lớn (×<span x-text="adults"></span>)</span>
                                    <span class="font-semibold" x-text="formatPrice({{ $tour->price_adult }} * adults)">{{ number_format($tour->price_adult) }} VNĐ</span>
                                </div>
                                <div class="flex justify-between text-gray-600" x-show="children > 0">
                                    <span>👦 Trẻ em (×<span x-text="children"></span>)</span>
                                    <span class="font-semibold" x-text="formatPrice({{ $tour->price_child }} * children)">{{ number_format($tour->price_child) }} VNĐ</span>
                                </div>
                                <div class="flex justify-between text-gray-600" x-show="infants > 0">
                                    <span>👶 Em bé (×<span x-text="infants"></span>)</span>
                                    <span class="font-semibold" x-text="formatPrice({{ $tour->price_infant }} * infants)">{{ number_format($tour->price_infant) }} VNĐ</span>
                                </div>
                                <div class="flex justify-between text-gray-600 font-medium">
                                    <span>Tổng số người</span>
                                    <span x-text="totalPeople + ' người'">1 người</span>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="border-t-2 border-gray-300 mt-4 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                                    <span class="text-2xl font-bold text-indigo-600" x-text="formatPrice(({{ $tour->price_adult }} * adults) + ({{ $tour->price_child }} * children) + ({{ $tour->price_infant }} * infants))">
                                        {{ number_format($tour->price_adult) }} VNĐ
                                    </span>
                                </div>
                            </div>

                            <!-- Security Badge -->
                            <div class="mt-6 p-3 bg-green-50 rounded-lg flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-sm text-green-800 font-semibold">Thanh toán bảo mật 100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                adults: 1,
                children: 0,
                infants: 0,
                get totalPeople() {
                    return parseInt(this.adults) + parseInt(this.children) + parseInt(this.infants);
                },
                formatPrice(price) {
                    return new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
                }
            }
        }
    </script>
    @endpush
</x-client-layout>
