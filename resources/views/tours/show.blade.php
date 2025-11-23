<x-client-layout>
    <!-- Hero Section with Image -->
    <section class="relative h-[500px] overflow-hidden">
        @if($tour->getThumbnailUrl())
            <img src="{{ $tour->getThumbnailUrl() }}" 
                 alt="{{ $tour->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        
        <!-- Content Overlay -->
        <div class="absolute bottom-0 left-0 right-0 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-white/80 mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Trang chủ</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('tours.index') }}" class="hover:text-white transition">Tours</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white font-semibold">{{ $tour->name }}</span>
                </nav>

                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div class="flex-1">
                        <!-- Badges -->
                        <div class="flex items-center gap-3 mb-4 group">
                            {{-- Badge Địa điểm --}}
                            <div class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-sm font-semibold border border-white/30 flex items-center gap-1.5 shadow-sm transition-all duration-300 hover:bg-white/30">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-red-100 group-hover:text-red-300 group-hover:animate-bounce transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                
                                <span>{{ $tour->destination->name }}</span>
                            </div>

                            {{-- Badge Nổi bật --}}
                            @if($tour->featured)
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 shadow-lg shadow-orange-500/30 ring-1 ring-white/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 animate-pulse text-yellow-100">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>

                                    <span>Nổi Bật</span>
                                </div>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                            {{ $tour->name }}
                        </h1>

                        <!-- Quick Info -->
                        <div class="flex flex-wrap items-center gap-6 text-white/90">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">{{ $tour->duration_days }} ngày {{ $tour->duration_nights }} đêm</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-semibold">Tối đa {{ $tour->max_people }} người</span>
                            </div>
                            @if($tour->reviews_count > 0)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="font-semibold">{{ number_format($tour->average_rating, 1) }}/5 ({{ $tour->reviews_count }} đánh giá)</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Price Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-2xl lg:min-w-[300px]">
                        <div class="text-sm text-gray-600 mb-1">Giá chỉ từ</div>
                        <div class="text-4xl font-black text-indigo-600 mb-1">
                            {{ number_format($tour->price_adult, 0, ',', '.') }}đ
                        </div>
                        <div class="text-sm text-gray-500 mb-4">/người lớn</div>
                        <a href="#booking" 
                           class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-4 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            Đặt Tour Ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- Left Column - Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Image Gallery -->
                    @if($tour->images->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6" x-data="{ activeImage: 0, lightbox: false }">
                            <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center">
                                <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Thư Viện Ảnh
                            </h2>

                            <!-- Main Image -->
                            <div class="relative h-96 rounded-xl overflow-hidden mb-4 group cursor-pointer" @click="lightbox = true">
                                @foreach($tour->images as $index => $image)
                                    <img x-show="activeImage === {{ $index }}" 
                                         src="{{ App\Facades\ImageHelper::getUrl($image->image_path) }}" 
                                         alt="{{ $image->alt_text ?? $tour->name }}"
                                         class="w-full h-full object-cover">
                                @endforeach
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 rounded-full p-4">
                                        <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbnail Grid -->
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($tour->images as $index => $image)
                                    <div @click="activeImage = {{ $index }}" 
                                         :class="activeImage === {{ $index }} ? 'ring-4 ring-indigo-600' : 'opacity-60 hover:opacity-100'"
                                         class="relative h-24 rounded-lg overflow-hidden cursor-pointer transition-all">
                                        <img src="{{ App\Facades\ImageHelper::getUrl($image->image_path) }}" 
                                             alt="{{ $image->alt_text ?? $tour->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>

                            <!-- Lightbox -->
                            <div x-show="lightbox" 
                                 x-cloak
                                 @click.self="lightbox = false"
                                 class="fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4">
                                <button @click="lightbox = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <img
                                    :src="[
                                        @foreach($tour->images as $image)
                                            '{{ App\Facades\ImageHelper::getUrl($image->image_path) }}',
                                        @endforeach
                                    ][activeImage]"
                                    alt="Tour Image"
                                    class="max-h-full max-w-full object-contain"
                                />

                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Mô Tả Chi Tiết
                        </h2>
                        <div class="markdown-content prose prose-lg max-w-none">
                            {!! $tour->full_description_html !!}
                        </div>
                    </div>

                    <!-- Itinerary -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Lịch Trình Tour
                        </h2>
                        <div class="markdown-content prose prose-lg max-w-none">
                            {!! $tour->itinerary_html !!}
                        </div>
                    </div>

                    <!-- Inclusions / Exclusions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-bold text-green-600 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Bao Gồm
                                </h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Vé máy bay khứ hồi
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Khách sạn {{ $tour->duration - 1 }} đêm
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Bữa ăn theo chương trình
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Hướng dẫn viên chuyên nghiệp
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Bảo hiểm du lịch
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-600 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Không Bao Gồm
                                </h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Chi phí cá nhân
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Hóa đơn VAT
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Tiền tip cho hướng dẫn viên
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Các điểm tham quan ngoài chương trình
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Sidebar -->
                <div class="lg:col-span-1 mt-8 lg:mt-0">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24" id="booking">
                        <h3 class="text-2xl font-black text-gray-900 mb-6">Đặt Tour Này</h3>
                        
                        <!-- Price Breakdown -->
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Giá tour/người:</span>
                                <span class="text-2xl font-black text-indigo-600">{{ number_format($tour->price_adult, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>Thời gian:</span>
                                <span class="font-semibold">{{ $tour->duration_days }} ngày {{ $tour->duration_nights }} đêm</span>
                            </div>
                            <div>

                            </div>
                        </div>

                        <!-- Tour Info -->
                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">Khởi hành hàng ngày</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="font-medium">Tối đa {{ $tour->max_people }} người</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium text-green-600">Miễn phí hủy trong 24h</span>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="space-y-3">
                            <a href="{{ route('bookings.create', $tour) }}" 
                               class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-4 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Đặt Tour Ngay
                            </a>
                            <button class="w-full border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 px-6 py-4 rounded-xl font-bold text-center transition-colors">
                                <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Liên Hệ Tư Vấn
                            </button>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center gap-6 text-sm text-gray-600">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <div class="font-semibold">100% An Toàn</div>
                                </div>
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="font-semibold">Giá Tốt Nhất</div>
                                </div>
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-purple-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <div class="font-semibold">Hỗ Trợ 24/7</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="mt-12">
                <div class="bg-white rounded-2xl shadow-xl p-8"  id="reviews">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-black text-gray-900 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Đánh Giá Từ Khách Hàng
                        </h2>
                        @auth
                            @php
                                $hasBooking = auth()->user()->bookings()
                                    ->where('tour_id', $tour->id)
                                    ->where('status', 'confirmed')
                                    ->exists();
                                $hasEndedTour = $tour->end_date < now();
                                $hasReviewed = auth()->user()->reviews()
                                    ->where('tour_id', $tour->id)
                                    ->exists();
                            @endphp
                            @if($hasBooking && $hasEndedTour && !$hasReviewed)
                                <a href="{{ route('reviews.create', $tour) }}" id="write-review" 
                                   class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    Viết Đánh Giá
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Rating Summary -->
                    <div class="grid md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                        <div class="text-center">
                            <div class="text-6xl font-black text-yellow-500 mb-2">
                                {{ number_format($tour->average_rating, 1) }}
                            </div>
                            <div class="flex items-center justify-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($tour->average_rating) ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-gray-600">Dựa trên {{ $tour->reviews_count }} đánh giá</p>
                        </div>

                        <div class="space-y-2">
                            @php
                                $ratingCounts = $tour->approvedReviews()
                                    ->selectRaw('rating, COUNT(*) as count')
                                    ->groupBy('rating')
                                    ->orderBy('rating', 'desc')
                                    ->pluck('count', 'rating');
                            @endphp
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $ratingCounts->get($i, 0);
                                    $percentage = $tour->reviews_count > 0 ? ($count / $tour->reviews_count * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700 w-12">{{ $i }} sao</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="bg-yellow-500 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Reviews List -->
                    @if($tour->approvedReviews()->count() > 0)
                        <div class="space-y-6">
                            @foreach($tour->approvedReviews()->with('user')->latest()->take(5)->get() as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <div class="flex">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        @if($review->is_verified)
                                                            <span class="inline-flex items-center text-blue-500">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 00-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08zm3.094 8.016a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Đã xác minh
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <h5 class="font-bold text-gray-900 mb-2">{{ $review->title }}</h5>
                                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($tour->approvedReviews()->count() > 5)
                            <div class="text-center mt-8">
                                <button class="text-indigo-600 hover:text-indigo-700 font-bold px-6 py-3 rounded-xl border-2 border-indigo-600 hover:bg-indigo-50 transition-colors">
                                    Xem Thêm {{ $tour->approvedReviews()->count() - 5 }} Đánh Giá
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <p class="text-gray-600 text-lg">Chưa có đánh giá nào cho tour này.</p>
                            <p class="text-gray-500 mt-2">Hãy là người đầu tiên đánh giá!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush
</x-client-layout>
