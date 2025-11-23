<x-client-layout>
    <section class="py-12 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-black text-gray-900 mb-2">Đánh Giá Của Tôi</h1>
                <p class="text-gray-600">Quản lý tất cả đánh giá bạn đã viết cho các tour đã tham gia</p>
            </div>

            <!-- Reviews List -->
            @if($reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="p-6">
                                <!-- Tour Info -->
                                <div class="flex items-start gap-4 mb-4 pb-4 border-b border-gray-200">
                                    @if($review->tour->getThumbnailUrl())
                                        <img src="{{ $review->tour->getThumbnailUrl() }}" 
                                             alt="{{ $review->tour->name }}"
                                             class="w-20 h-20 object-cover rounded-lg shadow">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg"></div>
                                    @endif
                                    <div class="flex-1">
                                        <a href="{{ route('tours.show', $review->tour) }}" 
                                           class="text-xl font-bold text-gray-900 hover:text-indigo-600 transition">
                                            {{ $review->tour->name }}
                                        </a>
                                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Đánh giá {{ $review->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    @if($review->is_approved)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Đã duyệt
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Chờ duyệt
                                        </span>
                                    @endif
                                </div>

                                <!-- Review Content -->
                                <div>
                                    <!-- Rating -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        @if($review->is_verified)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Đã xác minh
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Title & Comment -->
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $review->title }}</h3>
                                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                </div>

                                <!-- Footer Info -->
                                @if(!$review->is_approved)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-start gap-2 text-sm text-amber-700 bg-amber-50 p-3 rounded-lg">
                                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p>Đánh giá của bạn đang được admin xem xét và sẽ sớm được hiển thị công khai.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="mt-8">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Chưa có đánh giá nào</h3>
                    <p class="text-gray-600 mb-6">Bạn chưa đánh giá tour nào. Sau khi tham gia tour, hãy chia sẻ trải nghiệm của bạn!</p>
                    <a href="{{ route('tours.index') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Khám Phá Tours
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-client-layout>
