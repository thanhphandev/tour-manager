<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        $destinations = [
            [
                'name' => 'Vịnh Hạ Long',
                'slug' => 'vinh-ha-long',
                'description' => 'Một kỳ quan thiên nhiên thế giới với hàng ngàn hòn đảo đá vôi lớn nhỏ nổi bật trên mặt biển xanh ngắt.',
                'is_active' => true,
            ],
            [
                'name' => 'Đà Lạt',
                'slug'=> 'da-lat',
                'description' => 'Một thành phố núi nổi tiếng với các biệt thự phong cách Pháp, vườn hoa và khí hậu mát mẻ.',
                'is_active' => true,
            ],
            [
                'name' => 'Hội An',
                'slug'=> 'hoi-an',
                'description' => 'Một thị trấn cổ với kiến trúc được bảo tồn tốt, lễ hội đèn lồng và các nghề thủ công truyền thống.',
                'is_active' => true,
            ],
            [
                'name' => 'Nha Trang',
                'slug'=> 'nha-trang',
                'description' => 'Một thành phố ven biển nổi tiếng với các bãi biển, cơ hội lặn biển và Tháp Chàm Po Nagar nổi tiếng.',
                'is_active' => true,
            ],
            [
                'name' => 'Sapa',
                'slug'=> 'sapa',
                'description' => 'Một thị trấn núi ở dãy Hoàng Liên Sơn, nổi tiếng với các ruộng bậc thang và các làng dân tộc thiểu số.',
                'is_active' => true,
            ],
        ];

        foreach ($destinations as $destinationData) {
            Destination::create($destinationData);
        }
    }
}