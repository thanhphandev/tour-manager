<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Tour Manager',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Tên website',
                'description' => 'Tên hiển thị của website',
            ],
            [
                'key' => 'site_description',
                'value' => 'Hệ thống quản lý tour du lịch chuyên nghiệp',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Mô tả website',
                'description' => 'Mô tả ngắn về website',
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@tourmanager.com',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Email liên hệ',
                'description' => 'Email chính của công ty',
            ],
            [
                'key' => 'contact_phone',
                'value' => '1900-xxxx',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Số điện thoại',
                'description' => 'Số điện thoại liên hệ',
            ],
            
            // Booking Settings
            [
                'key' => 'booking_auto_confirm',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'booking',
                'label' => 'Tự động xác nhận booking',
                'description' => 'Tự động xác nhận booking sau khi thanh toán',
            ],
            [
                'key' => 'booking_cancellation_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'booking',
                'label' => 'Thời gian hủy booking (giờ)',
                'description' => 'Số giờ trước tour mà khách có thể hủy',
            ],
            [
                'key' => 'booking_deposit_percentage',
                'value' => '30',
                'type' => 'integer',
                'group' => 'booking',
                'label' => 'Phần trăm đặt cọc (%)',
                'description' => 'Phần trăm số tiền đặt cọc khi booking',
            ],
            
            // Payment Settings
            [
                'key' => 'payment_vnpay_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'Kích hoạt VNPay',
                'description' => 'Cho phép thanh toán qua VNPay',
            ],
            [
                'key' => 'payment_mock_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'Kích hoạt Mock Payment',
                'description' => 'Cho phép thanh toán demo (testing)',
            ],
            
            // Email Settings
            [
                'key' => 'email_from_name',
                'value' => 'Tour Manager',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Tên người gửi email',
                'description' => 'Tên hiển thị khi gửi email',
            ],
            [
                'key' => 'email_from_address',
                'value' => 'noreply@tourmanager.com',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Địa chỉ email gửi',
                'description' => 'Địa chỉ email mặc định để gửi',
            ],
            [
                'key' => 'email_booking_notification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'label' => 'Gửi email thông báo booking',
                'description' => 'Gửi email khi có booking mới',
            ],
            
            // Review Settings
            [
                'key' => 'review_auto_approve',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'review',
                'label' => 'Tự động duyệt review',
                'description' => 'Tự động duyệt review mới',
            ],
            [
                'key' => 'review_require_booking',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'review',
                'label' => 'Yêu cầu booking để review',
                'description' => 'Chỉ cho phép review nếu đã có booking',
            ],
            
            // SEO Settings
            [
                'key' => 'seo_meta_title',
                'value' => 'Tour Manager - Khám phá thế giới',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Meta Title',
                'description' => 'Tiêu đề SEO của website',
            ],
            [
                'key' => 'seo_meta_description',
                'value' => 'Đặt tour du lịch trực tuyến, khám phá các điểm đến tuyệt vời',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Meta Description',
                'description' => 'Mô tả SEO của website',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
