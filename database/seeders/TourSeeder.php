<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Destination;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Round prices into beautiful VND numbers (multiple of 50,000)
     */
    private function niceVndPrice($amount)
    {
        $rounded = round($amount / 50000) * 50000;
        return max(50000, $rounded);
    }

    public function run()
    {
        $faker = Faker::create('vi_VN');
        $destinations = Destination::all();

        if ($destinations->isEmpty()) {
            $this->command->warn('⚠️ No destinations found. Please run DestinationSeeder first.');
            return;
        }

        $tourTemplates = [
            [
                'name' => 'Vịnh Hạ Long 2 Ngày 1 Đêm',
                'short_description' => 'Trải nghiệm du thuyền sang trọng qua vịnh Hạ Long với cảnh sắc kỳ vĩ.',
                'full_description' => 'Tham gia hành trình 2 ngày khám phá kỳ quan thiên nhiên thế giới. Trải nghiệm du thuyền, chèo kayak, và khám phá hang động kỳ bí.',
                'itinerary' => "Ngày 1:\n- 12:00: Lên tàu tại cảng Tuần Châu\n- 13:00: Ăn trưa trên tàu\n- 15:00: Tham quan Hang Thiên Cung\n- 19:00: Dùng bữa tối\n\nNgày 2:\n- 6:00: Ngắm bình minh\n- 9:00: Trả phòng tàu\n- 11:00: Trở về bến cảng",
                'base_price' => 2500000,
                'duration_days' => 2,
                'max_people' => 20,
            ],
            [
                'name' => 'Khám phá Đà Lạt - Thành phố ngàn hoa',
                'short_description' => 'Chiêm ngưỡng vẻ đẹp Đà Lạt, khí hậu mát mẻ quanh năm.',
                'full_description' => 'Khám phá thành phố Đà Lạt lãng mạn với những vườn hoa rực rỡ, biệt thự cổ, và hồ Xuân Hương yên bình.',
                'itinerary' => "Ngày 1:\n- 8:00: Đón khách tại khách sạn\n- 9:00: Tham quan Vườn hoa thành phố\n- 15:00: Ghé thăm Crazy House\n- 19:00: Dạo chợ đêm Đà Lạt\n\nNgày 2:\n- 8:00: Tham quan Thiền viện Trúc Lâm\n- 11:00: Ăn trưa và kết thúc tour",
                'base_price' => 1800000,
                'duration_days' => 2,
                'max_people' => 15,
            ],
            [
                'name' => 'Hội An Cổ Kính & Văn Hóa',
                'short_description' => 'Khám phá phố cổ Hội An - di sản văn hóa thế giới.',
                'full_description' => 'Đi dạo qua các con phố đèn lồng, tham quan nhà cổ, chùa cầu Nhật Bản, và lớp học làm đèn truyền thống.',
                'itinerary' => "Ngày 1:\n- 9:00: Tham quan phố cổ\n- 11:00: Chùa Cầu Nhật Bản\n- 16:00: Làm đèn lồng thủ công\n\nNgày 2:\n- 8:00: Thăm Thánh địa Mỹ Sơn\n- 15:00: Trở lại Hội An\n- 19:00: Ăn tối và kết thúc tour",
                'base_price' => 2200000,
                'duration_days' => 2,
                'max_people' => 12,
            ],
        ];

        foreach ($destinations as $destination) {
            for ($i = 0; $i < rand(2, 4); $i++) {

                $template = $faker->randomElement($tourTemplates);
                $city = $faker->city();

                // Generate unique slug
                $baseSlug = Str::slug($template['name'] . ' - ' . $city);
                $slug = $baseSlug;
                $counter = 1;

                while (Tour::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }

                // Apply random price variation (+/-)
                $variation = rand(-200000, 400000);
                $priceAdult = $this->niceVndPrice($template['base_price'] + $variation);

                $priceChild  = $this->niceVndPrice($priceAdult * 0.7);
                $priceInfant = $this->niceVndPrice($priceAdult * 0.2);

                Tour::create([
                    'destination_id'    => $destination->id,
                    'name'              => $template['name'] . ' - ' . $city,
                    'slug'              => $slug,
                    'short_description' => $template['short_description'],
                    'full_description'  => $template['full_description'] . ' ' . $faker->paragraph(),
                    'itinerary'         => $template['itinerary'],
                    'price_adult'       => $priceAdult,
                    'price_child'       => $priceChild,
                    'price_infant'      => $priceInfant,
                    'duration_days'     => $template['duration_days'],
                    'duration_nights'   => $template['duration_days'] - 1,
                    'max_people'        => $template['max_people'],
                    'status'            => $faker->randomElement(['active', 'inactive']),
                    'featured'          => $faker->boolean(60),
                ]);
            }
        }

        $this->command->info('✅ TourSeeder: ' . Tour::count() . ' tours created successfully.');
    }
}
