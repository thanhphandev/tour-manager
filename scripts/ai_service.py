"""
AI Content Service - Sử dụng Groq API để tạo nội dung tour du lịch
"""
import time
import json
import re
from typing import Dict, Any, Optional, List
from groq import Groq

from config import GROQ_API_KEY, GROQ_MODEL, RATE_LIMIT


class AIContentService:
    """Service tạo nội dung tour du lịch bằng AI"""
    
    def __init__(self):
        if not GROQ_API_KEY:
            raise ValueError("GROQ_API_KEY chưa được cấu hình!")
        
        self.client = Groq(api_key=GROQ_API_KEY)
        self.model = GROQ_MODEL
        self.last_request_time = 0
    
    def _rate_limit(self):
        """Đảm bảo rate limit giữa các request"""
        elapsed = time.time() - self.last_request_time
        if elapsed < RATE_LIMIT['groq_delay']:
            sleep_time = RATE_LIMIT['groq_delay'] - elapsed
            time.sleep(sleep_time)
        self.last_request_time = time.time()
    
    def _call_api(self, prompt: str, max_tokens: int = 2000) -> Optional[str]:
        """Gọi Groq API với retry logic"""
        for attempt in range(RATE_LIMIT['max_retries']):
            try:
                self._rate_limit()
                
                response = self.client.chat.completions.create(
                    model=self.model,
                    messages=[
                        {
                            "role": "system",
                            "content": "Bạn là chuyên gia viết content du lịch chuyên nghiệp tại Việt Nam. Viết nội dung bằng tiếng Việt, hấp dẫn, chính xác và thu hút khách du lịch."
                        },
                        {
                            "role": "user", 
                            "content": prompt
                        }
                    ],
                    max_tokens=max_tokens,
                    temperature=0.7,
                )
                
                return response.choices[0].message.content
                
            except Exception as e:
                print(f"⚠️ Lỗi API (lần {attempt + 1}): {e}")
                if attempt < RATE_LIMIT['max_retries'] - 1:
                    time.sleep(RATE_LIMIT['retry_delay'])
                else:
                    print("❌ Đã hết số lần retry")
                    return None
        
        return None
    
    def generate_destination_description(self, destination_name: str) -> Optional[str]:
        """Tạo mô tả chuyên nghiệp cho destination"""
        prompt = f"""Viết mô tả hấp dẫn cho điểm đến du lịch: {destination_name}

Yêu cầu:
- 150-250 ký tự (ngắn gọn, súc tích)
- Nhấn mạnh đặc điểm nổi bật, độc đáo
- Gợi cảm xúc, thu hút du khách
- Giọng văn chuyên nghiệp, đáng tin cậy
- Không dùng dấu ngoặc kép
- CHỈ TRẢ VỀ mô tả, không giải thích

Ví dụ cho Vịnh Hạ Long:
Kỳ quan thiên nhiên thế giới với hàng ngàn đảo đá vôi hùng vĩ, hang động huyền bí và vịnh biển xanh ngọc bích."""

        result = self._call_api(prompt, max_tokens=200)
        if result:
            result = result.strip().strip('"\'')
        return result

    def generate_tour_name(self, destination_name: str, duration_days: int, 
                           tour_type: str = "khám phá") -> Optional[str]:
        """Tạo tên tour hấp dẫn"""
        prompt = f"""Tạo 1 tên tour du lịch hấp dẫn, chuyên nghiệp cho:
- Điểm đến: {destination_name}
- Thời gian: {duration_days} ngày {duration_days - 1} đêm
- Loại tour: {tour_type}

Yêu cầu:
- Tên ngắn gọn (tối đa 60 ký tự)
- Gây ấn tượng, thu hút
- Không dùng dấu ngoặc kép
- Chỉ trả về tên tour, không giải thích

Ví dụ: Khám Phá Vịnh Hạ Long 3N2Đ - Du Thuyền 5 Sao"""

        result = self._call_api(prompt, max_tokens=100)
        if result:
            # Clean up response
            result = result.strip().strip('"\'')
            result = result.split('\n')[0]  # Lấy dòng đầu tiên
        return result
    
    def generate_short_description(self, destination_name: str, 
                                   tour_name: str) -> Optional[str]:
        """Tạo mô tả ngắn (150-200 ký tự)"""
        prompt = f"""Viết mô tả ngắn gọn cho tour:
- Tour: {tour_name}
- Điểm đến: {destination_name}

Yêu cầu:
- 150-200 ký tự
- Hấp dẫn, gợi cảm xúc
- Nhấn mạnh điểm nổi bật
- Không dùng dấu ngoặc kép
- Chỉ trả về mô tả, không giải thích"""

        result = self._call_api(prompt, max_tokens=150)
        if result:
            result = result.strip().strip('"\'')
        return result
    
    def generate_full_description(self, destination_name: str, tour_name: str,
                                  duration_days: int) -> Optional[str]:
        """Tạo mô tả chi tiết (Markdown, 400-600 từ)"""
        prompt = f"""Viết mô tả chi tiết cho tour du lịch:
- Tour: {tour_name}  
- Điểm đến: {destination_name}
- Thời gian: {duration_days} ngày {duration_days - 1} đêm

Yêu cầu format Markdown:
1. Đoạn mở đầu giới thiệu (2-3 câu hấp dẫn)

2. ## Điểm Nổi Bật
- 4-5 bullet points về trải nghiệm đặc biệt

3. ## Trải Nghiệm Độc Đáo  
- Mô tả chi tiết 2-3 hoạt động đặc sắc

4. ## Phù Hợp Với
- Đối tượng khách hàng phù hợp

5. ## Thông Tin Thêm
- Lưu ý quan trọng cho du khách

Độ dài: 400-600 từ
Giọng văn: Chuyên nghiệp, hấp dẫn, đáng tin cậy"""

        return self._call_api(prompt, max_tokens=1500)
    
    def generate_itinerary(self, destination_name: str, tour_name: str,
                           duration_days: int) -> Optional[str]:
        """Tạo lịch trình chi tiết (Markdown)"""
        prompt = f"""Tạo lịch trình chi tiết cho tour:
- Tour: {tour_name}
- Điểm đến: {destination_name}  
- Thời gian: {duration_days} ngày {duration_days - 1} đêm

Format Markdown cho mỗi ngày:

## Ngày 1: [Tiêu đề ngắn gọn]

| Thời gian | Hoạt động |
|-----------|-----------|
| 06:00 | Đón khách tại điểm hẹn |
| 08:00 | Khởi hành đến {destination_name} |
| ... | ... |

**Bữa ăn:** Sáng (tự túc) | Trưa (nhà hàng) | Tối (buffet)

**Lưu trú:** Khách sạn 4 sao

---

Yêu cầu:
- Thời gian cụ thể, chi tiết
- Mỗi ngày có 6-8 hoạt động
- Bao gồm bữa ăn và nơi lưu trú
- Địa điểm tham quan phải THỰC TẾ và CHÍNH XÁC với {destination_name}
- Kết thúc ngày cuối với hoạt động chia tay"""

        return self._call_api(prompt, max_tokens=2000)
    
    def generate_tour_data(self, destination: Dict[str, Any], 
                           tour_index: int) -> Optional[Dict[str, Any]]:
        """Tạo toàn bộ dữ liệu cho 1 tour"""
        dest_name = destination['name']
        
        # Các loại tour và thời gian đa dạng
        tour_types = [
            ("khám phá", 3),
            ("trải nghiệm cao cấp", 4),
            ("phiêu lưu", 2),
            ("nghỉ dưỡng", 5),
            ("văn hóa ẩm thực", 3),
        ]
        
        tour_type, duration = tour_types[tour_index % len(tour_types)]
        
        print(f"  📝 Đang tạo tour {tour_index + 1}: {tour_type} ({duration}N{duration-1}Đ)...")
        
        # 1. Tạo tên tour
        name = self.generate_tour_name(dest_name, duration, tour_type)
        if not name:
            return None
        print(f"    ✓ Tên: {name}")
        
        # 2. Tạo mô tả ngắn
        short_desc = self.generate_short_description(dest_name, name)
        if not short_desc:
            return None
        print(f"    ✓ Mô tả ngắn: {len(short_desc)} ký tự")
        
        # 3. Tạo mô tả chi tiết
        full_desc = self.generate_full_description(dest_name, name, duration)
        if not full_desc:
            return None
        print(f"    ✓ Mô tả chi tiết: {len(full_desc)} ký tự")
        
        # 4. Tạo lịch trình
        itinerary = self.generate_itinerary(dest_name, name, duration)
        if not itinerary:
            return None
        print(f"    ✓ Lịch trình: {len(itinerary)} ký tự")
        
        # 5. Tính giá (dựa trên thời gian và loại tour)
        base_price = 2500000  # 2.5 triệu VND base
        price_adult = base_price * duration
        if "cao cấp" in tour_type:
            price_adult *= 1.5
        elif "nghỉ dưỡng" in tour_type:
            price_adult *= 1.3
        
        # Tạo slug từ tên tour
        from slugify import slugify
        slug = slugify(name)
        
        return {
            'destination_id': destination['id'],
            'name': name,
            'slug': slug,
            'short_description': short_desc,
            'full_description': full_desc,
            'itinerary': itinerary,
            'price_adult': int(price_adult),
            'price_child': int(price_adult * 0.7),
            'price_infant': int(price_adult * 0.3),
            'duration_days': duration,
            'duration_nights': duration - 1,
            'max_people': 20,
            'status': 'active',
            'featured': tour_index == 0,  # Tour đầu tiên là featured
        }
    
    def generate_reviews(self, tour_name: str, destination_name: str,
                         tour_context: str = "", count: int = 5) -> List[Dict[str, Any]]:
        """Tạo reviews cho tour với nội dung chính xác và thân thiện"""
        
        context_prompt = ""
        if tour_context:
            context_prompt = f"\nThông tin tour để review chính xác:\n{tour_context}\n"
            
        prompt = f"""Bạn đóng vai những khách hàng đã trải nghiệm tour du lịch thực tế.
Hãy viết {count} đánh giá (review) cho tour sau:
- Tên tour: {tour_name}
- Điểm đến: {destination_name}
{context_prompt}

Yêu cầu về nội dung (QUAN TRỌNG):
1. **Tính chân thực & Chính xác**: 
   - Nhắc đến các địa điểm, hoạt động cụ thể có trong thông tin tour.
   - Không chém gió chung chung (ví dụ: thay vì "cảnh đẹp", hãy nói "Hang Sửng Sốt thực sự choáng ngợp").
   
2. **Giọng văn Thân thiện & Gần gũi**:
   - Dùng ngôn ngữ tự nhiên của người Việt (có thể dùng từ cảm thán: wow, ôi, mê lắm, ...).
   - Đa dạng phong cách: Có người thích chụp ảnh, có gia đình đi nghỉ dưỡng, có cặp đôi trăng mật...
   - Rating chủ yếu 5 sao, thi thoảng 4 sao nếu có góp ý nhỏ (nhưng vẫn tích cực).

3. **Format trả về**: JSON Array
[
  {{
    "rating": 5,
    "user_name": "Tên Người Dùng",
    "title": "Tiêu đề review ngắn",
    "comment": "Nội dung review (60-120 từ)"
  }},
  ...
]

CHỈ TRẢ VỀ JSON ARRAY, không thêm text dẫn dắt."""

        result = self._call_api(prompt, max_tokens=1000)
        if not result:
            return []
        
        try:
            # Parse JSON từ response
            # Tìm JSON array trong response
            json_match = re.search(r'\[[\s\S]*\]', result)
            if json_match:
                reviews = json.loads(json_match.group())
                return reviews
        except json.JSONDecodeError as e:
            print(f"⚠️ Lỗi parse JSON reviews: {e}")
        
        return []


# Singleton instance
ai_service = AIContentService() if GROQ_API_KEY else None
