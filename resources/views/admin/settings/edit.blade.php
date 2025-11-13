<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Chỉnh Sửa Cài Đặt</h2>
            <a href="{{ route('admin.settings.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <h3 class="text-lg font-bold text-white capitalize">{{ ucfirst($group) }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($groupSettings as $setting)
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ $setting->label }}
                                    @if($setting->description)
                                        <span class="block text-xs font-normal text-gray-500 mt-1">{{ $setting->description }}</span>
                                    @endif
                                </label>

                                @if($setting->type === 'boolean')
                                    <div class="flex items-center">
                                        <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                        <input type="checkbox" 
                                               name="settings[{{ $setting->key }}]" 
                                               value="1"
                                               {{ $setting->value ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">Kích hoạt</span>
                                    </div>
                                @elseif($setting->type === 'integer')
                                    <input type="number" 
                                           name="settings[{{ $setting->key }}]" 
                                           value="{{ $setting->value }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @elseif($setting->type === 'json')
                                    <textarea name="settings[{{ $setting->key }}]" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}</textarea>
                                @else
                                    <input type="text" 
                                           name="settings[{{ $setting->key }}]" 
                                           value="{{ $setting->value }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @endif

                                <p class="mt-1 text-xs text-gray-400">
                                    Key: <code class="bg-gray-100 px-2 py-1 rounded">{{ $setting->key }}</code>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Lưu Cài Đặt
            </button>
        </div>
    </form>
</x-admin-layout>
