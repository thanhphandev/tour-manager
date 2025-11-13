<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Email Templates</h2>
            <a href="{{ route('admin.email-templates.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                + Tạo Template
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cập Nhật</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($templates as $template)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $template->slug }}</code>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $template->subject }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.email-templates.toggle', $template) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-2 py-1 text-xs font-semibold rounded-full {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $template->is_active ? 'Hoạt động' : 'Tắt' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $template->updated_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.email-templates.show', $template) }}" class="text-indigo-600 hover:text-indigo-900">
                                Xem
                            </a>
                            <a href="{{ route('admin.email-templates.edit', $template) }}" class="text-blue-600 hover:text-blue-900">
                                Sửa
                            </a>
                            <form action="{{ route('admin.email-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa template này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Chưa có email template nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $templates->links() }}
        </div>
    </div>
</x-admin-layout>
