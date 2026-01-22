#!/usr/bin/env python3
"""
Tour Content Generator - Script chính
Tự động tạo nội dung chuyên nghiệp cho tours du lịch

Usage:
    python main.py --all                    # Tạo cho tất cả destinations
    python main.py --destination da-lat     # Tạo cho 1 destination
    python main.py --all --dry-run          # Xem trước, không ghi database
    python main.py --all --skip-images      # Bỏ qua việc lấy ảnh mới
"""

import argparse
import sys
import time
from datetime import datetime
from typing import Optional, List, Dict, Any
from tqdm import tqdm

from config import (
    validate_config, 
    print_config, 
    CONTENT_SETTINGS,
    RATE_LIMIT
)
from database import db
from ai_service import AIContentService
from image_service import image_service
from destinations_data import VIETNAM_DESTINATIONS, get_destination_by_slug


class TourContentGenerator:
    """Class chính điều khiển việc tạo nội dung"""
    
    def __init__(self, dry_run: bool = False, skip_images: bool = False):
        self.dry_run = dry_run
        self.skip_images = skip_images
        self.ai_service: Optional[AIContentService] = None
        self.stats = {
            'destinations_created': 0,
            'destinations_processed': 0,
            'tours_created': 0,
            'images_added': 0,
            'reviews_created': 0,
            'errors': 0,
        }
    
    def initialize(self) -> bool:
        """Khởi tạo các services"""
        print("\n" + "=" * 60)
        print("🚀 TOUR CONTENT GENERATOR")
        print("=" * 60)
        
        # Validate config
        errors = validate_config()
        if errors:
            print("\n❌ Lỗi cấu hình:")
            for error in errors:
                print(f"   • {error}")
            return False
        
        # Print config
        print_config()
        
        # Connect to database
        if not db.connect():
            return False
        
        # Initialize AI service
        try:
            self.ai_service = AIContentService()
            print("✅ AI Service khởi tạo thành công")
        except Exception as e:
            print(f"❌ Lỗi khởi tạo AI Service: {e}")
            return False
        
        # Print database stats
        print("\n📊 THỐNG KÊ DATABASE HIỆN TẠI:")
        stats = db.get_stats()
        for table, count in stats.items():
            print(f"   • {table}: {count} records")
        
        if self.dry_run:
            print("\n⚠️  CHẾ ĐỘ DRY-RUN: Không ghi thay đổi vào database")
        
        return True
    
    def cleanup(self):
        """Dọn dẹp resources"""
        db.disconnect()
    
    def seed_destinations(self) -> int:
        """Tạo tất cả destinations từ danh sách có sẵn với mô tả AI"""
        print("\n" + "=" * 60)
        print("🌍 TẠO DESTINATIONS MỚI")
        print("=" * 60)
        
        created = 0
        
        for dest_data in VIETNAM_DESTINATIONS:
            slug = dest_data['slug']
            name = dest_data['name']
            
            # Kiểm tra đã tồn tại chưa
            if db.destination_exists(slug):
                print(f"   ⏭️ {name} đã tồn tại, bỏ qua")
                continue
            
            print(f"\n📍 Tạo destination: {name}")
            
            # 1. Tạo mô tả bằng AI
            description = None
            if self.ai_service:
                print(f"   🤖 Đang tạo mô tả bằng AI...")
                description = self.ai_service.generate_destination_description(name)
                if description:
                    print(f"   ✓ Mô tả: {description[:80]}...")
            
            if not description:
                description = f"Khám phá vẻ đẹp tuyệt vời của {name} - điểm đến hấp dẫn không thể bỏ lỡ."
            
            # 2. Lấy ảnh từ Google Images
            image_url = ''
            if not self.skip_images:
                search_keywords = dest_data.get('search_keywords', name)
                print(f"   🖼️ Đang lấy ảnh từ Google Images...")
                images = image_service.get_high_quality_images(search_keywords, num_images=1, validate=True)
                if images:
                    image_url = images[0]
                    print(f"   ✓ Ảnh: {image_url[:60]}...")
                else:
                    print(f"   ⚠️ Không tìm được ảnh, để trống")
            
            # 3. Chuẩn bị dữ liệu
            new_dest = {
                'name': name,
                'slug': slug,
                'description': description,
                'image': image_url,
                'is_active': 1,
            }
            
            if self.dry_run:
                print(f"   [DRY-RUN] Sẽ tạo: {name}")
                created += 1
            else:
                dest_id = db.create_destination(new_dest)
                if dest_id:
                    print(f"   ✅ Đã tạo destination ID: {dest_id}")
                    created += 1
                    self.stats['destinations_created'] += 1
                else:
                    print(f"   ❌ Lỗi tạo destination")
                    self.stats['errors'] += 1
        
        if not self.dry_run and created > 0:
            db.commit()
            print(f"\n💾 Đã commit {created} destinations mới")
        
        return created
    
    def process_destination(self, destination: Dict[str, Any], 
                           num_tours: int = 4) -> bool:
        """Xử lý 1 destination: tạo tours + images + reviews"""
        dest_name = destination['name']
        dest_slug = destination['slug']
        dest_id = destination['id']
        
        print(f"\n{'=' * 60}")
        print(f"📍 XỬ LÝ DESTINATION: {dest_name}")
        print(f"{'=' * 60}")
        
        try:
            # 1. Kiểm tra tours đã có
            existing_tours = db.get_tour_count_by_destination(dest_id)
            if existing_tours >= num_tours:
                print(f"   ⏭️ Đã có {existing_tours} tours, bỏ qua để tiết kiệm API.")
                self.stats['destinations_processed'] += 1
                return True
                
            # Nếu chưa đủ tours hoặc dry-run thì mới xóa cũ tạo mới (hoặc bổ sung - ở đây chọn xóa clean cho đồng bộ)
            if not self.dry_run and existing_tours > 0:
                print(f"🗑️ Xóa {existing_tours} tours cũ để tạo lại bộ mới...")
                db.delete_tours_by_destination(dest_id)
            
            # 2. Lấy ảnh cho destination từ Google Images
            images = []
            if not self.skip_images:
                # Lấy search keywords từ destinations_data nếu có
                dest_info = get_destination_by_slug(dest_slug)
                search_keywords = dest_info.get('search_keywords', dest_name) if dest_info else dest_name
                
                print(f"\n🖼️ Đang lấy ảnh cho {dest_name}...")
                images = image_service.get_high_quality_images(search_keywords, num_images=8, validate=True)
                
                if images:
                    print(f"   ✓ Có {len(images)} ảnh")
                else:
                    print("   ⚠️ Không tìm được ảnh, tours sẽ không có thumbnail")
            
            # 3. Tạo tours
            print(f"\n🎯 Tạo {num_tours} tours...")
            
            for i in range(num_tours):
                print(f"\n--- Tour {i + 1}/{num_tours} ---")
                
                # Generate tour data using AI
                tour_data = self.ai_service.generate_tour_data(destination, i)
                
                if not tour_data:
                    print(f"❌ Không thể tạo tour {i + 1}")
                    self.stats['errors'] += 1
                    continue
                
                # Gán thumbnail từ ảnh đã lấy
                if images:
                    tour_data['thumbnail'] = images[i % len(images)]
                
                if self.dry_run:
                    print(f"   [DRY-RUN] Sẽ tạo tour: {tour_data['name']}")
                    self.stats['tours_created'] += 1
                else:
                    # Insert tour vào database
                    tour_id = db.create_tour(tour_data)
                    
                    if tour_id:
                        print(f"   ✓ Đã tạo tour ID: {tour_id}")
                        self.stats['tours_created'] += 1
                        
                        # 4. Tạo tour images
                        tour_images = images[1:6] if len(images) > 1 else images
                        for idx, img_url in enumerate(tour_images):
                            img_data = {
                                'tour_id': tour_id,
                                'image_path': img_url,
                                'alt_text': f"{tour_data['name']} - Ảnh {idx + 1}",
                                'is_primary': idx == 0,
                                'order': idx,
                            }
                            if db.create_tour_image(img_data):
                                self.stats['images_added'] += 1
                        
                        # 5. Tạo reviews
                        if CONTENT_SETTINGS['generate_reviews']:
                            self._create_reviews_for_tour(
                                tour_id, 
                                tour_data['name'], 
                                dest_name
                            )
                    else:
                        print(f"   ❌ Lỗi tạo tour vào database")
                        self.stats['errors'] += 1
                
                # Rate limit giữa các tours
                time.sleep(1)
            
            self.stats['destinations_processed'] += 1
            
            # Commit sau mỗi destination
            if not self.dry_run:
                db.commit()
                print(f"\n✅ Đã commit thay đổi cho {dest_name}")
            
            return True
            
        except Exception as e:
            print(f"\n❌ Lỗi xử lý destination {dest_name}: {e}")
            if not self.dry_run:
                db.rollback()
            self.stats['errors'] += 1
            return False
    
    def _create_reviews_for_tour(self, tour_id: int, tour_name: str, 
                                  dest_name: str):
        """Tạo reviews cho tour"""
        print(f"   📝 Tạo reviews...")
        
        # Lấy user IDs
        user_ids = db.get_user_ids(limit=10)
        if not user_ids:
            print("   ⚠️ Không có users để tạo reviews")
            return
        
        # Generate reviews bằng AI
        reviews = self.ai_service.generate_reviews(
            tour_name, 
            dest_name,
            count=CONTENT_SETTINGS['reviews_per_tour']
        )
        
        for idx, review in enumerate(reviews):
            review_data = {
                'tour_id': tour_id,
                'user_id': user_ids[idx % len(user_ids)],
                'rating': review.get('rating', 5),
                'title': review.get('title', 'Đánh giá tuyệt vời'),
                'comment': review.get('comment', 'Tour rất tuyệt vời!'),
                'is_verified': True,
                'is_approved': True,
                'helpful_count': idx * 2,
            }
            
            if not self.dry_run:
                if db.create_review(review_data):
                    self.stats['reviews_created'] += 1
            else:
                self.stats['reviews_created'] += 1
        
        print(f"   ✓ Đã tạo {len(reviews)} reviews")
    
    def run(self, destination_slug: Optional[str] = None, 
            num_tours: int = 4) -> bool:
        """Chạy generator"""
        
        if not self.initialize():
            return False
        
        try:
            # Lấy destinations cần xử lý
            if destination_slug:
                dest = db.get_destination_by_slug(destination_slug)
                if not dest:
                    print(f"❌ Không tìm thấy destination: {destination_slug}")
                    return False
                destinations = [dest]
            else:
                destinations = db.get_all_destinations()
            
            print(f"\n📋 Sẽ xử lý {len(destinations)} destinations")
            print(f"📋 Mỗi destination tạo {num_tours} tours")
            
            # Confirm trước khi chạy
            if not self.dry_run:
                print("\n⚠️  CẢNH BÁO: Script sẽ XÓA tours cũ và tạo mới!")
                response = input("Tiếp tục? (y/N): ").strip().lower()
                if response != 'y':
                    print("❌ Đã hủy")
                    return False
            
            # Process từng destination
            start_time = time.time()
            
            for dest in tqdm(destinations, desc="Processing destinations"):
                self.process_destination(dest, num_tours)
            
            elapsed = time.time() - start_time
            
            # Print summary
            self._print_summary(elapsed)
            
            return True
            
        finally:
            self.cleanup()
    
    def _print_summary(self, elapsed: float):
        """In tóm tắt kết quả"""
        print("\n" + "=" * 60)
        print("📊 KẾT QUẢ")
        print("=" * 60)
        print(f"   ⏱️  Thời gian: {elapsed:.1f} giây")
        print(f"   🌍 Destinations tạo mới: {self.stats['destinations_created']}")
        print(f"   📍 Destinations xử lý: {self.stats['destinations_processed']}")
        print(f"   🎯 Tours tạo: {self.stats['tours_created']}")
        print(f"   🖼️  Images thêm: {self.stats['images_added']}")
        print(f"   ⭐ Reviews tạo: {self.stats['reviews_created']}")
        print(f"   ❌ Lỗi: {self.stats['errors']}")
        print("=" * 60)
        
        if self.dry_run:
            print("\n⚠️  ĐÂY LÀ DRY-RUN - Không có thay đổi nào được ghi vào database")
            print("   Chạy lại không có --dry-run để thực sự tạo nội dung")


def main():
    """Entry point"""
    parser = argparse.ArgumentParser(
        description='Tạo nội dung tour du lịch tự động với AI',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Ví dụ:
    python main.py --all                        # Tạo cho tất cả destinations
    python main.py --destination vinh-ha-long   # Chỉ tạo cho Vịnh Hạ Long
    python main.py --all --tours 3              # Tạo 3 tours mỗi destination
    python main.py --all --dry-run              # Xem trước, không ghi database
    python main.py --all --skip-images          # Dùng ảnh backup, không crawl
        """
    )
    
    parser.add_argument(
        '--all', 
        action='store_true',
        help='Xử lý tất cả destinations'
    )
    
    parser.add_argument(
        '--destination', '-d',
        type=str,
        help='Xử lý 1 destination cụ thể (theo slug)'
    )
    
    parser.add_argument(
        '--tours', '-t',
        type=int,
        default=4,
        help='Số tours tạo cho mỗi destination (mặc định: 4)'
    )
    
    parser.add_argument(
        '--dry-run',
        action='store_true',
        help='Chế độ xem trước, không ghi database'
    )
    
    parser.add_argument(
        '--skip-images',
        action='store_true', 
        help='Bỏ qua việc crawl ảnh, dùng ảnh backup'
    )
    
    parser.add_argument(
        '--seed-destinations',
        action='store_true',
        help='Tạo tất cả destinations mới với mô tả AI (20+ địa điểm VN)'
    )
    
    def generate_missing_reviews(self, destination_slug: Optional[str] = None) -> bool:
        """Tạo reviews cho các tour chưa có hoặc ít reviews"""
        if not self.initialize():
            return False
            
        try:
            print("\n" + "=" * 60)
            print("⭐ UPDATE REVIEWS (KHÔNG XÓA DỮ LIỆU CŨ)")
            print("=" * 60)

            # Lấy destinations
            if destination_slug:
                dest = db.get_destination_by_slug(destination_slug)
                destinations = [dest] if dest else []
            else:
                destinations = db.get_all_destinations()
            
            total_reviews_added = 0
            
            for dest in tqdm(destinations, desc="Processing destinations"):
                dest_id = dest['id']
                dest_name = dest['name']
                
                tours = db.get_tours_by_destination(dest_id)
                for tour in tours:
                    tour_id = tour['id']
                    tour_name = tour['name']
                    
                    # Check review count
                    current_reviews = db.get_review_count_by_tour(tour_id)
                    target_reviews = CONTENT_SETTINGS['reviews_per_tour']
                    
                    if current_reviews >= target_reviews:
                        continue
                        
                    needed = target_reviews - current_reviews
                    print(f"\n   📝 Tour '{tour_name}' thiếu {needed} reviews. Đang tạo...")
                    
                    # Prepare context for better accuracy
                    context = (
                        f"Mô tả: {tour['short_description']}\n"
                        f"Lịch trình tóm tắt: {tour.get('itinerary', '')[:500]}..."
                    )
                    
                    # Generate reviews
                    reviews = self.ai_service.generate_reviews(
                        tour_name, 
                        dest_name,
                        tour_context=context,
                        count=needed
                    )
                    
                    if not reviews:
                        print("   ⚠️ Không tạo được reviews")
                        continue
                        
                    # Get users
                    user_ids = db.get_user_ids(limit=20)
                    
                    # Save
                    count = 0
                    for idx, review in enumerate(reviews):
                        review_data = {
                            'tour_id': tour_id,
                            'user_id': user_ids[idx % len(user_ids)],
                            'rating': review.get('rating', 5),
                            'title': review.get('title', 'Tuyệt vời'),
                            'comment': review.get('comment', 'Rất hài lòng'),
                            'is_verified': True,
                            'is_approved': True,
                            'helpful_count': 0,
                        }
                        
                        if not self.dry_run:
                            if db.create_review(review_data):
                                count += 1
                        else:
                            count += 1
                            
                    print(f"   ✓ Đã thêm {count} reviews mới")
                    total_reviews_added += count
                    self.stats['reviews_created'] += count
                    
                    if not self.dry_run:
                        db.commit()
                    
                    # Rate limit lightly
                    time.sleep(0.5)
            
            print(f"\n✅ Hoàn tất! Tổng cộng thêm {total_reviews_added} reviews.")
            return True
            
        finally:
            self.cleanup()

def main():
    """Entry point"""
    parser = argparse.ArgumentParser(
        description='Tạo nội dung tour du lịch tự động với AI',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Ví dụ:
    python main.py --all                        # Tạo cho tất cả destinations
    python main.py --destination vinh-ha-long   # Chỉ tạo cho Vịnh Hạ Long
    python main.py --update-reviews             # Chỉ tạo thêm reviews cho data cũ
    python main.py --all --dry-run              # Xem trước, không ghi database
        """
    )
    
    parser.add_argument('--all', action='store_true', help='Xử lý tất cả destinations')
    parser.add_argument('--destination', '-d', type=str, help='Xử lý 1 destination cụ thể')
    parser.add_argument('--tours', '-t', type=int, default=4, help='Số tours tạo cho mỗi destination')
    parser.add_argument('--dry-run', action='store_true', help='Chế độ xem trước')
    parser.add_argument('--skip-images', action='store_true', help='Bỏ qua việc crawl ảnh')
    parser.add_argument('--seed-destinations', action='store_true', help='Tạo destinations mới')
    parser.add_argument('--update-reviews', action='store_true', help='Chế độ chỉ update reviews thiếu, KHÔNG xóa data')
    
    args = parser.parse_args()
    
    # Validate arguments
    if not args.all and not args.destination and not args.seed_destinations and not args.update_reviews:
        parser.error("Cần chỉ định --all, --destination, --seed-destinations hoặc --update-reviews")
    
    generator = TourContentGenerator(
        dry_run=args.dry_run,
        skip_images=args.skip_images
    )
    
    if args.update_reviews:
        success = generator.generate_missing_reviews(args.destination)
    elif args.seed_destinations and not args.all and not args.destination:
        if not generator.initialize():
            sys.exit(1)
        try:
            generator.seed_destinations()
            success = True
        finally:
            generator.cleanup()
    else:
        if args.seed_destinations:
            if not generator.initialize():
                sys.exit(1)
            generator.seed_destinations()
            generator.cleanup()
        
        success = generator.run(
            destination_slug=args.destination,
            num_tours=args.tours
        )
    
    sys.exit(0 if success else 1)


if __name__ == '__main__':
    main()
