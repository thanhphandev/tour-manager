"""
Image Service - Lấy ảnh từ Google Images cho các địa điểm du lịch
Sử dụng web scraping để lấy URLs ảnh chất lượng cao
"""
import os
import re
import time
import json
import requests
from pathlib import Path
from typing import List, Optional, Dict, Any
from urllib.parse import quote_plus, urlparse, unquote
from bs4 import BeautifulSoup

from config import RATE_LIMIT, GOOGLE_API_KEY, GOOGLE_CX

class GoogleImageScraper:
    """Scraper lấy ảnh thực từ Google Images (API + Scraping fallback)"""
    
    def __init__(self):
        self.session = requests.Session()
        self.last_request_time = 0
        
        # Headers giả lập browser Chrome thật
        self.headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        }
    
    def _rate_limit(self, delay: float = None):
        """Đảm bảo rate limit"""
        delay = delay or RATE_LIMIT['image_delay']
        elapsed = time.time() - self.last_request_time
        if elapsed < delay:
            time.sleep(delay - elapsed)
        self.last_request_time = time.time()
    
    def search_images(self, query: str, num_images: int = 10) -> List[str]:
        """
        Tìm kiếm ảnh từ Google Images
        Ưu tiên dùng API nếu có key -> Chính xác 100%
        Fallback về scraping nếu không có key
        """
        all_urls = []
        
        # 1. Thử dùng Google Custom Search API (Chính xác nhất)
        if GOOGLE_API_KEY and GOOGLE_CX:
            print(f"   🚀 Sử dụng Google Custom Search API...")
            api_urls = self._search_google_api(query, num_images)
            all_urls.extend(api_urls)
            
            if len(all_urls) >= num_images:
                return all_urls[:num_images]
        else:
            print(f"   ⚠️ Không có Google API Key, dùng Scraping (kém chính xác hơn)...")

        # 2. Fallback: Google Images Scraping
        if len(all_urls) < num_images:
            urls = self._search_google_images(query, num_images - len(all_urls))
            all_urls.extend(urls)
        
        # 3. Fallback 2: Bing Images
        if len(all_urls) < num_images:
            print(f"   📷 Thử Bing Images...")
            bing_urls = self._search_bing_images(query, num_images - len(all_urls))
            all_urls.extend(bing_urls)
        
        unique_urls = list(dict.fromkeys(all_urls))
        print(f"   ✓ Tổng cộng: {len(unique_urls)} ảnh")
        return unique_urls[:num_images]

    def _search_google_api(self, query: str, num_images: int) -> List[str]:
        """Tìm kiếm ảnh qua Google Custom Search API"""
        image_urls = []
        start_index = 1
        
        while len(image_urls) < num_images:
            try:
                self._rate_limit(RATE_LIMIT.get('google_delay', 1.0))
                
                # Google CSE API: https://developers.google.com/custom-search/v1/reference/rest/v1/cse/list
                url = "https://www.googleapis.com/customsearch/v1"
                params = {
                    'key': GOOGLE_API_KEY,
                    'cx': GOOGLE_CX,
                    'q': query,
                    'searchType': 'image',
                    'imgSize': 'large',  # Lấy ảnh lớn
                    'imgType': 'photo',  # Chỉ lấy ảnh chụp
                    'num': min(10, num_images - len(image_urls)), # Max 10 per request
                    'start': start_index,
                    'gl': 'vn',  # Định vị Việt Nam
                    'hl': 'vi',
                }
                
                response = self.session.get(url, params=params, timeout=10)
                
                if response.status_code == 429:
                    print("   ❌ Google API: Hết quota hoặc rate limit")
                    break
                    
                response.raise_for_status()
                data = response.json()
                
                if 'items' not in data:
                    print("   ⚠️ Google API: Không tìm thấy thêm kết quả")
                    break
                    
                for item in data['items']:
                    link = item.get('link')
                    if link and self._is_valid_image_url(link):
                        image_urls.append(link)
                
                start_index += len(data.get('items', []))
                
                # Giới hạn safety
                if start_index > 50: 
                    break
                    
            except Exception as e:
                print(f"   ⚠️ Lỗi Google API: {e}")
                break
        
        print(f"   ✅ Google API: tìm thấy {len(image_urls)} ảnh")
        return image_urls
    
    def _search_google_images(self, query: str, num_images: int = 10) -> List[str]:
        """Tìm kiếm ảnh từ Google Images"""
        self._rate_limit(2)  # Google cần delay lâu hơn
        
        # Thêm từ khóa để có ảnh đẹp hơn
        search_query = f"{query} landscape travel photography"
        encoded_query = quote_plus(search_query)
        
        # Sử dụng tbm=isch cho image search, tbs=isz:l cho ảnh lớn
        url = f"https://www.google.com/search?q={encoded_query}&tbm=isch&hl=vi&tbs=isz:l"
        
        try:
            response = self.session.get(url, headers=self.headers, timeout=15)
            response.raise_for_status()
            
            image_urls = []
            
            # Phương pháp 1: Tìm URLs trong JSON data
            # Google nhúng ảnh full-res trong các script tags
            patterns = [
                r'\["(https?://[^"]+\.(?:jpg|jpeg|png|webp))",\d+,\d+\]',
                r'"ou":"(https?://[^"]+)"',
                r'"tu":"(https?://[^"]+)"',
            ]
            
            for pattern in patterns:
                matches = re.findall(pattern, response.text, re.IGNORECASE)
                for match in matches:
                    if self._is_valid_image_url(match):
                        image_urls.append(match)
            
            # Phương pháp 2: Parse HTML
            soup = BeautifulSoup(response.text, 'html.parser')
            
            # Tìm trong data-src
            for img in soup.find_all('img'):
                for attr in ['data-src', 'data-iurl', 'src']:
                    src = img.get(attr, '')
                    if src and self._is_valid_image_url(src):
                        image_urls.append(src)
            
            # Loại bỏ duplicates giữ thứ tự
            unique_urls = list(dict.fromkeys(image_urls))
            
            print(f"   🔍 Google: {len(unique_urls)} ảnh tìm thấy")
            return unique_urls[:num_images]
            
        except Exception as e:
            print(f"   ⚠️ Lỗi Google Images: {e}")
            return []
    
    def _search_bing_images(self, query: str, num_images: int = 10) -> List[str]:
        """Tìm kiếm ảnh từ Bing Images (backup)"""
        self._rate_limit(1.5)
        
        search_query = f"{query} landscape"
        encoded_query = quote_plus(search_query)
        
        url = f"https://www.bing.com/images/search?q={encoded_query}&form=HDRSC2&first=1"
        
        try:
            response = self.session.get(url, headers=self.headers, timeout=15)
            response.raise_for_status()
            
            image_urls = []
            
            # Bing dùng attribute m với JSON chứa URL
            soup = BeautifulSoup(response.text, 'html.parser')
            
            for a_tag in soup.find_all('a', class_='iusc'):
                m_attr = a_tag.get('m', '')
                if m_attr:
                    try:
                        data = json.loads(m_attr)
                        murl = data.get('murl', '')
                        if murl and self._is_valid_image_url(murl):
                            image_urls.append(murl)
                    except:
                        pass
            
            # Backup: tìm trong img tags
            for img in soup.find_all('img'):
                src = img.get('src', '') or img.get('data-src', '')
                if src and self._is_valid_image_url(src):
                    image_urls.append(src)
            
            unique_urls = list(dict.fromkeys(image_urls))
            print(f"   🔍 Bing: {len(unique_urls)} ảnh tìm thấy")
            return unique_urls[:num_images]
            
        except Exception as e:
            print(f"   ⚠️ Lỗi Bing Images: {e}")
            return []
    
    def _is_valid_image_url(self, url: str) -> bool:
        """Kiểm tra URL ảnh hợp lệ"""
        if not url or not url.startswith('http'):
            return False
        
        try:
            parsed = urlparse(url)
            
            # Loại bỏ các domain không mong muốn
            blocked_patterns = [
                'gstatic.com',
                'google.com', 
                'googleusercontent.com',
                'bing.com/th',
                'favicon',
                'logo',
                'icon',
                'pixel',
                'tracking',
                'advertisement',
                '1x1',
                'blank',
                'spacer',
            ]
            
            url_lower = url.lower()
            for blocked in blocked_patterns:
                if blocked in url_lower:
                    return False
            
            # Kiểm tra có phải ảnh không
            valid_extensions = ['.jpg', '.jpeg', '.png', '.webp']
            path_lower = parsed.path.lower()
            
            # Một số URL không có extension nhưng vẫn là ảnh
            if any(ext in path_lower for ext in valid_extensions):
                return True
            
            # Kiểm tra query string
            if any(ext in parsed.query.lower() for ext in valid_extensions):
                return True
            
            # Các CDN phổ biến
            trusted_domains = [
                'istockphoto.com',
                'unsplash.com',
                'pexels.com',
                'shutterstock.com',
                'dreamstime.com',
                'depositphotos.com',
                'gettyimages.com',
                'freepik.com',
                'tripadvisor.com',
                'vinpearl.com',
                'vietnamtourism',
                'travel',
                'cloudinary',
                'imgix',
                'cdn',
            ]
            
            if any(domain in parsed.netloc.lower() for domain in trusted_domains):
                return True
            
            return False
            
        except:
            return False
    
    def validate_image_url(self, url: str, timeout: int = 5) -> bool:
        """Kiểm tra URL ảnh có thể truy cập được không"""
        try:
            self._rate_limit(0.5)
            
            response = self.session.head(
                url, 
                headers=self.headers, 
                timeout=timeout, 
                allow_redirects=True
            )
            
            if response.status_code != 200:
                return False
            
            content_type = response.headers.get('Content-Type', '')
            return 'image' in content_type
            
        except:
            return False
    
    def get_validated_images(self, query: str, num_images: int = 5) -> List[str]:
        """Lấy và validate ảnh, chỉ trả về các URL hoạt động"""
        print(f"   🔍 Đang tìm ảnh cho: {query}")
        
        # Lấy nhiều hơn cần để phòng lọc bớt
        raw_urls = self.search_images(query, num_images * 3)
        
        if not raw_urls:
            return []
        
        valid_urls = []
        print(f"   🔄 Đang validate {len(raw_urls)} URLs...")
        
        for url in raw_urls:
            if len(valid_urls) >= num_images:
                break
            
            if self.validate_image_url(url):
                valid_urls.append(url)
                print(f"      ✓ {url[:60]}...")
            else:
                pass  # Silent fail
        
        print(f"   ✅ {len(valid_urls)}/{num_images} ảnh hợp lệ")
        return valid_urls


class ImageService:
    """Service wrapper cho việc lấy ảnh"""
    
    def __init__(self):
        self.scraper = GoogleImageScraper()
    
    def get_high_quality_images(self, location_name: str, 
                                 num_images: int = 5,
                                 validate: bool = True) -> List[str]:
        """
        Lấy ảnh chất lượng cao cho địa điểm
        
        Args:
            location_name: Tên địa điểm
            num_images: Số lượng ảnh
            validate: Có validate URLs không (chậm hơn nhưng chắc chắn)
        """
        if validate:
            return self.scraper.get_validated_images(location_name, num_images)
        else:
            return self.scraper.search_images(location_name, num_images)


# Fallback service đã bị loại bỏ vì URLs cũ không hoạt động
# Singleton instance
image_service = ImageService()
