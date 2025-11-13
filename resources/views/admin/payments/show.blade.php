<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Chi Tiết Thanh Toán</h2>
            <a href="{{ route('admin.payments.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Thông Tin Thanh Toán</h3>
                        <p class="text-sm text-gray-500 mt-1">Mã giao dịch: {{ $payment->transaction_id }}</p>
                    </div>
                    @if($payment->status == 'success')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Thành công
                        </span>
                    @elseif($payment->status == 'pending')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Chờ xử lý
                        </span>
                    @elseif($payment->status == 'failed')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Thất bại
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                            Đã hoàn tiền
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Số tiền</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($payment->amount) }}đ</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phương thức thanh toán</p>
                        <p class="text-lg font-semibold text-gray-900">{{ strtoupper($payment->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ngày thanh toán</p>
                        <p class="font-medium text-gray-900">{{ $payment->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cập nhật lần cuối</p>
                        <p class="font-medium text-gray-900">{{ $payment->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                @if($payment->status == 'success')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-4">Hoàn Tiền</h4>
                        <form action="{{ route('admin.payments.refund', $payment) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hoàn tiền cho giao dịch này?')">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lý do hoàn tiền</label>
                                <textarea name="reason" rows="3" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Nhập lý do hoàn tiền..."></textarea>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Hoàn Tiền
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thông Tin Booking</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Mã booking</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->booking_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Trạng thái booking</p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $payment->booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : 
                               ($payment->booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($payment->booking->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Số người</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->total_people }} người</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tổng tiền booking</p>
                        <p class="font-medium text-gray-900">{{ number_format($payment->booking->total_amount) }}đ</p>
                    </div>
                </div>
                <a href="{{ route('admin.bookings.show', $payment->booking) }}" 
                   class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Xem Chi Tiết Booking
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Khách Hàng</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Tên</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Điện thoại</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Tour Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tour</h3>
                @if($payment->booking->tour->thumbnail)
                    <img src="{{ Storage::url($payment->booking->tour->thumbnail) }}" 
                         alt="{{ $payment->booking->tour->name }}"
                         class="w-full h-32 object-cover rounded-lg mb-4">
                @endif
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Tên tour</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->tour->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Điểm đến</p>
                        <p class="font-medium text-gray-900">{{ $payment->booking->tour->destination->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Giá tour</p>
                        <p class="font-medium text-gray-900">{{ number_format($payment->booking->total_amount / $payment->booking->total_people) }}đ (TB)</p>
                    </div>
                </div>
                <a href="{{ route('admin.tours.show', $payment->booking->tour) }}" 
                   class="mt-4 block text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Xem Tour
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow p-6 text-white">
                <h3 class="text-lg font-bold mb-4">Thống Kê Nhanh</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Tổng booking:</span>
                        <span class="font-bold">{{ $payment->booking->user->bookings()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tổng chi tiêu:</span>
                        <span class="font-bold">{{ number_format($payment->booking->user->bookings()->sum('total_amount')) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
