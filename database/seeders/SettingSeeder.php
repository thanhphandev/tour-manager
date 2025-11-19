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
            // Payment Settings
            [
                'key' => 'payment_vnpay_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'Kích hoạt VNPay',
                'description' => 'Cho phép thanh toán qua VNPay',
            ]
            ,
            
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
