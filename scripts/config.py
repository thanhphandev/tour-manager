"""
Configuration module - Quản lý cấu hình và biến môi trường
"""
import os
from pathlib import Path
from dotenv import load_dotenv

# Load .env file
env_path = Path(__file__).parent / '.env'
load_dotenv(env_path)

# Database Configuration
DB_CONFIG = {
    'host': os.getenv('MYSQL_HOST', 'localhost'),
    'port': int(os.getenv('MYSQL_PORT', 3306)),
    'user': os.getenv('MYSQL_USER', 'root'),
    'password': os.getenv('MYSQL_PASSWORD', ''),
    'database': os.getenv('MYSQL_DATABASE', 'railway'),
    'charset': 'utf8mb4',
    'collation': 'utf8mb4_unicode_ci',
    'autocommit': False,  # Manual commit for safety
}

# Groq API Configuration
GROQ_API_KEY = os.getenv('GROQ_API_KEY', '')
GROQ_MODEL = 'llama-3.3-70b-versatile'  # Best free model for Vietnamese

# Google Custom Search API (cho ảnh chính xác)
GOOGLE_API_KEY = os.getenv('GOOGLE_API_KEY', '')
GOOGLE_CX = os.getenv('GOOGLE_CX', '')

# Rate Limiting (để an toàn với free tier)
RATE_LIMIT = {
    'groq_delay': 2.5,      # Giây giữa mỗi request Groq
    'image_delay': 1.0,     # Giây giữa mỗi request ảnh
    'google_delay': 1.0,    # Giây giữa mỗi request Google API
    'max_retries': 3,       # Số lần retry khi lỗi
    'retry_delay': 5.0,     # Giây chờ khi retry
}

# Content Generation Settings
CONTENT_SETTINGS = {
    'tours_per_destination': 4,     # Số tours tạo cho mỗi destination
    'images_per_tour': 5,           # Số ảnh cho mỗi tour
    'generate_reviews': True,       # Có tạo reviews không
    'reviews_per_tour': 5,          # Số reviews cho mỗi tour
}

# Logging
LOG_FILE = Path(__file__).parent / 'logs' / 'generator.log'


def validate_config():
    """Kiểm tra cấu hình hợp lệ"""
    errors = []
    
    if not GROQ_API_KEY:
        errors.append("GROQ_API_KEY chưa được cấu hình trong .env")
    
    if not DB_CONFIG['password']:
        errors.append("MYSQL_PASSWORD chưa được cấu hình trong .env")
        
    return errors


def print_config():
    """In cấu hình hiện tại (ẩn thông tin nhạy cảm)"""
    print("=" * 50)
    print("📋 CẤU HÌNH HIỆN TẠI")
    print("=" * 50)
    print(f"🔗 MySQL Host: {DB_CONFIG['host']}:{DB_CONFIG['port']}")
    print(f"📁 Database: {DB_CONFIG['database']}")
    print(f"🤖 Groq Model: {GROQ_MODEL}")
    print(f"🔑 Groq API Key: {'✅ Configured' if GROQ_API_KEY else '❌ Missing'}")
    print(f"🔍 Google API Key: {'✅ Configured' if GOOGLE_API_KEY else '⚠️ Missing (Scraping fallback)'}")
    print(f"⏱️ Rate Limit: {RATE_LIMIT['groq_delay']}s giữa mỗi request")
    print("=" * 50)
