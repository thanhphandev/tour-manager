# 🚀 Tour Content Generator - Hướng Dẫn Sử Dụng

Script Python tự động hóa việc tạo nội dung du lịch chuyên nghiệp, bao gồm:
1.  **AI Content**: Sử dụng Groq (Llama 3) để viết mô tả, lịch trình tour hấp dẫn bằng tiếng Việt.
2.  **Smart Images**: Tự động tìm kiếm và tải ảnh thực tế chất lượng cao từ **Google Images** (thông qua Google Custom Search API).
3.  **Database Integration**: Kết nối trực tiếp vào MySQL trên Railway để cập nhật dữ liệu.

---

## 🛠️ 1. Cài đặt Môi trường

Yêu cầu: Python 3.8+

1.  **Di chuyển vào thư mục scripts**:
    ```bash
    cd e:\tour-manager\scripts
    ```

2.  **Tạo và kích hoạt môi trường ảo (khuyên dùng)**:
    ```bash
    python -m venv .venv
    .venv\Scripts\activate
    ```

3.  **Cài đặt các thư viện cần thiết**:
    ```bash
    pip install -r requirements.txt
    ```

---

## 🔑 2. Cấu hình API Keys (Quan trọng)

Bạn cần tạo file `.env` từ file mẫu `.env.example` và điền các thông tin sau:

```bash
copy .env.example .env
```

### A. Cấu hình Database (Railway)
Lấy thông tin từ Railway Dashboard > Variables hoặc Connect tab.
```env
MYSQL_HOST=tramway.proxy.rlwy.net
MYSQL_PORT=54848
MYSQL_USER=root
MYSQL_PASSWORD=...
MYSQL_DATABASE=railway
```

### B. Cấu hình AI (Groq - Miễn phí)
1.  Truy cập: [https://console.groq.com/keys](https://console.groq.com/keys)
2.  Tạo **API Key** mới.
3.  Điền vào `.env`:
    ```env
    GROQ_API_KEY=gsk_...
    ```

### C. Cấu hình Ảnh (Google Custom Search - Quan trọng)
Để lấy ảnh chính xác từ Google mà không bị chặn, bạn cần cấu hình Google API (Miễn phí 100 requests/ngày):

1.  **Lấy Google API Key**:
    -   Truy cập: [Google Cloud Console - Custom Search API](https://developers.google.com/custom-search/v1/overview)
    -   Nhấn **"Get a Key"** -> Tạo project mới -> Copy Key.

2.  **Lấy Search Engine ID (CX)**:
    -   Truy cập: [Programmable Search Engine](https://programmablesearchengine.google.com/controlpanel/all)
    -   Nhấn **"Add"** để tạo bộ máy tìm kiếm mới.
    -   **Name**: `TourImages`
    -   **What to search**: Chọn `Search the entire web`.
    -   **Image search**: BẬT (ON).
    -   **SafeSearch**: BẬT (nếu muốn lọc ảnh nhạy cảm).
    -   Sau khi tạo, copy **"Search engine ID"** (CX).

3.  Điền vào `.env`:
    ```env
    GOOGLE_API_KEY=AIza...
    GOOGLE_CX=012345...
    ```

---

## ▶️ 3. Cách Sử Dụng

### Lệnh 1: Khởi tạo dữ liệu Destinations (Chạy lần đầu)
Tạo danh sách các địa điểm du lịch (Vịnh Hạ Long, Đà Lạt,...) với mô tả AI và ảnh từ Google.
```bash
python main.py --seed-destinations
```

### Lệnh 2: Tạo Tours cho TẤT CẢ địa điểm
Tự động tạo 4 tours cho mỗi địa điểm có trong database.
```bash
python main.py --all
```

### Lệnh 3: Tạo Tours cho 1 địa điểm cụ thể
Chỉ tạo tours cho Đà Lạt (theo slug).
```bash
python main.py --destination da-lat --tours 3
```

### Lệnh 4: Chế độ Xem trước (Dry Run)
Kiểm tra kết quả mà KHÔNG ghi vào database (an toàn để test).
```bash
python main.py --all --dry-run
```

---

## ❓ Xử lý sự cố thường gặp

**1. Lỗi `ModuleNotFoundError`**:
-   Đảm bảo bạn đã `activate` môi trường ảo và chạy `pip install -r requirements.txt`.

**2. Lỗi `0 ảnh tìm thấy`**:
-   Kiểm tra lại `GOOGLE_API_KEY` và `GOOGLE_CX` trong `.env`.
-   Đảm bảo bạn đã bật **"Image search"** và **"Search the entire web"** trong cài đặt Google Search Engine.
-   Nếu hết quota Google API (100 req/ngày), script sẽ tự chuyển sang chế độ scraping (kém ổn định hơn).

**3. Lỗi kết nối Database**:
-   Database trên Railway có thể thay đổi Port sau mỗi lần redeploy. Hãy kiểm tra lại Host/Port trên Railway Dashboard.

**4. Script chạy lâu**:
-   Để tránh bị chặn, script có chế độ `RATE_LIMIT` (nghỉ 1-2s giữa các request). Đây là tính năng, không phải lỗi.
