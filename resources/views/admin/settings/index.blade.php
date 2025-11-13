<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Cài Đặt Hệ Thống</h2>
            <a href="{{ route('admin.settings.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Chỉnh Sửa
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <h3 class="text-lg font-bold text-white capitalize">{{ ucfirst($group) }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($groupSettings as $setting)
                            <div class="border-b border-gray-200 pb-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $setting->label }}</h4>
                                        @if($setting->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-4 text-right">
                                        @if($setting->type === 'boolean')
                                            @if($setting->value)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Bật
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Tắt
                                                </span>
                                            @endif
                                        @else
                                            <p class="text-sm font-medium text-gray-900">{{ $setting->value }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-400">
                                    Key: <code class="bg-gray-100 px-2 py-1 rounded">{{ $setting->key }}</code>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
