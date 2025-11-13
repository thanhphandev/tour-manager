<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Tour Mới') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.tours.index') }}" 
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Quản lý Tours
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Thêm Tour Mới</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Tạo Tour Mới</h1>
                        <p class="mt-1 text-sm text-gray-600">Điền thông tin chi tiết để thêm tour mới vào hệ thống</p>
                    </div>
                </div>
            </div>

            <!-- SimpleMDE CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

            {{-- Form Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" id="tourForm">
                    @csrf
                    
                    {{-- Basic Information Section --}}
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Thông Tin Cơ Bản
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Thông tin chính về tour du lịch</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Tên Tour --}}
                            <div class="lg:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tên Tour <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       placeholder="Ví dụ: Du lịch Đà Nẵng - Hội An 3 ngày 2 đêm"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Điểm đến --}}
                            <div>
                                <label for="destination_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Điểm Đến <span class="text-red-500">*</span>
                                </label>
                                <select name="destination_id" 
                                        id="destination_id" 
                                        required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('destination_id') border-red-500 @enderror">
                                    <option value="">-- Chọn điểm đến --</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Trạng thái --}}
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Trạng Thái <span class="text-red-500">*</span>
                                </label>
                                <select name="status" 
                                        id="status" 
                                        required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                        ✅ Hoạt động
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        ⏸️ Tạm dừng
                                    </option>
                                    <option value="sold_out" {{ old('status') == 'sold_out' ? 'selected' : '' }}>
                                        ❌ Hết chỗ
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Tour Nổi Bật --}}
                            <div class="lg:col-span-2">
                                <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <input type="checkbox" 
                                           name="featured" 
                                           id="featured" 
                                           value="1" 
                                           {{ old('featured') ? 'checked' : '' }}
                                           class="h-5 w-5 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                    <label for="featured" class="ml-3 flex items-center text-sm font-medium text-gray-900">
                                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Đánh dấu là Tour Nổi Bật
                                        <span class="ml-2 text-xs text-gray-500">(Hiển thị ưu tiên trên trang chủ)</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Mô tả ngắn --}}
                            <div class="lg:col-span-2">
                                <label for="short_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mô Tả Ngắn <span class="text-red-500">*</span>
                                    <span class="text-gray-500 font-normal text-xs">(Tối đa 500 ký tự)</span>
                                </label>
                                <textarea name="short_description" 
                                          id="short_description" 
                                          rows="3" 
                                          required
                                          maxlength="500"
                                          placeholder="Mô tả ngắn gọn về tour, thu hút khách hàng..."
                                          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                                <div class="mt-1 flex justify-between items-center">
                                    @error('short_description')
                                        <p class="text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @else
                                        <span></span>
                                    @enderror
                                    <span class="text-xs text-gray-500" id="charCount">0 / 500</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing Section --}}
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Giá Tour
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Thiết lập giá cho các đối tượng khách hàng</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Giá Người Lớn --}}
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <label for="price_adult" class="block text-sm font-semibold text-gray-700 mb-2">
                                    👨 Giá Người Lớn <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 font-normal mt-1">(≥ 12 tuổi)</span>
                                </label>
                                <div class="relative mt-2">
                                    <input type="number" 
                                           name="price_adult" 
                                           id="price_adult" 
                                           value="{{ old('price_adult') }}" 
                                           required 
                                           min="0" 
                                           step="1000"
                                           placeholder="0"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-12 text-sm @error('price_adult') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">VNĐ</span>
                                    </div>
                                </div>
                                @error('price_adult')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Giá Trẻ Em --}}
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <label for="price_child" class="block text-sm font-semibold text-gray-700 mb-2">
                                    👦 Giá Trẻ Em <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 font-normal mt-1">(2-11 tuổi)</span>
                                </label>
                                <div class="relative mt-2">
                                    <input type="number" 
                                           name="price_child" 
                                           id="price_child" 
                                           value="{{ old('price_child') }}" 
                                           required 
                                           min="0" 
                                           step="1000"
                                           placeholder="0"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 pr-12 text-sm @error('price_child') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">VNĐ</span>
                                    </div>
                                </div>
                                @error('price_child')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Giá Em Bé --}}
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <label for="price_infant" class="block text-sm font-semibold text-gray-700 mb-2">
                                    👶 Giá Em Bé <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 font-normal mt-1">(< 2 tuổi)</span>
                                </label>
                                <div class="relative mt-2">
                                    <input type="number" 
                                           name="price_infant" 
                                           id="price_infant" 
                                           value="{{ old('price_infant', 0) }}" 
                                           required 
                                           min="0" 
                                           step="1000"
                                           placeholder="0"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 pr-12 text-sm @error('price_infant') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">VNĐ</span>
                                    </div>
                                </div>
                                @error('price_infant')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Schedule & Capacity Section --}}
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Lịch Trình & Sức Chứa
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Thông tin về thời gian và số lượng khách</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {{-- Ngày bắt đầu --}}
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
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ngày kết thúc --}}
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
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Số ngày --}}
                            <div>
                                <label for="duration_days" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Số Ngày <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 font-normal mt-1">(Tự động tính)</span>
                                </label>
                                <input type="number" 
                                       name="duration_days" 
                                       id="duration_days" 
                                       value="{{ old('duration_days', 1) }}" 
                                       required
                                       min="1" 
                                       max="365"
                                       readonly
                                       class="block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm text-sm @error('duration_days') border-red-500 @enderror">
                                @error('duration_days')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Số đêm (hidden, auto-calculated) --}}
                            <input type="hidden" name="duration_nights" id="duration_nights" value="{{ old('duration_nights', 0) }}">

                            {{-- Số người tối đa --}}
                            <div>
                                <label for="max_people" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Số Người Tối Đa <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="max_people" 
                                       id="max_people" 
                                       value="{{ old('max_people') }}" 
                                       required
                                       min="1" 
                                       max="1000"
                                       placeholder="Ví dụ: 20"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('max_people') border-red-500 @enderror">
                                @error('max_people')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Images Section --}}
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Hình Ảnh Tour
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Tải lên ảnh đại diện và ảnh chi tiết của tour</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Thumbnail --}}
                            <div>
                                <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ảnh Đại Diện <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 font-normal mt-1">(Tối đa 5MB - JPG, PNG, WEBP)</span>
                                </label>
                                <div class="mt-2">
                                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors" id="thumbnailDropZone">
                                        <div class="space-y-2 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Tải lên ảnh</span>
                                                    <input id="thumbnail" 
                                                           name="thumbnail" 
                                                           type="file" 
                                                           class="sr-only" 
                                                           accept="image/jpeg,image/jpg,image/png,image/webp"
                                                           required>
                                                </label>
                                                <p class="pl-1">hoặc kéo thả</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, WEBP tới 5MB</p>
                                        </div>
                                    </div>
                                    <div id="thumbnailPreview" class="mt-4 hidden">
                                        <img src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-300">
                                        <button type="button" onclick="removeThumbnail()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            ✕ Xóa ảnh
                                        </button>
                                    </div>
                                </div>
                                @error('thumbnail')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Multiple Images --}}
                            <div>
                                <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ảnh Chi Tiết
                                    <span class="block text-xs text-gray-500 font-normal mt-1">(Tùy chọn - Có thể chọn nhiều ảnh, mỗi ảnh tối đa 5MB)</span>
                                </label>
                                <div class="mt-2">
                                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors" id="imagesDropZone">
                                        <div class="space-y-2 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Tải lên nhiều ảnh</span>
                                                    <input id="images" 
                                                           name="images[]" 
                                                           type="file" 
                                                           class="sr-only" 
                                                           accept="image/jpeg,image/jpg,image/png,image/webp"
                                                           multiple>
                                                </label>
                                                <p class="pl-1">hoặc kéo thả</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, WEBP tới 5MB mỗi ảnh</p>
                                        </div>
                                    </div>
                                    <div id="imagesPreview" class="mt-4 grid grid-cols-2 gap-4 hidden"></div>
                                </div>
                                @error('images')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Description Section --}}
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Nội Dung Chi Tiết
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Mô tả chi tiết và lịch trình tour (hỗ trợ Markdown)</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-6">
                            {{-- Mô tả đầy đủ --}}
                            <div>
                                <label for="full_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mô Tả Đầy Đủ <span class="text-red-500">*</span>
                                    <span class="text-gray-500 font-normal text-xs">(Markdown Editor với Preview)</span>
                                </label>
                                <textarea name="full_description" 
                                          id="full_description" 
                                          required>{{ old('full_description') }}</textarea>
                                @error('full_description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Lịch trình --}}
                            <div>
                                <label for="itinerary" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Lịch Trình <span class="text-red-500">*</span>
                                    <span class="text-gray-500 font-normal text-xs">(Markdown Editor với Preview)</span>
                                </label>
                                <textarea name="itinerary" 
                                          id="itinerary" 
                                          required>{{ old('itinerary') }}</textarea>
                                @error('itinerary')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                <span class="text-red-500">*</span> Trường bắt buộc
                            </div>
                            <div class="flex items-center gap-4">
                                <a href="{{ route('admin.tours.index') }}" 
                                   class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Hủy Bỏ
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tạo Tour Mới
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Help Section --}}
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Hướng dẫn sử dụng</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Điền đầy đủ các trường có dấu <span class="text-red-500">*</span></li>
                                <li>Ảnh đại diện sẽ hiển thị trên danh sách tour</li>
                                <li>Sử dụng Markdown để định dạng mô tả và lịch trình (Ctrl + P để xem preview)</li>
                                <li>Số ngày sẽ tự động tính dựa trên ngày bắt đầu và kết thúc</li>
                                <li>Tour nổi bật sẽ được ưu tiên hiển thị trên trang chủ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SimpleMDE JavaScript -->
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    
    <script>
        // Character count for short description
        const shortDescTextarea = document.getElementById('short_description');
        const charCount = document.getElementById('charCount');
        
        shortDescTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = `${count} / 500`;
            if (count > 450) {
                charCount.classList.add('text-red-600');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.add('text-gray-500');
                charCount.classList.remove('text-red-600');
            }
        });

        // Initialize SimpleMDE for Full Description with full toolbar
        const fullDescriptionEditor = new SimpleMDE({
            element: document.getElementById('full_description'),
            spellChecker: false,
            forceSync: true, // Force sync with textarea on submit
            placeholder: "Nhập mô tả đầy đủ về tour...\n\nVí dụ:\n## Giới thiệu\nTour du lịch...\n\n## Điểm nổi bật\n- Điểm 1\n- Điểm 2",
            toolbar: [
                "bold", "italic", "strikethrough", "heading", "heading-smaller", "heading-bigger", "heading-1", "heading-2", "heading-3", "|",
                "code", "quote", "unordered-list", "ordered-list", "clean-block", "|",
                "link", "image", "table", "horizontal-rule", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide", "undo", "redo"
            ],
            status: ["autosave", "lines", "words", "cursor"],
            tabSize: 4,
            indentWithTabs: false,
            autofocus: false,
            autosave: {
                enabled: true,
                uniqueId: "tour_full_description",
                delay: 1000,
            },
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
            shortcuts: {
                toggleBold: "Cmd-B",
                toggleItalic: "Cmd-I",
                toggleStrikethrough: "Cmd-Alt-S",
                toggleCodeBlock: "Cmd-Alt-C",
                togglePreview: "Cmd-P",
                toggleSideBySide: "F9",
                toggleFullScreen: "F11"
            }
        });

        // Initialize SimpleMDE for Itinerary with full toolbar
        const itineraryEditor = new SimpleMDE({
            element: document.getElementById('itinerary'),
            spellChecker: false,
            forceSync: true, // Force sync with textarea on submit
            placeholder: "Nhập lịch trình chi tiết...\n\nVí dụ:\n## Ngày 1: Hà Nội - Đà Nẵng\n**Sáng:**\n- 8:00: Khởi hành từ Hà Nội\n- 10:00: Đến sân bay Đà Nẵng\n\n**Chiều:**\n- 14:00: Tham quan...",
            toolbar: [
                "bold", "italic", "strikethrough", "heading", "heading-smaller", "heading-bigger", "heading-1", "heading-2", "heading-3", "|",
                "code", "quote", "unordered-list", "ordered-list", "clean-block", "|",
                "link", "image", "table", "horizontal-rule", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide", "undo", "redo"
            ],
            status: ["autosave", "lines", "words", "cursor"],
            tabSize: 4,
            indentWithTabs: false,
            autofocus: false,
            autosave: {
                enabled: true,
                uniqueId: "tour_itinerary",
                delay: 1000,
            },
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
            shortcuts: {
                toggleBold: "Cmd-B",
                toggleItalic: "Cmd-I",
                toggleStrikethrough: "Cmd-Alt-S",
                toggleCodeBlock: "Cmd-Alt-C",
                togglePreview: "Cmd-P",
                toggleSideBySide: "F9",
                toggleFullScreen: "F11"
            }
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
                    const diffNights = diffDays - 1;
                    
                    durationInput.value = diffDays;
                    document.getElementById('duration_nights').value = diffNights;
                    
                    console.log('Duration calculated:', { days: diffDays, nights: diffNights });
                } else {
                    endDateInput.setCustomValidity('Ngày kết thúc phải sau ngày bắt đầu');
                }
            }
        }

        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);

        // Thumbnail preview
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnailPreview');

        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.querySelector('img').src = e.target.result;
                    thumbnailPreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        function removeThumbnail() {
            thumbnailInput.value = '';
            thumbnailPreview.classList.add('hidden');
        }

        // Multiple images preview
        const imagesInput = document.getElementById('images');
        const imagesPreview = document.getElementById('imagesPreview');

        imagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                imagesPreview.innerHTML = '';
                imagesPreview.classList.remove('hidden');
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-300">
                            <button type="button" onclick="removeImage(${index})" 
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        `;
                        imagesPreview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });

        function removeImage(index) {
            const dt = new DataTransfer();
            const files = Array.from(imagesInput.files);
            
            files.forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            imagesInput.files = dt.files;
            
            if (dt.files.length === 0) {
                imagesPreview.classList.add('hidden');
                imagesPreview.innerHTML = '';
            } else {
                imagesInput.dispatchEvent(new Event('change'));
            }
        }

        // Form validation before submit
        const tourForm = document.getElementById('tourForm');
        console.log('Tour form element:', tourForm);
        
        if (tourForm) {
            console.log('Attaching submit event listener...');
            
            tourForm.addEventListener('submit', function(e) {
                console.log('=== FORM SUBMIT EVENT TRIGGERED ===');
                
                // Sync SimpleMDE content back to textarea before validation
                if (fullDescriptionEditor && fullDescriptionEditor.value) {
                    document.getElementById('full_description').value = fullDescriptionEditor.value();
                    console.log('Synced full_description:', fullDescriptionEditor.value().substring(0, 50));
                }
                
                if (itineraryEditor && itineraryEditor.value) {
                    document.getElementById('itinerary').value = itineraryEditor.value();
                    console.log('Synced itinerary:', itineraryEditor.value().substring(0, 50));
                }
                
                const priceAdult = parseFloat(document.getElementById('price_adult').value) || 0;
                const priceChild = parseFloat(document.getElementById('price_child').value) || 0;
                const priceInfant = parseFloat(document.getElementById('price_infant').value) || 0;
                
                console.log('Prices:', { priceAdult, priceChild, priceInfant });
                
                if (priceChild > priceAdult) {
                    e.preventDefault();
                    alert('⚠️ Giá trẻ em không nên cao hơn giá người lớn!');
                    console.log('Validation failed: child > adult');
                    return false;
                }
                
                if (priceInfant > priceChild) {
                    e.preventDefault();
                    alert('⚠️ Giá em bé không nên cao hơn giá trẻ em!');
                    console.log('Validation failed: infant > child');
                    return false;
                }
                
                console.log('✅ Validation passed, form will submit');
                return true;
            });
            
            console.log('Event listener attached successfully');
        } else {
            console.error('❌ Tour form NOT found!');
        }
        
        // Handle button click - make sure it triggers form submit
        const submitButton = document.querySelector('button[type="submit"]');
        console.log('Submit button:', submitButton);
        
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                console.log('=== BUTTON CLICKED ===');
                console.log('Default prevented?', e.defaultPrevented);
                
                // Ensure form submits if not prevented
                if (!e.defaultPrevented && this.form) {
                    console.log('Manually triggering form submit...');
                    // Let the normal form submit happen
                }
            });
        }

        // Drag and drop for thumbnail
        const thumbnailDropZone = document.getElementById('thumbnailDropZone');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            thumbnailDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            thumbnailDropZone.addEventListener(eventName, () => {
                thumbnailDropZone.classList.add('border-blue-500', 'bg-blue-50');
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            thumbnailDropZone.addEventListener(eventName, () => {
                thumbnailDropZone.classList.remove('border-blue-500', 'bg-blue-50');
            });
        });
        
        thumbnailDropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            thumbnailInput.files = files;
            thumbnailInput.dispatchEvent(new Event('change'));
        });

        // Drag and drop for multiple images
        const imagesDropZone = document.getElementById('imagesDropZone');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imagesDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            imagesDropZone.addEventListener(eventName, () => {
                imagesDropZone.classList.add('border-blue-500', 'bg-blue-50');
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            imagesDropZone.addEventListener(eventName, () => {
                imagesDropZone.classList.remove('border-blue-500', 'bg-blue-50');
            });
        });
        
        imagesDropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            imagesInput.files = files;
            imagesInput.dispatchEvent(new Event('change'));
        });
    </script>
</x-admin-layout>
