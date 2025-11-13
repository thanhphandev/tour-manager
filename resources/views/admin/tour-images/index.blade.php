<x-admin-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản Lý Hình Ảnh</h1>
                <p class="mt-1 text-sm text-gray-600">Tour: {{ $tour->name }}</p>
            </div>
            <a href="{{ route('admin.tours.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                Quay Lại Danh Sách Tour
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Tải Ảnh Lên</h2>
        <form action="{{ route('admin.tours.images.store', $tour) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                        Chọn Hình Ảnh (có thể chọn nhiều)
                    </label>
                    <input type="file" 
                           name="images[]" 
                           id="images" 
                           multiple 
                           accept="image/*" 
                           required
                           class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100">
                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Tải Lên
                </button>
            </div>
        </form>
    </div>

    <!-- Images Grid -->
    @if($images->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Thư Viện Ảnh ({{ $images->count() }} ảnh)</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($images as $image)
            <div class="relative group">
                <img src="{{ asset('storage/' . $image->image_path) }}" 
                     alt="{{ $image->alt_text }}" 
                     class="w-full h-48 object-cover rounded-lg">
                
                <!-- Primary Badge -->
                @if($image->is_primary)
                <div class="absolute top-2 left-2">
                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded">
                        Ảnh Chính
                    </span>
                </div>
                @endif

                <!-- Order Badge -->
                <div class="absolute top-2 right-2">
                    <span class="bg-gray-800 bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                        #{{ $image->order + 1 }}
                    </span>
                </div>

                <!-- Actions (shown on hover) -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center gap-2">
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex gap-2">
                        @if(!$image->is_primary)
                        <form action="{{ route('admin.tours.images.primary', [$tour, $image]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition text-sm">
                                Đặt Làm Ảnh Chính
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('admin.images.destroy', $image) }}" 
                              method="POST"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 transition text-sm">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Alt Text -->
                <div class="mt-2 text-sm text-gray-600 truncate">
                    {{ $image->alt_text }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có hình ảnh</h3>
        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tải lên một số hình ảnh cho tour này.</p>
    </div>
    @endif
</x-admin-layout>
