<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Xác nhận đặt tour',
                'slug' => 'booking-confirmation',
                'subject' => 'Xác nhận đặt tour - {{booking_code}}',
                'body' => '<h2>Xin chào {{customer_name}},</h2>
                
<p>Cảm ơn bạn đã đặt tour tại Tour Manager!</p>

<h3>Thông tin đặt tour:</h3>
<ul>
    <li><strong>Mã đặt tour:</strong> {{booking_code}}</li>
    <li><strong>Tour:</strong> {{tour_name}}</li>
    <li><strong>Ngày khởi hành:</strong> {{start_date}}</li>
    <li><strong>Số người:</strong> {{total_people}}</li>
    <li><strong>Tổng tiền:</strong> {{total_amount}} VNĐ</li>
</ul>

<p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận booking.</p>

<p>Trân trọng,<br>
Đội ngũ Tour Manager</p>',
                'variables' => ['customer_name', 'booking_code', 'tour_name', 'start_date', 'total_people', 'total_amount'],
                'is_active' => true,
            ],
            [
                'name' => 'Xác nhận thanh toán',
                'slug' => 'payment-confirmation',
                'subject' => 'Thanh toán thành công - {{booking_code}}',
                'body' => '<h2>Xin chào {{customer_name}},</h2>

<p>Thanh toán của bạn đã được xác nhận thành công!</p>

<h3>Thông tin thanh toán:</h3>
<ul>
    <li><strong>Mã giao dịch:</strong> {{transaction_id}}</li>
    <li><strong>Mã đặt tour:</strong> {{booking_code}}</li>
    <li><strong>Số tiền:</strong> {{amount}} VNĐ</li>
    <li><strong>Phương thức:</strong> {{payment_method}}</li>
</ul>

<p>Tour của bạn đã được xác nhận. Chúng tôi sẽ gửi thông tin chi tiết qua email trong thời gian sớm nhất.</p>

<p>Trân trọng,<br>
Đội ngũ Tour Manager</p>',
                'variables' => ['customer_name', 'transaction_id', 'booking_code', 'amount', 'payment_method'],
                'is_active' => true,
            ],
            [
                'name' => 'Hủy booking',
                'slug' => 'booking-cancellation',
                'subject' => 'Thông báo hủy tour - {{booking_code}}',
                'body' => '<h2>Xin chào {{customer_name}},</h2>

<p>Booking của bạn đã được hủy như yêu cầu.</p>

<h3>Thông tin booking đã hủy:</h3>
<ul>
    <li><strong>Mã đặt tour:</strong> {{booking_code}}</li>
    <li><strong>Tour:</strong> {{tour_name}}</li>
    <li><strong>Lý do:</strong> {{cancellation_reason}}</li>
</ul>

<p>Nếu bạn đã thanh toán, chúng tôi sẽ hoàn tiền theo chính sách hoàn tiền của công ty.</p>

<p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>

<p>Trân trọng,<br>
Đội ngũ Tour Manager</p>',
                'variables' => ['customer_name', 'booking_code', 'tour_name', 'cancellation_reason'],
                'is_active' => true,
            ],
            [
                'name' => 'Nhắc nhở tour sắp đến',
                'slug' => 'tour-reminder',
                'subject' => 'Nhắc nhở: Tour {{tour_name}} sắp khởi hành',
                'body' => '<h2>Xin chào {{customer_name}},</h2>

<p>Đây là email nhắc nhở tour của bạn sắp khởi hành!</p>

<h3>Thông tin tour:</h3>
<ul>
    <li><strong>Tour:</strong> {{tour_name}}</li>
    <li><strong>Ngày khởi hành:</strong> {{start_date}}</li>
    <li><strong>Địa điểm tập trung:</strong> {{meeting_point}}</li>
    <li><strong>Thời gian tập trung:</strong> {{meeting_time}}</li>
</ul>

<p>Vui lòng chuẩn bị đầy đủ giấy tờ và hành lý theo hướng dẫn.</p>

<p>Chúc bạn có chuyến đi vui vẻ!<br>
Đội ngũ Tour Manager</p>',
                'variables' => ['customer_name', 'tour_name', 'start_date', 'meeting_point', 'meeting_time'],
                'is_active' => true,
            ],
            [
                'name' => 'Yêu cầu đánh giá',
                'slug' => 'review-request',
                'subject' => 'Đánh giá trải nghiệm tour của bạn',
                'body' => '<h2>Xin chào {{customer_name}},</h2>

<p>Cảm ơn bạn đã tham gia tour <strong>{{tour_name}}</strong>!</p>

<p>Chúng tôi rất mong nhận được đánh giá của bạn về chuyến đi để cải thiện chất lượng dịch vụ.</p>

<p><a href="{{review_link}}" style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 5px;">Đánh giá ngay</a></p>

<p>Trân trọng,<br>
Đội ngũ Tour Manager</p>',
                'variables' => ['customer_name', 'tour_name', 'review_link'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
