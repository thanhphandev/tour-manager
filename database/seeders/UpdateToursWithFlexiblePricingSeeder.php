<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;

class UpdateToursWithFlexiblePricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Cập nhật giá cho tours hiện có theo quy tắc:
     * - Người lớn (Adults): 100% giá gốc
     * - Trẻ em (Children 2-11 tuổi): 70% giá gốc
     * - Em bé (Infants <2 tuổi): 20% giá gốc (hoặc miễn phí)
     */
    public function run(): void
    {
        // Lấy tất cả tours và cập nhật giá
        Tour::chunk(100, function ($tours) {
            foreach ($tours as $tour) {
                // Giả sử price cũ là giá người lớn
                $basePrice = $tour->price ?? 1000000; // Fallback nếu không có giá
                
                $tour->update([
                    'price_adult' => $basePrice,                    // 100% - Người lớn
                    'price_child' => $basePrice * 0.7,             // 70% - Trẻ em
                    'price_infant' => $basePrice * 0.2,            // 20% - Em bé (hoặc set = 0 nếu muốn miễn phí)
                    'duration_nights' => max(0, $tour->duration_days - 1), // Tính số đêm
                ]);
            }
        });
        
        $this->command->info('✅ Updated all tours with flexible pricing successfully!');
        $this->command->info('   - Adults: 100% of base price');
        $this->command->info('   - Children: 70% of base price');
        $this->command->info('   - Infants: 20% of base price');
    }
}

