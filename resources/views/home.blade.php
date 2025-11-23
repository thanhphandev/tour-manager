<x-client-layout>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center bg-white/20 backdrop-blur-md rounded-full px-6 py-2 mb-8 animate-fade-in-down">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></span>
                    <span class="text-white text-sm font-semibold">Khám phá hơn 100+ điểm đến tuyệt vời</span>
                </div>

                <!-- Heading -->
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight animate-fade-in">
                    Khám Phá Thế Giới<br/>
                    <span class="bg-gradient-to-r from-yellow-200 to-orange-300 text-transparent bg-clip-text">
                        Cùng TravelGo
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-12 font-medium animate-fade-in-up">
                    Đặt tour du lịch dễ dàng, trải nghiệm khó quên. Từ biển xanh đến núi cao, chúng tôi đều có.
                </p>

                <!-- Search Bar -->
                <form action="{{ route('tours.index') }}" method="GET" class="max-w-4xl mx-auto animate-fade-in-up animation-delay-200">
                    <div class="bg-white rounded-2xl shadow-2xl p-3 flex flex-col md:flex-row gap-3">
                        <!-- Destination Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <input type="text" name="search" placeholder="Bạn muốn đi đâu?" 
                                       class="w-full pl-12 pr-4 py-4 rounded-xl border-0 focus:ring-2 focus:ring-indigo-500 text-gray-900 placeholder-gray-400 font-medium">
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="flex-1">
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <select name="price" class="w-full pl-12 pr-4 py-4 rounded-xl border-0 focus:ring-2 focus:ring-indigo-500 text-gray-900 font-medium appearance-none bg-white">
                                    <option value="">Giá tour</option>
                                    <option value="under-5m">Dưới 5 triệu</option>
                                    <option value="5m-10m">5 triệu - 10 triệu</option>
                                    <option value="10m-20m">10 triệu - 20 triệu</option>
                                    <option value="over-20m">Trên 20 triệu</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-10 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Tìm Kiếm
                        </button>
                    </div>
                </form>

                <!-- Stats -->
                <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 animate-fade-in-up animation-delay-400">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform">
                        <div class="text-4xl font-black text-white mb-2">100+</div>
                        <div class="text-white/80 font-medium">Tours</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform">
                        <div class="text-4xl font-black text-white mb-2">50+</div>
                        <div class="text-white/80 font-medium">Điểm Đến</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform">
                        <div class="text-4xl font-black text-white mb-2">10K+</div>
                        <div class="text-white/80 font-medium">Khách Hàng</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform">
                        <div class="text-4xl font-black text-white mb-2">4.9★</div>
                        <div class="text-white/80 font-medium">Đánh Giá</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </section>

    <!-- Featured Tours Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block bg-indigo-100 text-indigo-600 rounded-full px-4 py-2 text-sm font-semibold mb-4">
                    NỔI BẬT
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Tours Nổi Bật
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Những tour du lịch được yêu thích nhất với trải nghiệm tuyệt vời
                </p>
            </div>

            <!-- Tours Grid -->
            @if($featuredTours->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredTours as $tour)
                        <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if($tour->getThumbnailUrl())
                                    <img src="{{$tour->getThumbnailUrl() }}" 
                                         alt="{{ $tour->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Featured Badge -->
                                <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                    Nổi Bật
                                </div>

                                <!-- Destination Badge -->
                                <div class="absolute bottom-4 left-4 bg-black/50 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">
                                      {{ $tour->destination->name }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                    {{ $tour->name }}
                                </h3>

                                <!-- Tour Info -->
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $tour->duration_days }} ngày
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $tour->max_people }} người
                                    </span>
                                </div>

                                <!-- Price & CTA -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm text-gray-500">Từ</span>
                                        <div class="text-2xl font-black text-indigo-600">
                                            {{ number_format($tour->price_adult, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                    <a href="{{ route('tours.show', $tour) }}" 
                                       class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        Xem Chi Tiết
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-lg">Chưa có tour nổi bật nào</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Latest Tours Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block bg-purple-100 text-purple-600 rounded-full px-4 py-2 text-sm font-semibold mb-4">
                    MỚI NHẤT
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Tours Mới Nhất
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Cập nhật liên tục các tour du lịch mới với giá ưu đãi
                </p>
            </div>

            <!-- Tours Grid -->
            @if($latestTours->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($latestTours as $tour)
                        <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <!-- Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if($tour->getThumbnailUrl())
                                    <img src="{{ $tour->getThumbnailUrl() }}" 
                                         alt="{{ $tour->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- New Badge -->
                                <div class="absolute top-4 right-4 bg-gradient-to-r from-green-400 to-emerald-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg animate-pulse">
                                    Mới
                                </div>

                                <!-- Destination Badge -->
                                <div class="absolute bottom-4 left-4 bg-black/50 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">
                                      {{ $tour->destination->name }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors line-clamp-2">
                                    {{ $tour->name }}
                                </h3>

                                <!-- Tour Info -->
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $tour->duration_days }} ngày
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $tour->max_people }} người
                                    </span>
                                </div>

                                <!-- Price & CTA -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm text-gray-500">Từ</span>
                                        <div class="text-2xl font-black text-purple-600">
                                            {{ number_format($tour->price_adult, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                    <a href="{{ route('tours.show', $tour) }}" 
                                       class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        Xem Chi Tiết
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- View All Button -->
                <div class="text-center">
                    <a href="{{ route('tours.index') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-10 py-4 rounded-full font-bold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                        Xem Tất Cả Tours
                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-lg">Chưa có tour mới nào</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-gradient-to-br from-indigo-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Tại Sao Chọn TravelGo?
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Chúng tôi cam kết mang đến trải nghiệm du lịch tuyệt vời nhất
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Giá Tốt Nhất</h3>
                    <p class="text-gray-600">Cam kết giá cả cạnh tranh và nhiều ưu đãi hấp dẫn</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">An Toàn</h3>
                    <p class="text-gray-600">Đảm bảo an toàn tuyệt đối trong suốt hành trình</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-500 to-orange-600 rounded-2xl mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Dịch Vụ Tốt</h3>
                    <p class="text-gray-600">Đội ngũ hướng dẫn viên chuyên nghiệp, nhiệt tình</p>
                </div>

                <!-- Feature 4 -->
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-2xl mb-6 transform group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Đặt Tour Nhanh</h3>
                    <p class="text-gray-600">Quy trình đặt tour đơn giản, nhanh chóng chỉ vài phút</p>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
        .animate-fade-in-down {
            animation: fade-in-down 1s ease-out;
        }
        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
        }
        .animation-delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: backwards;
        }
        .animation-delay-400 {
            animation-delay: 0.4s;
            animation-fill-mode: backwards;
        }
    </style>
    @endpush
</x-client-layout>
