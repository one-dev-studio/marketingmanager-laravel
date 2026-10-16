import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import path from 'path';

const base = process.env.SCREENSHOT_BASE_URL ?? 'http://127.0.0.1:8000';
const orgId = process.env.SCREENSHOT_ORG_ID ?? '1';
const outRoot =
    process.env.PHASE7_MEDIA_ROOT ??
    '/cursor/stores/bc-c472b906-674f-5af3-9de2-56d29f3b39a2/media/gap-closure/track-3';

const shots = [
    { dir: 'phase-7-landing-pages', file: 'landing-pages-index.png', path: `/main/${orgId}/landing-pages` },
    { dir: 'phase-7-landing-pages', file: 'landing-pages-builder.png', path: `/main/${orgId}/landing-pages/create` },
    { dir: 'phase-7-surveys', file: 'surveys-index.png', path: `/main/${orgId}/surveys` },
    { dir: 'phase-7-surveys', file: 'surveys-builder.png', path: `/main/${orgId}/surveys/create` },
    { dir: 'phase-7-email-templates', file: 'templates-index.png', path: `/main/${orgId}/email-marketing/templates` },
    {
        dir: 'phase-7-email-templates',
        file: 'templates-builder.png',
        path: `/main/${orgId}/email-marketing/templates/builder`,
    },
    { dir: 'phase-7-reports', file: 'reports-index.png', path: `/main/${orgId}/reports` },
    { dir: 'phase-7-reports', file: 'reports-builder.png', path: `/main/${orgId}/reports/create` },
    { dir: 'phase-7-tasks-collaboration', file: 'tasks-kanban.png', path: `/main/${orgId}/tasks` },
    { dir: 'phase-7-tasks-collaboration', file: 'collaboration-hub.png', path: `/main/${orgId}/collaboration` },
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
    await page.goto(`${base}${shot.path}`, { waitUntil: 'networkidle', timeout: 45000 });
    await page.waitForSelector('h1', { timeout: 15000 });
    await page.waitForTimeout(1500);
    const target = path.join(dir, shot.file);
    await page.screenshot({ path: target, fullPage: false });
    console.log('wrote', target);
}

await browser.close();
