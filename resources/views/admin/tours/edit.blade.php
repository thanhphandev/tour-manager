<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Chỉnh Sửa Tour</h1>
        <p class="mt-1 text-sm text-gray-600">Cập nhật thông tin tour: {{ $tour->name }}</p>
    </div>

    <!-- SimpleMDE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.tours.update', $tour) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên Tour -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên Tour *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tour->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Điểm đến -->
                <div>
                    <label for="destination_id" class="block text-sm font-medium text-gray-700">Điểm Đến *</label>
                    <select name="destination_id" id="destination_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Chọn điểm đến</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" 
                                {{ old('destination_id', $tour->destination_id) == $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('destination_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Giá Tour (Flexible Pricing) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="price_adult" class="block text-sm font-medium text-gray-700">
                            👨 Giá Người Lớn (≥12 tuổi) *
                        </label>
                        <input type="number" name="price_adult" id="price_adult" 
                               value="{{ old('price_adult', $tour->price_adult) }}" 
                               required min="0" step="1000"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price_adult')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="price_child" class="block text-sm font-medium text-gray-700">
                            👦 Giá Trẻ Em (2-11 tuổi) *
                        </label>
                        <input type="number" name="price_child" id="price_child" 
                               value="{{ old('price_child', $tour->price_child) }}" 
                               required min="0" step="1000"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price_child')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="price_infant" class="block text-sm font-medium text-gray-700">
                            👶 Giá Em Bé (<2 tuổi) *
                        </label>
                        <input type="number" name="price_infant" id="price_infant" 
                               value="{{ old('price_infant', $tour->price_infant) }}" 
                               required min="0" step="1000"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price_infant')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Thời gian -->
                <div>
                    <label for="duration_days" class="block text-sm font-medium text-gray-700">
                        Số Ngày <span class="text-gray-500 text-xs">(Tự động tính nếu có ngày bắt đầu/kết thúc)</span>
                    </label>
                    <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $tour->duration_days) }}" min="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('duration_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Số người tối đa -->
                <div>
                    <label for="max_people" class="block text-sm font-medium text-gray-700">Số Người Tối Đa</label>
                    <input type="number" name="max_people" id="max_people" value="{{ old('max_people', $tour->max_people) }}" min="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('max_people')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ngày bắt đầu -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Ngày Bắt Đầu</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $tour->start_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ngày kết thúc -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Ngày Kết Thúc</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $tour->end_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trạng thái -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Trạng Thái *</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active" {{ old('status', $tour->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $tour->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="sold_out" {{ old('status', $tour->status) == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700">Ảnh Đại Diện</label>
                    @if($tour->thumbnail)
                        <div class="mt-2 mb-2">
                            <img src="{{ asset('storage/' . $tour->thumbnail) }}" alt="Current thumbnail" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100">
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div class="flex items-center">
                    <input type="checkbox" name="featured" id="featured" value="1" 
                        {{ old('featured', $tour->featured) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="featured" class="ml-2 block text-sm text-gray-900">
                        Tour Nổi Bật
                    </label>
                </div>

                <!-- Mô tả ngắn -->
                <div class="col-span-2">
                    <label for="short_description" class="block text-sm font-medium text-gray-700">Mô Tả Ngắn *</label>
                    <textarea name="short_description" id="short_description" rows="3" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('short_description', $tour->short_description) }}</textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mô tả đầy đủ -->
                <div class="col-span-2">
                    <label for="full_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô Tả Đầy Đủ * 
                        <span class="text-gray-500 text-xs">(Markdown Editor với Preview)</span>
                    </label>
                    <textarea name="full_description" id="full_description" required>{{ old('full_description', $tour->full_description) }}</textarea>
                    @error('full_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lịch trình -->
                <div class="col-span-2">
                    <label for="itinerary" class="block text-sm font-medium text-gray-700 mb-2">
                        Lịch Trình * 
                        <span class="text-gray-500 text-xs">(Markdown Editor với Preview)</span>
                    </label>
                    <textarea name="itinerary" id="itinerary" required>{{ old('itinerary', $tour->itinerary) }}</textarea>
                    @error('itinerary')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('admin.tours.index') }}" class="text-sm font-semibold text-gray-900">Hủy</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Cập Nhật Tour
                </button>
            </div>
        </form>
    </div>

    <!-- SimpleMDE JavaScript -->
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    
    <script>
        // Initialize SimpleMDE for Full Description
        const fullDescriptionEditor = new SimpleMDE({
            element: document.getElementById('full_description'),
            spellChecker: false,
            placeholder: "Nhập mô tả đầy đủ về tour...",
            toolbar: [
                "bold", "italic", "heading", "|",
                "quote", "unordered-list", "ordered-list", "|",
                "link", "image", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide"
            ],
            status: ["lines", "words"],
            tabSize: 4,
        });

        // Initialize SimpleMDE for Itinerary
        const itineraryEditor = new SimpleMDE({
            element: document.getElementById('itinerary'),
            spellChecker: false,
            placeholder: "Nhập lịch trình chi tiết...",
            toolbar: [
                "bold", "italic", "heading", "|",
                "quote", "unordered-list", "ordered-list", "|",
                "link", "image", "table", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide"
            ],
            status: ["lines", "words"],
            tabSize: 4,
        });

        // Auto calculate duration_days when dates change
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationInput = document.getElementById('duration_days');

        function calculateDuration() {
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (endDate >= startDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    durationInput.value = diffDays;
                }
            }
        }

        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);
    </script>
</x-admin-layout>
