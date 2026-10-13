import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import path from 'path';

const base = process.env.SCREENSHOT_BASE_URL ?? 'http://127.0.0.1:8000';
const orgId = process.env.SCREENSHOT_ORG_ID ?? '1';
const outRoot =
    process.env.PHASE8_MEDIA_ROOT ??
    '/cursor/stores/bc-c472b906-674f-5af3-9de2-56d29f3b39a2/media/gap-closure/track-3';

const shots = [
    { dir: 'phase-8-workflows-chatbots', file: 'workflows-index.png', path: `/main/${orgId}/workflows` },
    { dir: 'phase-8-workflows-chatbots', file: 'workflows-builder.png', path: `/main/${orgId}/workflows/builder` },
    { dir: 'phase-8-workflows-chatbots', file: 'chatbots-index.png', path: `/main/${orgId}/chatbots` },
    { dir: 'phase-8-workflows-chatbots', file: 'chatbots-builder.png', path: `/main/${orgId}/chatbots/builder` },
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

for (const shot of shots) {
    const dir = path.join(outRoot, shot.dir);
    await mkdir(dir, { recursive: true });
    await page.goto(`${base}${shot.path}`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
    await page.screenshot({ path: path.join(dir, shot.file), fullPage: true });
    console.log('wrote', path.join(dir, shot.file));
}

await browser.close();
