<x-client-layout>
    <!-- Hero Section -->
    <section class="relative h-[400px] overflow-hidden">
        @if($destination->image_url)
            <img src="{{ Storage::url($destination->image_url) }}" 
                 alt="{{ $destination->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 right-0 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-white/80 mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Trang chủ</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('destinations.index') }}" class="hover:text-white transition">Điểm đến</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white font-semibold">{{ $destination->name }}</span>
                </nav>

                <h1 class="text-4xl md:text-5xl font-black text-white mb-4">
                    📍 {{ $destination->name }}
                </h1>
                <p class="text-xl text-white/90 max-w-2xl">
                    Khám phá {{ $tours->total() }} tours du lịch tại {{ $destination->name }}
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Description -->
            @if($destination->description)
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-12">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">
                        Về {{ $destination->name }}
                    </h2>
                    <div class="markdown-content prose prose-lg max-w-none">
                        {!! $destination->description_html !!}
                    </div>
                </div>
            @endif

            <!-- Tours Section -->
            <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">
                    Tours Tại {{ $destination->name }}
                </h2>
            </div>

            @if($tours->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($tours as $tour)
                        <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Image -->
                            <div class="relative h-56 overflow-hidden">
                                @if($tour->primaryImage)
                                    <img src="{{ Storage::url($tour->primaryImage->image_path) }}" 
                                         alt="{{ $tour->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                @if($tour->featured)
                                    <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1.5 rounded-full text-xs font-bold">
                                        ⭐ Nổi bật
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-2 min-h-[3.5rem]">
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
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div>
                                        <span class="text-xs text-gray-500 block">Từ</span>
                                        <div class="text-xl font-black text-purple-600">
                                            {{ number_format($tour->price_adult, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                    <a href="{{ route('tours.show', $tour) }}" 
                                       class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        Chi tiết
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $tours->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16 bg-white rounded-2xl">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Chưa có tour nào</h3>
                    <p class="text-gray-600 mb-6">Hiện chưa có tour tại điểm đến này</p>
                    <a href="{{ route('tours.index') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Xem Tất Cả Tours
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-client-layout>
