<x-client-layout>
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4">
                    Tất Cả Tours Du Lịch
                </h1>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Khám phá {{ $tours->total() }} tour du lịch tuyệt vời trên khắp thế giới
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Bộ Lọc</h3>
                            @if(request()->hasAny(['destination', 'price', 'duration', 'search']))
                                <a href="{{ route('tours.index') }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                                    Xóa hết
                                </a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('tours.index') }}" class="space-y-6" x-data="{ open: true }">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Tìm kiếm
                                </label>
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Tên tour, địa điểm, mô tả..."
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <!-- Destination Filter (using slug) -->
                            <div class="border-t border-gray-200 pt-6">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    Điểm đến
                                </label>
                                <select name="destination" <!-- Use 'destination' instead of 'destination_id' -->
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                    <option value="">Tất cả điểm đến</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->slug }}" <!-- Use slug -->
                                                {{ request('destination') == $destination->slug ? 'selected' : '' }}>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Filter -->
                            <div class="border-t border-gray-200 pt-6">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Mức giá
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="" {{ !request('price') ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Tất cả mức giá</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="under-5m" {{ request('price') == 'under-5m' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Dưới 5 triệu</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="5m-10m" {{ request('price') == '5m-10m' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">5 - 10 triệu</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="10m-20m" {{ request('price') == '10m-20m' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">10 - 20 triệu</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="over-20m" {{ request('price') == 'over-20m' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Trên 20 triệu</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Duration Filter -->
                            <div class="border-t border-gray-200 pt-6">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Thời gian
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="duration" value="" {{ !request('duration') ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Tất cả thời gian</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="duration" value="1-3" {{ request('duration') == '1-3' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">1-3 ngày</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="duration" value="4-7" {{ request('duration') == '4-7' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">4-7 ngày</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="duration" value="8-14" {{ request('duration') == '8-14' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">8-14 ngày</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="duration" value="15+" {{ request('duration') == '15+' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">15+ ngày</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Apply Button -->
                            <div class="border-t border-gray-200 pt-6">
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Áp dụng
                                </button>
                            </div>
                        </form>
                    </div>
                </aside>

                <!-- Tours Grid -->
                <div class="lg:col-span-3 mt-8 lg:mt-0">
                    <!-- Results Info -->
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-gray-700">
                            Tìm thấy <span class="font-bold text-indigo-600">{{ $tours->total() }}</span> tours
                        </p>
                        <!-- Sort Options could go here -->
                    </div>

                    @if($tours->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($tours as $tour)
                                <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                                    <!-- Image -->
                                    <div class="relative h-56 overflow-hidden">
                                        @if($tour->getThumbnailUrl())
                                            <img src="{{ $tour->getThumbnailUrl() }}"
                                                 alt="{{ $tour->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Destination Badge -->
                                        <div class="absolute top-4 left-4 bg-black/50 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-sm font-semibold">
                                            📍 {{ $tour->destination->name }}
                                        </div>

                                        <!-- Featured Badge -->
                                        @if($tour->featured)
                                            <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1.5 rounded-full text-xs font-bold">
                                                ⭐ Nổi bật
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                                            {{ $tour->name }}
                                        </h3>

                                        <!-- Tour Info -->
                                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $tour->duration_days}} ngày
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
                                                <div class="text-xl font-black text-indigo-600">
                                                    {{ number_format($tour->price_adult, 0, ',', '.') }}đ
                                                </div>
                                            </div>
                                            <a href="{{ route('tours.show', $tour) }}"
                                               class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
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
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Không tìm thấy tour nào</h3>
                            <p class="text-gray-600 mb-6">Vui lòng thử lại với bộ lọc khác</p>
                            <a href="{{ route('tours.index') }}"
                               class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Xóa bộ lọc
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-client-layout>