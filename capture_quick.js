import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * ============================================================================
 * 🎯 SCRIPT CHỤP ẢNH ĐƠN GIẢN - Dùng để test hoặc chụp nhanh vài trang
 * ============================================================================
 */

const BASE_URL = 'http://localhost:8000';
const SCREENSHOT_DIR = path.join(__dirname, 'screenshots-quick');

// Tạo thư mục nếu chưa có
if (!fs.existsSync(SCREENSHOT_DIR)) {
    fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

/**
 * Hàm chụp một trang đơn giản
 * @param {Page} page - Puppeteer page
 * @param {string} url - URL tương đối (vd: '/tours')
 * @param {string} filename - Tên file (không cần .png)
 * @param {Object} options - Tùy chọn bổ sung
 */
async function quickCapture(page, url, filename, options = {}) {
    const {
        waitForSelector = null,
        fullPage = false,
        delay = 1000
    } = options;

    console.log(`📸 Chụp: ${filename}`);

    try {
        // Điều hướng đến trang
        await page.goto(BASE_URL + url, {
            waitUntil: 'networkidle0',
            timeout: 30000
        });

        // Đợi selector nếu có
        if (waitForSelector) {
            await page.waitForSelector(waitForSelector, { timeout: 10000 });
        }

        // Delay thêm
        await page.waitForTimeout(delay);

        // Ẩn debug bar
        await page.evaluate(() => {
            const debugBar = document.querySelector('.phpdebugbar');
            if (debugBar) debugBar.style.display = 'none';
        });

        // Chụp ảnh
        const filepath = path.join(SCREENSHOT_DIR, `${filename}.png`);
        await page.screenshot({
            path: filepath,
            fullPage: fullPage
        });

        console.log(`   ✅ Đã lưu: ${filepath}`);

    } catch (error) {
        console.error(`   ❌ Lỗi: ${error.message}`);
    }
}

/**
 * ============================================================================
 * MAIN - Sử dụng ở ĐÂY
 * ============================================================================
 */
(async () => {
    console.log('🚀 Bắt đầu chụp ảnh nhanh...\n');

    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();

    // Set viewport - 1920x1080, retina display
    await page.setViewport({
        width: 1920,
        height: 1080,
        deviceScaleFactor: 2  // Chất lượng cao
    });

    // ========================================
    // ✏️ CHỈNH SỬA Ở ĐÂY - Thêm/bớt trang cần chụp
    // ========================================

    // Example 1: Chụp trang chủ
    await quickCapture(page, '/', 'homepage');

    // Example 2: Chụp danh sách tour, đợi element .tour-card xuất hiện
    await quickCapture(page, '/tours', 'tours-list', {
        waitForSelector: '.tour-card'
    });

    // Example 3: Chụp chi tiết tour, full page (toàn bộ trang dài)
    await quickCapture(page, '/tours/hoi-an-co-kinh-van-hoa-da-nang', 'tour-detail', {
        fullPage: true,
        delay: 2000  // Đợi lâu hơn
    });

    // Example 4: Chụp trang login
    await quickCapture(page, '/login', 'login-page');

    // ========================================
    // 🔐 NẾU CẦN ĐĂNG NHẬP
    // ========================================

    /*
    console.log('\n🔐 Đăng nhập...');
    await page.goto(BASE_URL + '/login', { waitUntil: 'networkidle0' });
    await page.type('input[name="email"]', 'your-email@example.com');
    await page.type('input[name="password"]', 'your-password');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('button[type="submit"]')
    ]);
    console.log('✅ Đã đăng nhập!\n');
    
    // Sau khi đăng nhập, chụp các trang cần authentication
    await quickCapture(page, '/profile', 'profile-page');
    await quickCapture(page, '/bookings/history', 'booking-history');
    */

    await browser.close();
    console.log('\n✅ Hoàn tất!\n');
})();
