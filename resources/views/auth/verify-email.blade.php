<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block group">
                    <x-application-logo class="h-16 w-auto mx-auto text-indigo-600 transition-transform duration-300 group-hover:scale-110" />
                </a>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Xác Thực Email
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Kiểm tra hộp thư của bạn
                </p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6 backdrop-blur-sm bg-white/90">
                <!-- Info Message -->
                <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <svg class="h-6 w-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-gray-700">
                        Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác thực địa chỉ email của bạn bằng cách nhấp vào link chúng tôi vừa gửi cho bạn. Nếu bạn không nhận được email, chúng tôi sẽ gửi lại cho bạn.
                    </div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="flex items-start space-x-3 p-4 bg-green-50 rounded-lg border border-green-200">
                        <svg class="h-6 w-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm font-medium text-green-700">
                            Link xác thực mới đã được gửi đến địa chỉ email bạn đã đăng ký.
                        </div>
                    </div>
                @endif

                <div class="space-y-4">
                    <!-- Resend Button -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-[1.02]">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Gửi Lại Email Xác Thực
                        </button>
                    </form>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Đăng Xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
