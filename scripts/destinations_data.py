"""
Destination Data - Danh sách các điểm đến du lịch Việt Nam
Chỉ chứa thông tin cơ bản, ảnh sẽ được tự động lấy từ Google Images
"""

# Danh sách 20+ điểm đến du lịch nổi tiếng Việt Nam
VIETNAM_DESTINATIONS = [
    # Miền Bắc
    {'name': 'Vịnh Hạ Long', 'slug': 'vinh-ha-long', 'region': 'Miền Bắc', 'search_keywords': 'Vịnh Hạ Long Quảng Ninh cảnh đẹp'},
    {'name': 'Sapa', 'slug': 'sapa', 'region': 'Miền Bắc', 'search_keywords': 'Sapa Lào Cai ruộng bậc thang'},
    {'name': 'Hà Nội', 'slug': 'ha-noi', 'region': 'Miền Bắc', 'search_keywords': 'Hà Nội Hồ Gươm phố cổ'},
    {'name': 'Ninh Bình', 'slug': 'ninh-binh', 'region': 'Miền Bắc', 'search_keywords': 'Ninh Bình Tràng An Tam Cốc'},
    {'name': 'Mai Châu', 'slug': 'mai-chau', 'region': 'Miền Bắc', 'search_keywords': 'Mai Châu Hòa Bình thung lũng'},
    {'name': 'Hà Giang', 'slug': 'ha-giang', 'region': 'Miền Bắc', 'search_keywords': 'Hà Giang đèo Mã Pí Lèng'},
    {'name': 'Mộc Châu', 'slug': 'moc-chau', 'region': 'Miền Bắc', 'search_keywords': 'Mộc Châu Sơn La đồi chè'},
    
    # Miền Trung
    {'name': 'Cố đô Huế', 'slug': 'hue', 'region': 'Miền Trung', 'search_keywords': 'Huế Đại Nội hoàng thành'},
    {'name': 'Đà Nẵng', 'slug': 'da-nang', 'region': 'Miền Trung', 'search_keywords': 'Đà Nẵng Cầu Rồng Mỹ Khê'},
    {'name': 'Hội An', 'slug': 'hoi-an', 'region': 'Miền Trung', 'search_keywords': 'Hội An phố cổ đèn lồng'},
    {'name': 'Nha Trang', 'slug': 'nha-trang', 'region': 'Miền Trung', 'search_keywords': 'Nha Trang biển đẹp Vinpearl'},
    {'name': 'Quy Nhơn', 'slug': 'quy-nhon', 'region': 'Miền Trung', 'search_keywords': 'Quy Nhơn Eo Gió Kỳ Co'},
    {'name': 'Phú Yên', 'slug': 'phu-yen', 'region': 'Miền Trung', 'search_keywords': 'Phú Yên Ghềnh Đá Đĩa'},
    {'name': 'Đảo Lý Sơn', 'slug': 'ly-son', 'region': 'Miền Trung', 'search_keywords': 'Lý Sơn Quảng Ngãi đảo'},
    
    # Tây Nguyên
    {'name': 'Đà Lạt', 'slug': 'da-lat', 'region': 'Tây Nguyên', 'search_keywords': 'Đà Lạt thành phố ngàn hoa'},
    {'name': 'Buôn Ma Thuột', 'slug': 'buon-ma-thuot', 'region': 'Tây Nguyên', 'search_keywords': 'Buôn Ma Thuột cà phê thác'},
    {'name': 'Pleiku', 'slug': 'pleiku', 'region': 'Tây Nguyên', 'search_keywords': 'Pleiku Gia Lai Biển Hồ'},
    
    # Miền Nam  
    {'name': 'TP. Hồ Chí Minh', 'slug': 'tp-ho-chi-minh', 'region': 'Miền Nam', 'search_keywords': 'Sài Gòn TP Hồ Chí Minh Landmark 81'},
    {'name': 'Phú Quốc', 'slug': 'phu-quoc', 'region': 'Miền Nam', 'search_keywords': 'Phú Quốc đảo ngọc biển'},
    {'name': 'Côn Đảo', 'slug': 'con-dao', 'region': 'Miền Nam', 'search_keywords': 'Côn Đảo Bà Rịa biển'},
    {'name': 'Vũng Tàu', 'slug': 'vung-tau', 'region': 'Miền Nam', 'search_keywords': 'Vũng Tàu biển Bãi Sau'},
    {'name': 'Cần Thơ', 'slug': 'can-tho', 'region': 'Miền Nam', 'search_keywords': 'Cần Thơ chợ nổi Cái Răng'},
    {'name': 'Long Xuyên', 'slug': 'long-xuyen', 'region': 'Miền Nam', 'search_keywords': 'Long Xuyên An Giang chợ nổi'},
    {'name': 'Mũi Né', 'slug': 'mui-ne', 'region': 'Miền Nam', 'search_keywords': 'Mũi Né Phan Thiết đồi cát'},
    {'name': 'Châu Đốc', 'slug': 'chau-doc', 'region': 'Miền Nam', 'search_keywords': 'Châu Đốc An Giang núi Sam'},
]


def get_destination_by_slug(slug: str) -> dict:
    """Lấy thông tin destination theo slug"""
    for dest in VIETNAM_DESTINATIONS:
        if dest['slug'] == slug:
            return dest
    return None


def get_all_destinations() -> list:
    """Lấy tất cả destinations"""
    return VIETNAM_DESTINATIONS


def get_destinations_by_region(region: str) -> list:
    """Lấy destinations theo vùng miền"""
    return [d for d in VIETNAM_DESTINATIONS if d['region'] == region]
