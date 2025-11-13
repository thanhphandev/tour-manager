<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gửi Email Hàng Loạt</h1>
        <p class="text-gray-600 mt-2">Gửi email marketing hoặc thông báo đến khách hàng</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Compose Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Soạn Email</h2>
                </div>

                <form action="{{ route('admin.emails.send') }}" method="POST" class="p-6 space-y-6" x-data="emailComposer()">
                    @csrf

                    <!-- Send Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gửi đến</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                                   :class="sendType === 'individual' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="send_type" value="individual" x-model="sendType" class="sr-only">
                                <div class="flex-1 text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2" :class="sendType === 'individual' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-sm font-medium" :class="sendType === 'individual' ? 'text-indigo-600' : 'text-gray-700'">Cá nhân</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                                   :class="sendType === 'group' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="send_type" value="group" x-model="sendType" class="sr-only">
                                <div class="flex-1 text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2" :class="sendType === 'group' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium" :class="sendType === 'group' ? 'text-indigo-600' : 'text-gray-700'">Nhóm</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                                   :class="sendType === 'all' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="send_type" value="all" x-model="sendType" class="sr-only">
                                <div class="flex-1 text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2" :class="sendType === 'all' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                                    </svg>
                                    <span class="text-sm font-medium" :class="sendType === 'all' ? 'text-indigo-600' : 'text-gray-700'">Tất cả</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Individual Email -->
                    <div x-show="sendType === 'individual'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email người nhận</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="email@example.com">
                    </div>

                    <!-- Group Selection -->
                    <div x-show="sendType === 'group'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn người nhận</label>
                        <div class="border border-gray-300 rounded-lg max-h-64 overflow-y-auto p-4 space-y-2">
                            @foreach(\App\Models\User::where('is_admin', false)->get() as $user)
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="ml-3 text-sm text-gray-700">{{ $user->name }} ({{ $user->email }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- All Users Info -->
                    <div x-show="sendType === 'all'" x-cloak>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-blue-700">Email sẽ được gửi đến <strong>{{ \App\Models\User::where('is_admin', false)->count() }}</strong> khách hàng</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="recipients" value="1">

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề</label>
                        <input type="text" name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Nhập tiêu đề email...">
                    </div>

                    <!-- Body -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung</label>
                        <textarea name="body" rows="12" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm" placeholder="Nhập nội dung email (hỗ trợ HTML)..."></textarea>
                        <p class="mt-2 text-xs text-gray-500">Bạn có thể sử dụng HTML để định dạng email</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Gửi Email
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Templates -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Mẫu Email</h3>
                <div class="space-y-2">
                    @foreach(\App\Models\EmailTemplate::active()->get() as $template)
                    <button type="button" onclick="loadTemplate({{ $template->id }})" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="font-medium text-gray-900">{{ $template->name }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $template->subject }}</div>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">💡 Tips</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li>• Sử dụng tiêu đề ngắn gọn, hấp dẫn</li>
                    <li>• Kiểm tra kỹ nội dung trước khi gửi</li>
                    <li>• Tránh gửi email quá nhiều lần</li>
                    <li>• Cá nhân hóa nội dung khi có thể</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function emailComposer() {
            return {
                sendType: 'individual'
            }
        }

        function loadTemplate(templateId) {
            fetch(`{{ route('admin.emails.preview') }}?template_id=${templateId}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('input[name="subject"]').value = data.subject;
                    document.querySelector('textarea[name="body"]').value = data.body;
                });
        }
    </script>
    @endpush
</x-admin-layout>
