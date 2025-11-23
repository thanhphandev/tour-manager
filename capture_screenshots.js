import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const BASE_URL = 'http://localhost:8000';
const SCREENSHOT_DIR = 'C:\\Users\\THANH\\.gemini\\antigravity\\brain\\f5b43289-6ca8-4ef6-b8d3-5b6ec114cdda\\screenshots';

if (!fs.existsSync(SCREENSHOT_DIR)) {
    fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

async function capture(page, url, name) {
    console.log(`Navigating to ${url}...`);
    try {
        await page.goto(BASE_URL + url, { waitUntil: 'networkidle0', timeout: 30000 });
        await page.setViewport({ width: 1366, height: 768 });
        // Hide debug bar if present
        await page.evaluate(() => {
            const debugBar = document.querySelector('.phpdebugbar');
            if (debugBar) debugBar.style.display = 'none';
        });
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, `${name}.png`) });
        console.log(`Captured ${name}.png`);
    } catch (e) {
        console.error(`Failed to capture ${name} (${url}):`, e.message);
    }
}

(async () => {
    const browser = await puppeteer.launch({ headless: true });

    // --- Context 1: Client (Guest & Registered) ---
    const clientContext = await browser.createBrowserContext();
    const clientPage = await clientContext.newPage();

    console.log('--- Starting Client Screenshots ---');
    await capture(clientPage, '/', '01_Homepage');
    await capture(clientPage, '/destinations', '02_Destinations');
    await capture(clientPage, '/destinations/vinh-ha-long', '03_Destination_Detail');
    await capture(clientPage, '/tours', '04_Tours');
    await capture(clientPage, '/tours/hoi-an-co-kinh-van-hoa-da-nang', '05_Tour_Detail');
    await capture(clientPage, '/login', '09_Login');
    await capture(clientPage, '/register', '09_Register');

    // Register
    console.log('Registering new client user...');
    try {
        await clientPage.goto(BASE_URL + '/register', { waitUntil: 'networkidle0' });
        await clientPage.type('input[name="name"]', 'Client User');
        await clientPage.type('input[name="email"]', 'client_' + Date.now() + '@example.com');
        await clientPage.type('input[name="password"]', 'password');
        await clientPage.type('input[name="password_confirmation"]', 'password');
        await Promise.all([
            clientPage.waitForNavigation({ waitUntil: 'networkidle0' }),
            clientPage.click('button[type="submit"]'), // Adjust selector if needed
        ]);
        console.log('Registered and logged in as Client.');

        await capture(clientPage, '/profile', '10_Profile');
        await capture(clientPage, '/bookings/history', '11_Booking_History');
        // Use slug for booking create if needed, or ID if route uses ID. 
        // Route: Route::get('/{tour:slug}/book', ...). So needs slug.
        await capture(clientPage, '/tours/hoi-an-co-kinh-van-hoa-da-nang/book', '06_Booking_Form');
    } catch (e) {
        console.error('Client registration/login failed:', e.message);
    }
    await clientContext.close();

    // --- Context 2: Admin ---
    const adminContext = await browser.createBrowserContext();
    const adminPage = await adminContext.newPage();

    console.log('--- Starting Admin Screenshots ---');
    try {
        await adminPage.goto(BASE_URL + '/login', { waitUntil: 'networkidle0' });
        await adminPage.type('input[name="email"]', 'thanhphanvan1610@gmail.com');
        await adminPage.type('input[name="password"]', '123');
        await Promise.all([
            adminPage.waitForNavigation({ waitUntil: 'networkidle0' }),
            adminPage.click('button[type="submit"]'),
        ]);
        console.log('Logged in as Admin.');

        await capture(adminPage, '/admin/dashboard', '14_Admin_Dashboard');
        await capture(adminPage, '/admin/tours', '15_Admin_Tours');
        await capture(adminPage, '/admin/tours/create', '16_Admin_Tour_Create');

        // Corrected: Use slug for tour images
        await capture(adminPage, '/admin/tours/hoi-an-co-kinh-van-hoa-da-nang/images', '17_Admin_Tour_Images');

        await capture(adminPage, '/admin/destinations', '18_Admin_Destinations');
        await capture(adminPage, '/admin/bookings', '19_Admin_Bookings');

        // Corrected: Use booking_code for booking detail
        await capture(adminPage, '/admin/bookings/BOOK12345', '20_Admin_Booking_Detail');

        await capture(adminPage, '/admin/users', '21_Admin_Users');
        await capture(adminPage, '/admin/reviews', '22_Admin_Reviews');
        await capture(adminPage, '/admin/reports/revenue', '23_Admin_Revenue');
        await capture(adminPage, '/admin/activity-logs', '24_Admin_Activity_Logs');
        await capture(adminPage, '/admin/settings', '25_Admin_Settings');
    } catch (e) {
        console.error('Admin login/capture failed:', e.message);
    }
    await adminContext.close();

    await browser.close();
})();
