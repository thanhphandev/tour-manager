import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// ===== CẤU HÌNH =====
const CONFIG = {
    baseUrl: 'http://localhost:8000',
    screenshotDir: path.join(__dirname, 'screenshots'),

    // Viewport settings
    viewport: {
        width: 1920,
        height: 1080,
        deviceScaleFactor: 2  // Chụp ảnh chất lượng cao (retina)
    },

    // Screenshot quality settings
    screenshotOptions: {
        type: 'png',           // 'png' cho chất lượng cao, 'jpeg' cho file nhỏ hơn
        fullPage: false,       // true = chụp toàn bộ trang, false = chỉ viewport
        quality: 100          // Chỉ áp dụng cho jpeg (0-100)
    },

    // Delay settings (ms)
    delays: {
        afterPageLoad: 1500,    // Đợi sau khi trang load
        afterInteraction: 500,  // Đợi sau khi click/type
        betweenScreenshots: 300 // Đợi giữa các screenshot
    },

    // Admin credentials
    admin: {
        email: 'thanhphanvan1610@gmail.com',
        password: '123'
    }
};

// ===== DANH SÁCH CÁC TRANG CẦN CHỤP =====
const SCREENSHOT_MANIFEST = {
    // ========== PHẦN CLIENT ==========
    client_guest: [
        {
            url: '/',
            name: '01_Homepage',
            label: 'Trang chủ - Giao diện khách',
            description: 'Trang chủ hiển thị các tour du lịch nổi bật, điểm đến phổ biến và banner quảng cáo.',
            waitForSelector: '.hero-section, .featured-tours'
        },
        {
            url: '/destinations',
            name: '02_Destinations_List',
            label: 'Danh sách điểm đến',
            description: 'Trang hiển thị tất cả các điểm đến du lịch có sẵn trong hệ thống.',
            waitForSelector: '.destinations-grid, .destination-card'
        },
        {
            url: '/destinations/vinh-ha-long',
            name: '03_Destination_Detail',
            label: 'Chi tiết điểm đến - Vịnh Hạ Long',
            description: 'Trang chi tiết về một điểm đến cụ thể, bao gồm mô tả, hình ảnh và các tour liên quan.',
            waitForSelector: '.destination-detail, .related-tours'
        },
        {
            url: '/tours',
            name: '04_Tours_List',
            label: 'Danh sách tour',
            description: 'Trang hiển thị tất cả các tour du lịch với bộ lọc và tìm kiếm.',
            waitForSelector: '.tours-grid, .tour-card'
        },
        {
            url: '/tours/hoi-an-co-kinh-van-hoa-da-nang',
            name: '05_Tour_Detail',
            label: 'Chi tiết tour - Hội An cổ kính',
            description: 'Trang chi tiết tour bao gồm lịch trình, giá cả, đánh giá và nút đặt tour.',
            waitForSelector: '.tour-detail, .tour-itinerary'
        },
        {
            url: '/login',
            name: '09_Login_Page',
            label: 'Trang đăng nhập',
            description: 'Giao diện đăng nhập cho khách hàng và admin.',
            waitForSelector: 'form[action*="login"]'
        },
        {
            url: '/register',
            name: '10_Register_Page',
            label: 'Trang đăng ký',
            description: 'Form đăng ký tài khoản mới cho khách hàng.',
            waitForSelector: 'form[action*="register"]'
        }
    ],

    client_authenticated: [
        {
            url: '/tours/hoi-an-co-kinh-van-hoa-da-nang/book',
            name: '06_Booking_Form',
            label: 'Form đặt tour',
            description: 'Form đặt tour cho khách hàng đã đăng nhập, bao gồm chọn ngày, số lượng người và thông tin liên hệ.',
            waitForSelector: 'form[action*="book"]'
        },
        {
            url: '/bookings/history',
            name: '11_Booking_History',
            label: 'Lịch sử đặt tour',
            description: 'Danh sách các booking của khách hàng với trạng thái và thông tin chi tiết.',
            waitForSelector: '.booking-history, .booking-item'
        },
        {
            url: '/profile',
            name: '12_Profile',
            label: 'Trang hồ sơ cá nhân',
            description: 'Trang quản lý thông tin cá nhân, mật khẩu và tùy chọn tài khoản.',
            waitForSelector: '.profile-form'
        }
    ],

    // ========== PHẦN ADMIN ==========
    admin: [
        {
            url: '/admin/dashboard',
            name: '14_Admin_Dashboard',
            label: 'Admin - Dashboard',
            description: 'Trang tổng quan hiển thị thống kê doanh thu, booking, tour và khách hàng.',
            waitForSelector: '.dashboard-stats, .chart-container',
            fullPage: true
        },
        {
            url: '/admin/tours',
            name: '15_Admin_Tours_List',
            label: 'Admin - Danh sách tour',
            description: 'Quản lý danh sách tour với chức năng thêm, sửa, xóa.',
            waitForSelector: 'table, .tours-table',
            fullPage: true
        },
        {
            url: '/admin/tours/create',
            name: '16_Admin_Tour_Create',
            label: 'Admin - Tạo tour mới',
            description: 'Form tạo tour mới với đầy đủ thông tin: tên, mô tả, giá, lịch trình, điểm đến.',
            waitForSelector: 'form',
            fullPage: true
        },
        {
            url: '/admin/destinations',
            name: '18_Admin_Destinations',
            label: 'Admin - Quản lý điểm đến',
            description: 'Quản lý các điểm đến du lịch trong hệ thống.',
            waitForSelector: 'table, .destinations-table',
            fullPage: true
        },
        {
            url: '/admin/bookings',
            name: '19_Admin_Bookings',
            label: 'Admin - Quản lý booking',
            description: 'Danh sách tất cả các booking với trạng thái và thao tác quản lý.',
            waitForSelector: 'table, .bookings-table',
            fullPage: true
        },
        {
            url: '/admin/users',
            name: '21_Admin_Users',
            label: 'Admin - Quản lý người dùng',
            description: 'Quản lý tài khoản khách hàng và admin.',
            waitForSelector: 'table, .users-table',
            fullPage: true
        },
        {
            url: '/admin/reviews',
            name: '22_Admin_Reviews',
            label: 'Admin - Quản lý đánh giá',
            description: 'Quản lý và duyệt các đánh giá từ khách hàng.',
            waitForSelector: 'table, .reviews-table',
            fullPage: true
        },
        {
            url: '/admin/reports/revenue',
            name: '23_Admin_Revenue_Report',
            label: 'Admin - Báo cáo doanh thu',
            description: 'Báo cáo chi tiết về doanh thu theo thời gian với biểu đồ.',
            waitForSelector: '.report-content, .chart-container',
            fullPage: true
        },
        {
            url: '/admin/activity-logs',
            name: '24_Admin_Activity_Logs',
            label: 'Admin - Nhật ký hoạt động',
            description: 'Theo dõi các hoạt động của người dùng trong hệ thống.',
            waitForSelector: 'table, .activity-logs-table',
            fullPage: true
        }
    ]
};

// ===== HÀM TIỆN ÍCH =====

/**
 * Tạo thư mục screenshot nếu chưa tồn tại
 */
function ensureScreenshotDir() {
    if (!fs.existsSync(CONFIG.screenshotDir)) {
        fs.mkdirSync(CONFIG.screenshotDir, { recursive: true });
        console.log(`✅ Created screenshot directory: ${CONFIG.screenshotDir}`);
    }
}

/**
 * Chụp màn hình với cấu hình chất lượng cao
 * @param {Page} page - Puppeteer page
 * @param {Object} screenshot - Screenshot config
 */
async function captureHighQuality(page, screenshot) {
    const { url, name, label, description, waitForSelector, fullPage } = screenshot;
    const fullUrl = CONFIG.baseUrl + url;

    console.log(`\n📸 Capturing: ${label}`);
    console.log(`   URL: ${fullUrl}`);

    try {
        // Navigate to page
        await page.goto(fullUrl, {
            waitUntil: 'networkidle0',
            timeout: 30000
        });

        // Wait for specific selector if provided
        if (waitForSelector) {
            try {
                await page.waitForSelector(waitForSelector, { timeout: 10000 });
            } catch (e) {
                console.warn(`   ⚠️  Selector not found: ${waitForSelector}`);
            }
        }

        // Additional delay for animations/loading
        await page.waitForTimeout(CONFIG.delays.afterPageLoad);

        // Hide debug bars and other development elements
        await page.evaluate(() => {
            // Hide Laravel debug bar
            const debugBar = document.querySelector('.phpdebugbar');
            if (debugBar) debugBar.style.display = 'none';

            // Hide any other debug overlays
            const debugElements = document.querySelectorAll('[class*="debug"], [id*="debug"]');
            debugElements.forEach(el => el.style.display = 'none');
        });

        // Take screenshot
        const screenshotPath = path.join(CONFIG.screenshotDir, `${name}.png`);
        await page.screenshot({
            path: screenshotPath,
            type: CONFIG.screenshotOptions.type,
            fullPage: fullPage !== undefined ? fullPage : CONFIG.screenshotOptions.fullPage
        });

        console.log(`   ✅ Saved: ${name}.png`);

        // Delay between screenshots
        await page.waitForTimeout(CONFIG.delays.betweenScreenshots);

        return {
            success: true,
            name,
            label,
            description,
            path: screenshotPath
        };

    } catch (error) {
        console.error(`   ❌ Failed: ${error.message}`);
        return {
            success: false,
            name,
            label,
            error: error.message
        };
    }
}

/**
 * Đăng ký và đăng nhập tài khoản client mới
 * @param {Page} page - Puppeteer page
 */
async function registerClient(page) {
    console.log('\n🔐 Registering new client user...');

    try {
        await page.goto(CONFIG.baseUrl + '/register', {
            waitUntil: 'networkidle0',
            timeout: 30000
        });

        const timestamp = Date.now();
        const email = `client_${timestamp}@example.com`;

        await page.type('input[name="name"]', 'Client User');
        await page.type('input[name="email"]', email);
        await page.type('input[name="password"]', 'password');
        await page.type('input[name="password_confirmation"]', 'password');

        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle0' }),
            page.click('button[type="submit"]')
        ]);

        console.log(`   ✅ Registered as: ${email}`);
        return { success: true, email };

    } catch (error) {
        console.error(`   ❌ Registration failed: ${error.message}`);
        return { success: false, error: error.message };
    }
}

/**
 * Đăng nhập tài khoản admin
 * @param {Page} page - Puppeteer page
 */
async function loginAdmin(page) {
    console.log('\n🔐 Logging in as admin...');

    try {
        await page.goto(CONFIG.baseUrl + '/login', {
            waitUntil: 'networkidle0',
            timeout: 30000
        });

        await page.type('input[name="email"]', CONFIG.admin.email);
        await page.type('input[name="password"]', CONFIG.admin.password);

        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle0' }),
            page.click('button[type="submit"]')
        ]);

        console.log(`   ✅ Logged in as: ${CONFIG.admin.email}`);
        return { success: true };

    } catch (error) {
        console.error(`   ❌ Login failed: ${error.message}`);
        return { success: false, error: error.message };
    }
}

/**
 * Tạo file manifest JSON với thông tin tất cả screenshots
 * @param {Array} results - Kết quả chụp ảnh
 */
function generateManifest(results) {
    const manifest = {
        generated_at: new Date().toISOString(),
        total_screenshots: results.length,
        successful: results.filter(r => r.success).length,
        failed: results.filter(r => !r.success).length,
        screenshots: results.map(r => ({
            name: r.name,
            label: r.label,
            description: r.description,
            success: r.success,
            path: r.path,
            error: r.error
        }))
    };

    const manifestPath = path.join(CONFIG.screenshotDir, 'manifest.json');
    fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2));
    console.log(`\n📋 Manifest saved to: ${manifestPath}`);

    return manifest;
}

/**
 * Tạo file Markdown với tất cả screenshots và nhãn
 * @param {Array} results - Kết quả chụp ảnh
 */
function generateMarkdownDoc(results) {
    let markdown = `# Screenshots Documentation\n\n`;
    markdown += `**Generated:** ${new Date().toLocaleString('vi-VN')}\n\n`;
    markdown += `**Total Screenshots:** ${results.length}\n\n`;
    markdown += `---\n\n`;

    // Group by category
    const categories = {
        'Client - Guest': results.filter(r => r.name.startsWith('0') && parseInt(r.name) < 10),
        'Client - Authenticated': results.filter(r => r.name.startsWith('1') && parseInt(r.name) < 14),
        'Admin Panel': results.filter(r => parseInt(r.name) >= 14)
    };

    Object.entries(categories).forEach(([category, screenshots]) => {
        if (screenshots.length > 0) {
            markdown += `## ${category}\n\n`;

            screenshots.forEach(screenshot => {
                if (screenshot.success) {
                    markdown += `### ${screenshot.label}\n\n`;
                    markdown += `**File:** \`${screenshot.name}.png\`\n\n`;
                    markdown += `${screenshot.description}\n\n`;
                    markdown += `![${screenshot.label}](${screenshot.name}.png)\n\n`;
                    markdown += `---\n\n`;
                } else {
                    markdown += `### ❌ ${screenshot.label}\n\n`;
                    markdown += `**Error:** ${screenshot.error}\n\n`;
                    markdown += `---\n\n`;
                }
            });
        }
    });

    const docPath = path.join(CONFIG.screenshotDir, 'README.md');
    fs.writeFileSync(docPath, markdown);
    console.log(`📄 Documentation saved to: ${docPath}`);
}

// ===== MAIN SCRIPT =====

(async () => {
    console.log('🚀 Starting Screenshot Capture Script...\n');
    console.log(`📁 Screenshots will be saved to: ${CONFIG.screenshotDir}\n`);

    ensureScreenshotDir();

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const allResults = [];

    try {
        // ========== CONTEXT 1: CLIENT GUEST ==========
        console.log('\n' + '='.repeat(60));
        console.log('📱 CAPTURING CLIENT (GUEST) SCREENSHOTS');
        console.log('='.repeat(60));

        const guestContext = await browser.createBrowserContext();
        const guestPage = await guestContext.newPage();
        await guestPage.setViewport(CONFIG.viewport);

        for (const screenshot of SCREENSHOT_MANIFEST.client_guest) {
            const result = await captureHighQuality(guestPage, screenshot);
            allResults.push(result);
        }

        await guestContext.close();

        // ========== CONTEXT 2: CLIENT AUTHENTICATED ==========
        console.log('\n' + '='.repeat(60));
        console.log('👤 CAPTURING CLIENT (AUTHENTICATED) SCREENSHOTS');
        console.log('='.repeat(60));

        const clientContext = await browser.createBrowserContext();
        const clientPage = await clientContext.newPage();
        await clientPage.setViewport(CONFIG.viewport);

        const registerResult = await registerClient(clientPage);

        if (registerResult.success) {
            for (const screenshot of SCREENSHOT_MANIFEST.client_authenticated) {
                const result = await captureHighQuality(clientPage, screenshot);
                allResults.push(result);
            }
        } else {
            console.error('⚠️  Skipping authenticated client screenshots due to registration failure');
        }

        await clientContext.close();

        // ========== CONTEXT 3: ADMIN ==========
        console.log('\n' + '='.repeat(60));
        console.log('🔧 CAPTURING ADMIN SCREENSHOTS');
        console.log('='.repeat(60));

        const adminContext = await browser.createBrowserContext();
        const adminPage = await adminContext.newPage();
        await adminPage.setViewport(CONFIG.viewport);

        const loginResult = await loginAdmin(adminPage);

        if (loginResult.success) {
            for (const screenshot of SCREENSHOT_MANIFEST.admin) {
                const result = await captureHighQuality(adminPage, screenshot);
                allResults.push(result);
            }
        } else {
            console.error('⚠️  Skipping admin screenshots due to login failure');
        }

        await adminContext.close();

    } catch (error) {
        console.error('\n❌ Critical error:', error);
    } finally {
        await browser.close();
    }

    // ========== GENERATE DOCUMENTATION ==========
    console.log('\n' + '='.repeat(60));
    console.log('📊 GENERATING DOCUMENTATION');
    console.log('='.repeat(60));

    const manifest = generateManifest(allResults);
    generateMarkdownDoc(allResults);

    // ========== SUMMARY ==========
    console.log('\n' + '='.repeat(60));
    console.log('✨ SUMMARY');
    console.log('='.repeat(60));
    console.log(`Total screenshots: ${manifest.total_screenshots}`);
    console.log(`Successful: ${manifest.successful} ✅`);
    console.log(`Failed: ${manifest.failed} ❌`);
    console.log('\n✅ Screenshot capture completed!\n');
})();
