<x-admin-layout>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chỉnh Sửa Điểm Đến</h1>
                <p class="mt-1 text-sm text-gray-600">Cập nhật thông tin: {{ $destination->name }}</p>
            </div>
            <a href="{{ route('admin.destinations.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay Lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data" id="destinationForm">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">Thông Tin Cơ Bản</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Tên -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tên Điểm Đến <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $destination->name) }}" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ví dụ: Hạ Long Bay">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug (Read-only) -->
                        <div>
                            <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                                Slug
                            </label>
                            <input type="text" 
                                   value="{{ $destination->slug }}" 
                                   readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                        </div>

                        <!-- Mô tả (Markdown) -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mô Tả (Hỗ trợ Markdown)
                            </label>
                            <textarea name="description" id="description">{{ old('description', $destination->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.destinations.index') }}" 
                       class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-lg transition">
                        Hủy Bỏ
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg transition">
                        Cập Nhật Điểm Đến
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Cài Đặt</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Ảnh -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            Hình Ảnh Điểm Đến
                        </label>
                        <div id="imagePreview" class="{{ $destination->image ? '' : 'hidden' }} mb-3">
                            <img src="{{ $destination->image ? App\Facades\ImageHelper::getUrl($destination->image) : '' }}" 
                                 alt="Preview" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                        <label for="image" class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-gray-600">{{ $destination->image ? 'Thay đổi ảnh' : 'Chọn ảnh' }}</span>
                        </label>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*"
                               class="hidden"
                               form="destinationForm">
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Trạng thái -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1" 
                               {{ old('is_active', $destination->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                               form="destinationForm">
                        <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                            Kích Hoạt Điểm Đến
                        </label>
                    </div>

                    <!-- Stats -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Thống Kê</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Số Tours:</dt>
                                <dd class="text-sm font-bold text-gray-900">{{ $destination->tours->count() }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Ngày Tạo:</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $destination->created_at->format('d/m/Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SimpleMDE CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <style>
        .CodeMirror {
            height: 250px !important;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }
        .editor-toolbar {
            border: 1px solid #d1d5db;
            border-bottom: none;
            border-radius: 0.5rem 0.5rem 0 0;
            background: #f9fafb;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script>
        // Initialize Markdown Editor
        const descriptionEditor = new SimpleMDE({
            element: document.getElementById('description'),
            placeholder: "Mô tả về điểm đến (hỗ trợ Markdown)...",
            spellChecker: false,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "guide"],
            status: false,
            minHeight: "250px",
        });

        // Image Preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Paste image from clipboard
        document.addEventListener('paste', function (event) {
            if (!event.clipboardData) return;
            const items = event.clipboardData.items;
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    const file = items[i].getAsFile();
                    if (file) {
                        // Gán file vào input file
                        const input = document.getElementById('image');
                        // Tạo DataTransfer để set file cho input
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        input.files = dt.files;
                        // Hiển thị preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = document.getElementById('imagePreview');
                            preview.querySelector('img').src = e.target.result;
                            preview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    </script>
</x-admin-layout>
