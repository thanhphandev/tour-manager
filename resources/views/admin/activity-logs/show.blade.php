<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Chi Tiết Activity Log</h2>
            <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                ← Quay Lại
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Thông Tin Cơ Bản</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mô Tả</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $activityLog->description }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Event</dt>
                        <dd class="mt-1">
                            @if($activityLog->event)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $activityLog->event }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Log Name</dt>
                        <dd class="mt-1">
                            @if($activityLog->log_name)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $activityLog->log_name }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Thời Gian</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $activityLog->created_at->format('d/m/Y H:i:s') }}
                            <span class="text-xs text-gray-500">({{ $activityLog->created_at->diffForHumans() }})</span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Thông Tin Người Dùng</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User (Causer)</dt>
                        <dd class="mt-1">
                            @if($activityLog->causer)
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ $activityLog->causer->name }}</div>
                                    <div class="text-gray-500">{{ $activityLog->causer->email }}</div>
                                    <div class="text-xs text-gray-400">ID: {{ $activityLog->causer_id }}</div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Associated User</dt>
                        <dd class="mt-1">
                            @if($activityLog->user)
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ $activityLog->user->name }}</div>
                                    <div class="text-gray-500">{{ $activityLog->user->email }}</div>
                                    <div class="text-xs text-gray-400">ID: {{ $activityLog->user_id }}</div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">System / Guest</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $activityLog->ip_address ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">{{ $activityLog->user_agent ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Subject Information -->
        @if($activityLog->subject_type)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Subject (Đối Tượng)</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subject Type</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ class_basename($activityLog->subject_type) }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subject ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $activityLog->subject_id }}</dd>
                    </div>

                    @if($activityLog->subject)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Subject Details</dt>
                        <dd class="mt-2">
                            <div class="bg-gray-50 rounded-md p-4">
                                <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($activityLog->subject->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </dd>
                    </div>
                    @else
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Subject Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Subject đã bị xóa hoặc không tồn tại
                            </span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
        @endif

        <!-- Properties -->
        @if($activityLog->properties && count($activityLog->properties) > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Properties (Thuộc Tính)</h3>
            </div>
            <div class="p-6">
                <div class="bg-gray-50 rounded-md p-4 overflow-x-auto">
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Quay Lại Danh Sách
            </a>
            <form method="POST" action="{{ route('admin.activity-logs.destroy', $activityLog) }}" 
                  onsubmit="return confirm('Bạn có chắc muốn xóa log này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Xóa Log
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
