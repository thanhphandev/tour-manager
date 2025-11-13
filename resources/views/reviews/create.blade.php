<x-client-layout>
    <section class="py-12 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <span class="font-semibold text-gray-900">Viết đánh giá</span>
            </nav>

            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-start gap-6">
                    @if($tour->primaryImage)
                        <img src="{{ Storage::url($tour->primaryImage->image_path) }}" 
                             alt="{{ $tour->name }}"
                             class="w-32 h-32 object-cover rounded-xl shadow-lg">
                    @else
                        <div class="w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl"></div>
                    @endif
                    <div class="flex-1">
                        <h1 class="text-3xl font-black text-gray-900 mb-3">Đánh Giá Tour</h1>
                        <h2 class="text-xl text-gray-700 mb-2">{{ $tour->name }}</h2>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $tour->destination->name }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $tour->duration_days }} ngày
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form action="{{ route('reviews.store', $tour) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Rating -->
                    <div x-data="{ rating: 0, hoverRating: 0 }">
                        <label class="block text-lg font-bold text-gray-900 mb-3">
                            Đánh giá chung <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                        @click="rating = {{ $i }}"
                                        @mouseenter="hoverRating = {{ $i }}"
                                        @mouseleave="hoverRating = 0"
                                        class="focus:outline-none transform hover:scale-110 transition-transform">
                                    <svg class="w-12 h-12 transition-colors"
                                         :class="(hoverRating >= {{ $i }} || (hoverRating === 0 && rating >= {{ $i }})) ? 'text-yellow-500' : 'text-gray-300'"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-600" x-show="rating > 0" x-cloak>
                            <span x-show="rating === 1">Rất tệ</span>
                            <span x-show="rating === 2">Tệ</span>
                            <span x-show="rating === 3">Bình thường</span>
                            <span x-show="rating === 4">Tốt</span>
                            <span x-show="rating === 5">Xuất sắc</span>
                        </p>
                        <input type="hidden" name="rating" :value="rating" required>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-lg font-bold text-gray-900 mb-3">
                            Tiêu đề đánh giá <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               placeholder="Tóm tắt trải nghiệm của bạn trong một câu..."
                               class="w-full px-4 py-3 rounded-xl border-2 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all @error('title') border-red-500 @enderror"
                               required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-lg font-bold text-gray-900 mb-3">
                            Nội dung đánh giá <span class="text-red-500">*</span>
                        </label>
                        <textarea id="comment" 
                                  name="comment" 
                                  rows="6"
                                  placeholder="Chia sẻ chi tiết về trải nghiệm của bạn... (tối thiểu 10 ký tự)"
                                  class="w-full px-4 py-3 rounded-xl border-2 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all @error('comment') border-red-500 @enderror"
                                  required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600">
                            💡 Một đánh giá chi tiết giúp khách hàng khác có thêm thông tin hữu ích
                        </p>
                    </div>

                    <!-- Notice -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-bold mb-1">Lưu ý quan trọng:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Đánh giá của bạn sẽ được admin xem xét trước khi hiển thị công khai</li>
                                    <li>Vui lòng chia sẻ trải nghiệm trung thực và có ích</li>
                                    <li>Tránh ngôn từ xúc phạm hoặc không phù hợp</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Gửi Đánh Giá
                        </button>
                        <a href="{{ route('tours.show', $tour) }}"
                           class="px-8 py-4 rounded-xl font-bold border-2 border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush
</x-client-layout>
