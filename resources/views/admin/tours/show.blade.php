<x-admin-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chi Tiết Tour</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $tour->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tours.show', $tour) }}" 
                   target="_blank"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview
                </a>
                <a href="{{ route('admin.tours.edit', $tour) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Chỉnh Sửa
                </a>
                <a href="{{ route('admin.tours.images.index', $tour) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                    Quản Lý Hình Ảnh
                </a>
                <a href="{{ route('admin.tours.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                    Quay Lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Thông Tin Cơ Bản</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tên Tour</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tour->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Điểm Đến</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tour->destination->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bảng Giá Tour</dt>
                        <dd class="mt-1 text-sm text-gray-900 space-y-1">
                            <div>👨 Người lớn: <span class="font-semibold text-indigo-600">{{ number_format($tour->price_adult, 0, ',', '.') }} VND</span></div>
                            <div>👦 Trẻ em: <span class="font-semibold text-green-600">{{ number_format($tour->price_child, 0, ',', '.') }} VND</span></div>
                            <div>👶 Em bé: <span class="font-semibold text-blue-600">{{ number_format($tour->price_infant, 0, ',', '.') }} VND</span></div>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Thời Gian</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tour->duration_days }} ngày</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Số Người Tối Đa</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tour->max_people }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Trạng Thái</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $tour->status === 'active' ? 'bg-green-100 text-green-800' : ($tour->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($tour->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tour Nổi Bật</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tour->featured ? 'Có' : 'Không' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tour->slug }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Mô Tả Ngắn</h2>
                <p class="text-gray-700">{{ $tour->short_description }}</p>
            </div>

            <!-- Full Description -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Mô Tả Đầy Đủ</h2>
                <x-markdown-content :markdown="$tour->full_description" />
            </div>

            <!-- Itinerary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Lịch Trình</h2>
                <x-markdown-content :markdown="$tour->itinerary" />
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Thumbnail -->
            @if($tour->thumbnail)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Ảnh Đại Diện</h2>
                <img src="{{ App\Facades\ImageHelper::getUrl($tour->thumbnail) }}" 
                     alt="{{ $tour->name }}" 
                     class="w-full rounded-lg">
            </div>
            @endif

            <!-- Images Gallery -->
            @if($tour->images->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Thư Viện Ảnh ({{ $tour->images->count() }})</h2>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($tour->images as $image)
                        <div class="relative">
                            <img src="{{ App\Facades\ImageHelper::getUrl($image->image_path) }}" 
                                 alt="{{ $image->alt_text }}" 
                                 class="w-full h-24 object-cover rounded">
                            @if($image->is_primary)
                                <span class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                    Chính
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.tours.images.index', $tour) }}" 
                   class="mt-4 block text-center text-blue-600 hover:text-blue-800">
                    Xem Tất Cả
                </a>
            </div>
            @endif

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Thống Kê</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tổng Đặt Chỗ</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $tour->bookings->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Đặt Chỗ Đang Chờ</dt>
                        <dd class="mt-1 text-2xl font-bold text-yellow-600">
                            {{ $tour->bookings->where('status', 'pending')->count() }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Đặt Chỗ Đã Xác Nhận</dt>
                        <dd class="mt-1 text-2xl font-bold text-green-600">
                            {{ $tour->bookings->where('status', 'confirmed')->count() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin-layout>
