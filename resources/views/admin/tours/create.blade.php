<x-admin-layout>
    <x-slot name="breadcrumb">
        <span class="text-gray-600">Quản lý Tour</span>
    </x-slot>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Tạo Tour Mới
                        </span>
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 max-w-2xl">
                        Điền đầy đủ thông tin để tạo một tour du lịch mới trong hệ thống
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('admin.tours.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Quay Lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-6 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-semibold text-red-800">
                            Có {{ $errors->count() }} lỗi cần khắc phục:
                        </h3>
                        <ul class="mt-3 space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" id="tourForm" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Column -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h2 class="text-xl font-semibold text-white">Thông Tin Cơ Bản</h2>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Tour Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tên Tour <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-300 @enderror"
                                       placeholder="Ví dụ: Du lịch Hạ Long 3 ngày 2 đêm">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Destination -->
                            <div>
                                <label for="destination_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Điểm Đến <span class="text-red-500">*</span>
                                </label>
                                <select name="destination_id" 
                                        id="destination_id" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('destination_id') border-red-300 @enderror">
                                    <option value="">-- Chọn điểm đến --</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" @selected(old('destination_id') == $destination->id)>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Short Description -->
                            <div>
                                <label for="short_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mô Tả Ngắn <span class="text-red-500">*</span>
                                </label>
                                <textarea name="short_description" 
                                          id="short_description" 
                                          rows="3" 
                                          required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none @error('short_description') border-red-300 @enderror"
                                          placeholder="Mô tả ngắn gọn về tour (tối đa 500 ký tự)...">{{ old('short_description') }}</textarea>
                                <div class="mt-1 flex justify-between">
                                    <p class="text-xs text-gray-500">Mô tả ngắn hiển thị trên danh sách tour</p>
                                    <p class="text-xs text-gray-500"><span id="charCount">0</span>/500</p>
                                </div>
                                @error('short_description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pricing Section -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Bảng Giá Tour <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Adult Price -->
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                        <label for="price_adult" class="block text-xs font-semibold text-blue-900 mb-2">
                                            👨 Người Lớn (≥12 tuổi)
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="price_adult" 
                                                   id="price_adult" 
                                                   value="{{ old('price_adult') }}" 
                                                   required 
                                                   min="0"
                                                   step="1000"
                                                   class="w-full px-4 py-2.5 pr-16 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all @error('price_adult') ring-2 ring-red-300 @enderror"
                                                   placeholder="5000000">
                                            <span class="absolute right-3 top-2.5 text-gray-600 text-sm font-medium">VND</span>
                                        </div>
                                        @error('price_adult')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Child Price -->
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                        <label for="price_child" class="block text-xs font-semibold text-green-900 mb-2">
                                            👦 Trẻ Em (2-11 tuổi)
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="price_child" 
                                                   id="price_child" 
                                                   value="{{ old('price_child') }}" 
                                                   required 
                                                   min="0"
                                                   step="1000"
                                                   class="w-full px-4 py-2.5 pr-16 border-0 rounded-lg focus:ring-2 focus:ring-green-500 transition-all @error('price_child') ring-2 ring-red-300 @enderror"
                                                   placeholder="3500000">
                                            <span class="absolute right-3 top-2.5 text-gray-600 text-sm font-medium">VND</span>
                                        </div>
                                        @error('price_child')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Infant Price -->
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                        <label for="price_infant" class="block text-xs font-semibold text-purple-900 mb-2">
                                            👶 Em Bé (&lt;2 tuổi)
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="price_infant" 
                                                   id="price_infant" 
                                                   value="{{ old('price_infant') }}" 
                                                   required 
                                                   min="0"
                                                   step="1000"
                                                   class="w-full px-4 py-2.5 pr-16 border-0 rounded-lg focus:ring-2 focus:ring-purple-500 transition-all @error('price_infant') ring-2 ring-red-300 @enderror"
                                                   placeholder="1000000">
                                            <span class="absolute right-3 top-2.5 text-gray-600 text-sm font-medium">VND</span>
                                        </div>
                                        @error('price_infant')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Duration & Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Duration -->
                                <div>
                                    <label for="duration_days" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Số Ngày <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="duration_days" 
                                           id="duration_days" 
                                           value="{{ old('duration_days') }}" 
                                           required
                                           min="1"
                                           max="365"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('duration_days') border-red-300 @enderror"
                                           placeholder="3">
                                    <p class="mt-1 text-xs text-gray-500">1-365 ngày</p>
                                    @error('duration_days')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Ngày Bắt Đầu <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           name="start_date" 
                                           id="start_date" 
                                           value="{{ old('start_date') }}"
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('start_date') border-red-300 @enderror">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Ngày Kết Thúc <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           name="end_date" 
                                           id="end_date" 
                                           value="{{ old('end_date') }}"
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('end_date') border-red-300 @enderror">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h2 class="text-xl font-semibold text-white">Mô Tả Đầy Đủ</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <textarea name="full_description" 
                                      id="full_description" 
                                      required>{{ old('full_description') }}</textarea>
                            @error('full_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Itinerary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <h2 class="text-xl font-semibold text-white">Lịch Trình Chi Tiết</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <textarea name="itinerary" 
                                      id="itinerary" 
                                      required>{{ old('itinerary') }}</textarea>
                            @error('itinerary')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tour Images -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="bg-gradient-to-r from-orange-600 to-red-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h2 class="text-xl font-semibold text-white">Hình Ảnh Tour</h2>
                                </div>
                                <span class="text-xs text-white bg-white bg-opacity-20 px-3 py-1 rounded-full">Tùy chọn</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="dropzone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 cursor-pointer group">
                                <svg class="mx-auto h-16 w-16 text-gray-400 group-hover:text-blue-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p class="mt-4 text-base font-medium text-gray-700">Kéo và thả ảnh vào đây</p>
                                <p class="mt-1 text-sm text-gray-500">hoặc click để chọn từ máy tính</p>
                                <p class="mt-2 text-xs text-gray-400">PNG, JPG, JPEG, WEBP tối đa 5MB mỗi ảnh</p>
                            </div>
                            <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden">
                            <div id="preview" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="space-y-6">
                    
                    <!-- Settings Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200 lg:sticky lg:top-24">
                        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <h2 class="text-xl font-semibold text-white">Cài Đặt Tour</h2>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            
                            <!-- Thumbnail -->
                            <div>
                                <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-3">
                                    Ảnh Đại Diện <span class="text-red-500">*</span>
                                </label>
                                <div id="thumbnailPreview" class="hidden mb-4">
                                    <div class="relative group">
                                        <img src="" alt="Preview" class="w-full h-56 object-cover rounded-xl border-2 border-gray-200">
                                        <button type="button" 
                                                onclick="removeThumbnail()"
                                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <label for="thumbnail" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 group">
                                    <svg class="w-12 h-12 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="mt-2 text-sm font-medium text-gray-600 group-hover:text-blue-600">Click để chọn ảnh</span>
                                    <span class="mt-1 text-xs text-gray-400">JPG, PNG, WEBP (Max 5MB)</span>
                                </label>
                                <input type="file" 
                                       name="thumbnail" 
                                       id="thumbnail" 
                                       accept="image/jpeg,image/jpg,image/png,image/webp"
                                       required
                                       class="hidden">
                                @error('thumbnail')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Trạng Thái <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="status" 
                                            id="status" 
                                            required
                                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white">
                                        <option value="active" @selected(old('status', 'active') == 'active')>
                                            ✅ Đang Hoạt Động
                                        </option>
                                        <option value="inactive" @selected(old('status') == 'inactive')>
                                            ⏸️ Tạm Ngừng
                                        </option>
                                        <option value="sold_out" @selected(old('status') == 'sold_out')>
                                            🔴 Hết Chỗ
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max People -->
                            <div>
                                <label for="max_people" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Số Người Tối Đa <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <input type="number" 
                                           name="max_people" 
                                           id="max_people" 
                                           value="{{ old('max_people') }}" 
                                           required
                                           min="1"
                                           max="1000"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           placeholder="20">
                                </div>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Tối thiểu 1 người, tối đa 1000 người
                                </p>
                                @error('max_people')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Featured -->
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               name="featured" 
                                               id="featured" 
                                               value="1" 
                                               @checked(old('featured'))
                                               class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-2 focus:ring-amber-500 transition-all">
                                    </div>
                                    <div class="ml-3">
                                        <label for="featured" class="font-semibold text-sm text-gray-900 cursor-pointer">
                                            ⭐ Tour Nổi Bật
                                        </label>
                                        <p class="text-xs text-gray-600 mt-1">
                                            Tour sẽ được hiển thị ở vị trí ưu tiên trên trang chủ
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 border-t border-gray-200 space-y-3">
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tạo Tour Ngay
                                </button>
                                <a href="{{ route('admin.tours.index') }}" 
                                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Hủy Bỏ
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-blue-900">Hướng Dẫn</h3>
                                <ul class="mt-2 text-xs text-blue-800 space-y-1">
                                    <li>• Điền đầy đủ thông tin bắt buộc (*)</li>
                                    <li>• Sử dụng Markdown cho mô tả & lịch trình</li>
                                    <li>• Ảnh đại diện tối đa 5MB</li>
                                    <li>• Có thể thêm nhiều ảnh tour</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- SimpleMDE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    
    <style>
        .CodeMirror {
            height: 350px !important;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .editor-toolbar {
            border: 1px solid #e5e7eb;
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0;
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
            padding: 8px;
        }
        
        .editor-toolbar button {
            border-radius: 0.375rem !important;
            transition: all 0.2s;
        }
        
        .editor-toolbar button:hover {
            background: #dbeafe !important;
            border-color: #3b82f6 !important;
        }
        
        .CodeMirror-scroll {
            min-height: 350px;
        }
        
        .editor-preview, .editor-preview-side {
            background: white;
            padding: 1.5rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .CodeMirror-fullscreen {
            z-index: 9999 !important;
        }
        
        .editor-toolbar.fullscreen {
            z-index: 9999 !important;
        }
        
        .CodeMirror-sided {
            width: 50% !important;
        }
    </style>
    
    <!-- SimpleMDE JS -->
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    
    <script>
        // Character counter for short description
        const shortDesc = document.getElementById('short_description');
        const charCount = document.getElementById('charCount');
        
        if (shortDesc && charCount) {
            shortDesc.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                if (this.value.length > 500) {
                    charCount.classList.add('text-red-600', 'font-bold');
                } else {
                    charCount.classList.remove('text-red-600', 'font-bold');
                }
            });
            charCount.textContent = shortDesc.value.length;
        }

        // Initialize SimpleMDE for Full Description
        const fullDescriptionEditor = new SimpleMDE({
            element: document.getElementById('full_description'),
            placeholder: "Nhập mô tả đầy đủ về tour (hỗ trợ Markdown)...\n\nVí dụ:\n## Điểm Nổi Bật\n- Điểm 1\n- Điểm 2\n\n## Dịch Vụ Bao Gồm\n- Dịch vụ 1\n- Dịch vụ 2",
            spellChecker: false,
            toolbar: [
                "bold", "italic", "heading", "|", 
                "quote", "unordered-list", "ordered-list", "|", 
                "link", "image", "|", 
                "preview", "side-by-side", "fullscreen", "|", 
                "guide"
            ],
            status: ["lines", "words", "cursor"],
            minHeight: "350px",
            autosave: {
                enabled: true,
                uniqueId: "tour-full-description",
                delay: 1000,
            },
        });

        // Initialize SimpleMDE for Itinerary
        const itineraryEditor = new SimpleMDE({
            element: document.getElementById('itinerary'),
            placeholder: "Nhập lịch trình chi tiết theo từng ngày...\n\n## Ngày 1: Khởi hành - Đến điểm đến\n\n**Sáng:**\n- 07:00 - Tập trung tại điểm hẹn\n- 08:00 - Khởi hành\n\n**Trưa:**\n- 12:00 - Dùng bữa trưa\n\n**Chiều:**\n- 14:00 - Tham quan điểm A\n- 16:00 - Tham quan điểm B\n\n**Tối:**\n- 18:00 - Nhận phòng khách sạn\n- 19:00 - Dùng bữa tối",
            spellChecker: false,
            toolbar: [
                "bold", "italic", "heading", "|", 
                "quote", "unordered-list", "ordered-list", "|", 
                "link", "image", "|", 
                "preview", "side-by-side", "fullscreen", "|", 
                "guide"
            ],
            status: ["lines", "words", "cursor"],
            minHeight: "350px",
            autosave: {
                enabled: true,
                uniqueId: "tour-itinerary",
                delay: 1000,
            },
        });

        // Thumbnail preview with size validation
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnailPreview');
        
        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ảnh không được vượt quá 5MB!');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.querySelector('img').src = e.target.result;
                    thumbnailPreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        window.removeThumbnail = function() {
            thumbnailInput.value = '';
            thumbnailPreview.classList.add('hidden');
            thumbnailPreview.querySelector('img').src = '';
        }

        // Multiple images drag & drop with validation
        const dropzone = document.getElementById('dropzone');
        const imagesInput = document.getElementById('images');
        const preview = document.getElementById('preview');
        let selectedFiles = [];

        dropzone.addEventListener('click', () => imagesInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            const files = Array.from(e.dataTransfer.files).filter(file => 
                file.type.startsWith('image/')
            );
            handleFiles(files);
        });

        imagesInput.addEventListener('change', (e) => {
            handleFiles(Array.from(e.target.files));
        });

        function handleFiles(files) {
            const validFiles = files.filter(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`Ảnh "${file.name}" vượt quá 5MB!`);
                    return false;
                }
                return true;
            });
            
            selectedFiles = [...selectedFiles, ...validFiles];
            updateDataTransfer();
            updatePreview();
        }

        function updatePreview() {
            preview.innerHTML = '';
            if (selectedFiles.length === 0) {
                preview.classList.add('hidden');
                return;
            }
            
            preview.classList.remove('hidden');
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl shadow-sm border-2 border-gray-200 group-hover:border-blue-500 transition-all">
                        <button type="button" 
                                onclick="removeImage(${index})"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="absolute bottom-2 left-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                            ${(file.size / 1024).toFixed(0)} KB
                        </div>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }

        window.removeImage = function(index) {
            selectedFiles.splice(index, 1);
            updateDataTransfer();
            updatePreview();
        }

        function updateDataTransfer() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            imagesInput.files = dt.files;
        }

        // Auto-calculate duration based on dates
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationInput = document.getElementById('duration_days');

        function calculateDuration() {
            if (startDateInput.value && endDateInput.value) {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                if (diffDays > 0 && diffDays <= 365) {
                    durationInput.value = diffDays;
                }
            }
        }

        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);
    </script>
</x-admin-layout>
