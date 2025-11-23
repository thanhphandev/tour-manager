<x-client-layout>
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-purple-600 to-pink-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4">
                    Khám Phá Điểm Đến
                </h1>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Những địa điểm tuyệt vời đang chờ bạn khám phá
                </p>
            </div>
        </div>
    </section>

    <!-- Destinations Grid -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($destinations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($destinations as $destination)
                        <article class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if($destination->image)
                                    <img src="{{ App\Facades\ImageHelper::getUrl($destination->image) }}" 
                                         alt="{{ $destination->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                                
                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <h3 class="text-2xl font-black text-white mb-2">
                                        {{ $destination->name }}
                                    </h3>
                                    @if($destination->tours_count)
                                        <p class="text-white/90 text-sm font-semibold">
                                            {{ $destination->tours_count }} tours có sẵn
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="p-6">
                                @if($destination->description)
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ strip_tags($destination->description_html ?? $destination->description) }}
                                    </p>
                                @endif

                                <a href="{{ route('destinations.show', $destination) }}" 
                                   class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 w-full justify-center">
                                    Khám Phá Ngay
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16 bg-white rounded-2xl">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Chưa có điểm đến nào</h3>
                    <p class="text-gray-600">Vui lòng quay lại sau</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Travel Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
                    Tại Sao Nên Du Lịch?
                </h2>
                <p class="text-xl text-gray-600">
                    Mở rộng tầm nhìn, trải nghiệm văn hóa mới
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Khám Phá Thế Giới</h3>
                    <p class="text-gray-600">Trải nghiệm những nền văn hóa độc đáo</p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tạo Kỷ Niệm</h3>
                    <p class="text-gray-600">Những khoảnh khắc đáng nhớ suốt đời</p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-orange-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Thư Giãn</h3>
                    <p class="text-gray-600">Nghỉ ngơi và nạp lại năng lượng</p>
                </div>
            </div>
        </div>
    </section>
</x-client-layout>
