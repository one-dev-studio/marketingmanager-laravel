import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import path from 'path';

const base = process.env.APP_URL ?? 'http://127.0.0.1:8000';
const orgId = process.env.SCREENSHOT_ORG_ID ?? '1';
const brandId = process.env.SCREENSHOT_BRAND_ID ?? '1';
const outRoot =
    process.env.MEDIA_ROOT ??
    '/cursor/stores/bc-c472b906-674f-5af3-9de2-56d29f3b39a2/media/gap-closure/track-3/phase-11-legacy-app-blade-cleanup';

const shots = [
    { file: 'brands-choose-name.png', path: `/main/${orgId}/brands/choose-name` },
    { file: 'files-show.png', path: `/main/${orgId}/files/1?brandId=${brandId}` },
];

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await context.newPage();

await page.goto(`${base}/login`, { waitUntil: 'networkidle' });
await page.fill('#email', process.env.SCREENSHOT_EMAIL ?? 'demo@test.com');
await page.fill('#password', process.env.SCREENSHOT_PASSWORD ?? 'password123');
await page.click('button[type=submit]');
await page.waitForURL(/\/main\//, { timeout: 20000 });
await page.evaluate(() => localStorage.setItem('mm_cookies', 'accepted'));

await mkdir(outRoot, { recursive: true });

for (const shot of shots) {
    await page.goto(`${base}${shot.path}`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(800);
    const bodyText = await page.locator('body').innerText();
    if (bodyText.trim().length < 20) {
        throw new Error(`Blank or tiny page for ${shot.file}`);
    }
    await page.screenshot({ path: path.join(outRoot, shot.file), fullPage: true });
    console.log('wrote', path.join(outRoot, shot.file));
}

await browser.close();
