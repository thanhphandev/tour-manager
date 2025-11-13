<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Tạo Email Template</h2>
            <a href="{{ route('admin.email-templates.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('admin.email-templates.store') }}" class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên Template *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">Chỉ dùng chữ thường, số và dấu gạch ngang</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="mt-1 text-xs text-gray-500">Sử dụng biến với cú pháp {<!-- -->{variable_name}}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nội Dung Email *</label>
                <textarea name="body" rows="12" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Hỗ trợ HTML. Sử dụng biến với cú pháp {<!-- -->{variable_name}}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Biến Khả Dụng (JSON)</label>
                <textarea name="variables" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('variables', '["customer_name", "booking_code"]') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: ["customer_name", "booking_code", "tour_name"]</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <label class="ml-2 text-sm text-gray-700">Kích hoạt template</label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('admin.email-templates.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Tạo Template
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
