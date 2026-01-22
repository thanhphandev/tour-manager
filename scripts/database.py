"""
Database module - Kết nối và thao tác với MySQL Railway
"""
import mysql.connector
from mysql.connector import Error
from contextlib import contextmanager
from typing import Optional, List, Dict, Any
import time

from config import DB_CONFIG


class Database:
    """Class quản lý kết nối database an toàn"""
    
    def __init__(self):
        self.connection: Optional[mysql.connector.MySQLConnection] = None
        self._connected = False
    
    def connect(self) -> bool:
        """Kết nối đến database"""
        try:
            print(f"🔗 Đang kết nối đến {DB_CONFIG['host']}:{DB_CONFIG['port']}...")
            
            self.connection = mysql.connector.connect(**DB_CONFIG)
            
            if self.connection.is_connected():
                db_info = self.connection.get_server_info()
                print(f"✅ Kết nối thành công! MySQL Server version: {db_info}")
                self._connected = True
                return True
                
        except Error as e:
            print(f"❌ Lỗi kết nối database: {e}")
            return False
    
    def disconnect(self):
        """Đóng kết nối database"""
        if self.connection and self.connection.is_connected():
            self.connection.close()
            self._connected = False
            print("🔌 Đã đóng kết nối database")
    
    def is_connected(self) -> bool:
        """Kiểm tra trạng thái kết nối"""
        return self._connected and self.connection and self.connection.is_connected()
    
    @contextmanager
    def cursor(self, dictionary: bool = True):
        """Context manager cho cursor (tự động đóng sau khi dùng)"""
        if not self.is_connected():
            raise ConnectionError("Database chưa được kết nối")
        
        cursor = self.connection.cursor(dictionary=dictionary)
        try:
            yield cursor
        finally:
            cursor.close()
    
    def commit(self):
        """Commit transaction"""
        if self.connection:
            self.connection.commit()
            print("💾 Đã commit thay đổi vào database")
    
    def rollback(self):
        """Rollback transaction"""
        if self.connection:
            self.connection.rollback()
            print("↩️ Đã rollback thay đổi")
    
    # ==================== DESTINATION OPERATIONS ====================
    
    def get_all_destinations(self) -> List[Dict[str, Any]]:
        """Lấy tất cả destinations"""
        with self.cursor() as cursor:
            cursor.execute("""
                SELECT id, name, slug, description, image, is_active
                FROM destinations
                WHERE is_active = 1
                ORDER BY id
            """)
            return cursor.fetchall()
    
    def get_destination_by_slug(self, slug: str) -> Optional[Dict[str, Any]]:
        """Lấy destination theo slug"""
        with self.cursor() as cursor:
            cursor.execute("""
                SELECT id, name, slug, description, image, is_active
                FROM destinations
                WHERE slug = %s
            """, (slug,))
            return cursor.fetchone()
    
    def update_destination(self, dest_id: int, data: Dict[str, Any]) -> bool:
        """Cập nhật destination"""
        try:
            with self.cursor() as cursor:
                set_clause = ', '.join([f"{k} = %s" for k in data.keys()])
                query = f"UPDATE destinations SET {set_clause} WHERE id = %s"
                values = list(data.values()) + [dest_id]
                cursor.execute(query, values)
                return True
        except Error as e:
            print(f"❌ Lỗi cập nhật destination: {e}")
            return False
    
    def create_destination(self, data: Dict[str, Any]) -> Optional[int]:
        """Tạo destination mới, trả về ID"""
        try:
            with self.cursor() as cursor:
                columns = ', '.join(data.keys())
                placeholders = ', '.join(['%s'] * len(data))
                query = f"INSERT INTO destinations ({columns}) VALUES ({placeholders})"
                cursor.execute(query, list(data.values()))
                return cursor.lastrowid
        except Error as e:
            print(f"❌ Lỗi tạo destination: {e}")
            return None
    
    def destination_exists(self, slug: str) -> bool:
        """Kiểm tra destination đã tồn tại chưa"""
        with self.cursor() as cursor:
            cursor.execute("SELECT id FROM destinations WHERE slug = %s", (slug,))
            return cursor.fetchone() is not None
    
    # ==================== TOUR OPERATIONS ====================
    
    def get_tours_by_destination(self, dest_id: int) -> List[Dict[str, Any]]:
        """Lấy tours theo destination"""
        with self.cursor() as cursor:
            cursor.execute("""
                SELECT id, destination_id, name, slug, short_description, 
                       full_description, itinerary, price_adult, price_child,
                       price_infant, duration_days, duration_nights, max_people,
                       status, thumbnail, featured
                FROM tours
                WHERE destination_id = %s
                ORDER BY id
            """, (dest_id,))
            return cursor.fetchall()
    
    def get_tour_count_by_destination(self, dest_id: int) -> int:
        """Đếm số tours của destination"""
        with self.cursor() as cursor:
            cursor.execute("""
                SELECT COUNT(*) as count FROM tours WHERE destination_id = %s
            """, (dest_id,))
            result = cursor.fetchone()
            return result['count'] if result else 0
    
    def create_tour(self, data: Dict[str, Any]) -> Optional[int]:
        """Tạo tour mới, trả về ID"""
        try:
            with self.cursor() as cursor:
                columns = ', '.join(data.keys())
                placeholders = ', '.join(['%s'] * len(data))
                query = f"INSERT INTO tours ({columns}) VALUES ({placeholders})"
                cursor.execute(query, list(data.values()))
                return cursor.lastrowid
        except Error as e:
            print(f"❌ Lỗi tạo tour: {e}")
            return None
    
    def update_tour(self, tour_id: int, data: Dict[str, Any]) -> bool:
        """Cập nhật tour"""
        try:
            with self.cursor() as cursor:
                set_clause = ', '.join([f"{k} = %s" for k in data.keys()])
                query = f"UPDATE tours SET {set_clause} WHERE id = %s"
                values = list(data.values()) + [tour_id]
                cursor.execute(query, values)
                return True
        except Error as e:
            print(f"❌ Lỗi cập nhật tour: {e}")
            return False
    
    def delete_tours_by_destination(self, dest_id: int) -> int:
        """Xóa tất cả tours của destination (dùng khi regenerate)"""
        try:
            with self.cursor() as cursor:
                cursor.execute("DELETE FROM tours WHERE destination_id = %s", (dest_id,))
                return cursor.rowcount
        except Error as e:
            print(f"❌ Lỗi xóa tours: {e}")
            return 0
    
    # ==================== TOUR IMAGES OPERATIONS ====================
    
    def create_tour_image(self, data: Dict[str, Any]) -> Optional[int]:
        """Tạo tour image mới"""
        try:
            with self.cursor() as cursor:
                # Bọc tên cột trong backticks để tránh lỗi từ khóa (ví dụ: `order`)
                columns = ', '.join([f"`{k}`" for k in data.keys()])
                placeholders = ', '.join(['%s'] * len(data))
                query = f"INSERT INTO tour_images ({columns}) VALUES ({placeholders})"
                cursor.execute(query, list(data.values()))
                return cursor.lastrowid
        except Error as e:
            print(f"❌ Lỗi tạo tour image: {e}")
            return None
    
    def delete_tour_images(self, tour_id: int) -> int:
        """Xóa tất cả images của tour"""
        try:
            with self.cursor() as cursor:
                cursor.execute("DELETE FROM tour_images WHERE tour_id = %s", (tour_id,))
                return cursor.rowcount
        except Error as e:
            print(f"❌ Lỗi xóa tour images: {e}")
            return 0
    
    # ==================== REVIEW OPERATIONS ====================
    
    def get_user_ids(self, limit: int = 10) -> List[int]:
        """Lấy danh sách user IDs để tạo reviews"""
        with self.cursor() as cursor:
            cursor.execute("""
                SELECT id FROM users WHERE is_admin = 0 LIMIT %s
            """, (limit,))
            return [row['id'] for row in cursor.fetchall()]
    
    def create_review(self, data: Dict[str, Any]) -> Optional[int]:
        """Tạo review mới"""
        try:
            with self.cursor() as cursor:
                columns = ', '.join(data.keys())
                placeholders = ', '.join(['%s'] * len(data))
                query = f"INSERT INTO reviews ({columns}) VALUES ({placeholders})"
                cursor.execute(query, list(data.values()))
                return cursor.lastrowid
        except Error as e:
            print(f"❌ Lỗi tạo review: {e}")
            return None
    
    def delete_reviews_by_tour(self, tour_id: int) -> int:
        """Xóa tất cả reviews của tour"""
        try:
            with self.cursor() as cursor:
                cursor.execute("DELETE FROM reviews WHERE tour_id = %s", (tour_id,))
                return cursor.rowcount
        except Error as e:
            print(f"❌ Lỗi xóa reviews: {e}")
            return 0
            
    def get_review_count_by_tour(self, tour_id: int) -> int:
        """Đếm số reviews của tour"""
        with self.cursor() as cursor:
            cursor.execute("SELECT COUNT(*) as count FROM reviews WHERE tour_id = %s", (tour_id,))
            result = cursor.fetchone()
            return result['count'] if result else 0
    
    # ==================== STATISTICS ====================
    
    def get_stats(self) -> Dict[str, int]:
        """Lấy thống kê database"""
        stats = {}
        with self.cursor() as cursor:
            for table in ['destinations', 'tours', 'tour_images', 'reviews', 'users']:
                cursor.execute(f"SELECT COUNT(*) as count FROM {table}")
                result = cursor.fetchone()
                stats[table] = result['count'] if result else 0
        return stats


# Singleton instance
db = Database()
