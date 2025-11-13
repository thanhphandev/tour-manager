<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Chi Tiết Đánh Giá</h2>
            <a href="{{ route('admin.reviews.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $review->title }}</h3>
                        <div class="flex items-center mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    @if($review->is_approved)
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Đã duyệt
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Chờ duyệt
                        </span>
                    @endif
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $review->comment }}</p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Đăng ngày: {{ $review->created_at->format('d/m/Y H:i') }}</span>
                        @if($review->is_verified)
                            <span class="flex items-center text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Đã xác thực mua hàng
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex gap-3">
                    @if(!$review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Phê Duyệt
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                Bỏ Duyệt
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa review này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Xóa Review
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thông Tin Khách Hàng</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Tên khách hàng</p>
                        <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $review->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tổng đánh giá</p>
                        <p class="font-medium text-gray-900">{{ $review->user->reviews()->count() }} reviews</p>
                    </div>
                </div>
            </div>

            <!-- Tour Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thông Tin Tour</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Tên tour</p>
                        <p class="font-medium text-gray-900">{{ $review->tour->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Điểm đến</p>
                        <p class="font-medium text-gray-900">{{ $review->tour->destination->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Đánh giá trung bình</p>
                        <p class="font-medium text-gray-900">{{ round($review->tour->approvedReviews()->avg('rating'), 1) }}/5</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tổng đánh giá</p>
                        <p class="font-medium text-gray-900">{{ $review->tour->approvedReviews()->count() }} reviews</p>
                    </div>
                </div>
                <a href="{{ route('admin.tours.show', $review->tour) }}" class="mt-4 block text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Xem Tour
                </a>
            </div>

            <!-- Booking Info -->
            @if($review->booking)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Thông Tin Booking</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Mã booking</p>
                            <p class="font-medium text-gray-900">{{ $review->booking->booking_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Trạng thái</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($review->booking->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ngày đặt</p>
                            <p class="font-medium text-gray-900">{{ $review->booking->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.bookings.show', $review->booking) }}" class="mt-4 block text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Xem Booking
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
