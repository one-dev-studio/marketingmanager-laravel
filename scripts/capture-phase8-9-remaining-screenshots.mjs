import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import path from 'path';

const base = process.env.SCREENSHOT_BASE_URL ?? 'http://127.0.0.1:8000';
const orgId = process.env.SCREENSHOT_ORG_ID ?? '1';
const outRoot =
    process.env.PHASE89_MEDIA_ROOT ??
    '/cursor/stores/bc-c472b906-674f-5af3-9de2-56d29f3b39a2/media/gap-closure/track-3';

const agencyId = process.env.SCREENSHOT_AGENCY_ID ?? '1';

const shots = [
    { dir: 'phase-8-tools-remaining', file: 'tools-blog.png', path: `/main/${orgId}/tools/blog` },
    { dir: 'phase-8-tools-remaining', file: 'competitors-index.png', path: `/main/${orgId}/competitors` },
    { dir: 'phase-8-tools-remaining', file: 'paid-campaigns.png', path: `/main/${orgId}/paid-campaigns` },
    { dir: 'phase-8-tools-remaining', file: 'email-campaigns.png', path: `/main/${orgId}/email-marketing/campaigns` },
    { dir: 'phase-9-agency-remaining', file: 'agency-tasks.png', path: `/agency/${agencyId}/tasks` },
    { dir: 'phase-9-agency-remaining', file: 'agency-calendar.png', path: `/agency/${agencyId}/calendar` },
    { dir: 'phase-9-agency-remaining', file: 'agency-billing.png', path: `/agency/${agencyId}/billing` },
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
    await page.waitForTimeout(800);
    await page.screenshot({ path: path.join(dir, shot.file), fullPage: true });
    console.log('wrote', path.join(dir, shot.file));
}

await browser.close();
