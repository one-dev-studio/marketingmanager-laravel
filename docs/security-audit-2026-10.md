# Marketing Manager — Security audit report (October 2026)

**Repository:** [one-dev-studio/marketingmanager-laravel](https://github.com/one-dev-studio/marketingmanager-laravel)  
**Audit baseline SHA:** `7b55d87da7498d1c4dc544eba0f86f3555ff5bd5`  
**Staging tip after npm remediation:** _(pending merge — see PR in npm section)_  
**Host probe:** `https://marketing-manager.staging.oneinternetmedia.com` (read-only)  
**Last updated:** 2026-10-15

---

## Critical summary (no secret values)

| # | Finding | Status |
|---|---------|--------|
| C1 | **Billing webhooks accepted forged JSON** — Stripe checked only for a missing signature header; PayPal had no verification. | **Fixed** — [#76](https://github.com/one-dev-studio/marketingmanager-laravel/pull/76) |

No **open Critical** findings remain in application code at staging tip, provided CoS configures webhook secrets on the server (see appendix).

---

## Findings register

| Severity | Finding | Location / URL | Fix status | PR |
|----------|---------|----------------|------------|-----|
| Critical | Forged Stripe/PayPal billing webhooks could flip invoice/payment state | `PaymentWebhookController`, `WebhookVerificationGate` | Merged | [#76](https://github.com/one-dev-studio/marketingmanager-laravel/pull/76) |
| High | Webhook POSTs hit **419 CSRF** on staging instead of signature rejection | `bootstrap/app.php` | **Fixed** | [#79](https://github.com/one-dev-studio/marketingmanager-laravel/pull/79) |
| High | Social platform webhooks without signature verification | `SocialMedia/WebhookController`, `SocialWebhookVerificationGate` | **Fixed** — Meta `X-Hub-Signature-256`, Twitter, LinkedIn, TikTok, Pinterest headers | [#81](https://github.com/one-dev-studio/marketingmanager-laravel/pull/81) |
| High | PhpSpreadsheet CVEs (SSRF/DoS) | `composer.lock` | Merged bump to 1.30.7 | [#78](https://github.com/one-dev-studio/marketingmanager-laravel/pull/78) |
| High | Guzzle host/cookie advisories | `composer.lock` | Addressed in #78; re-run `composer audit` periodically | [#78](https://github.com/one-dev-studio/marketingmanager-laravel/pull/78) |
| High | Stored HTML on public landing pages — XSS if org user embeds script | `public.blade.php`, `UserHtmlSanitizer`, save paths | **Fixed** — HTMLPurifier on save + public render | [#81](https://github.com/one-dev-studio/marketingmanager-laravel/pull/81) |
| Medium | CSP allows `unsafe-inline` and `unsafe-eval` for scripts | `SecurityHeaders` | **Improved** — Vite nonces; `unsafe-eval` only in `local`; styles still allow `unsafe-inline` for Tailwind | [#81](https://github.com/one-dev-studio/marketingmanager-laravel/pull/81) |
| Medium | HSTS not sent when `APP_ENV=staging` | `SecurityHeaders` | **Fixed** | [#77](https://github.com/one-dev-studio/marketingmanager-laravel/pull/77) |
| Medium | Builder previews use `dangerouslySetInnerHTML` / `v-html` | Inertia/Vue builder panels | Open — scoped to authenticated builders | — |
| Medium | PayPal webhook logs `event_type` at info level | `PaymentWebhookController` | Accepted | — |
| Low | Pagination labels use framework HTML entities | Inertia index pages | **Fixed** — `paginationLabel.ts` | [#81](https://github.com/one-dev-studio/marketingmanager-laravel/pull/81) |
| Low | `/up` health endpoint public (200) | staging host | Accepted | — |
| Info | `gitleaks detect`: no leaks in sampled history | git | — | — |
| Info | Staging sensitive paths → **404** | host probe | — | — |

### npm / frontend toolchain (2026-10-15)

| Item | Status |
|------|--------|
| Prior state (#81) | `npm audit fix` (non-breaking) left **11** advisories (6 high: braces→chokidar→Tailwind 3; moderate: esbuild/Vite 5, postcss-selector-parser) |
| Remediation | **Vite 8.3.4**, **Tailwind CSS 4.3** (`@tailwindcss/vite`), plugin majors (`@vitejs/plugin-vue` 6, `@vitejs/plugin-react` 6, `laravel-vite-plugin` 3); `postcss-selector-parser` **override** `^7.1.6` for `@tailwindcss/typography` transitive dep |
| `npm audit` (2026-10-15, after `npm ci`) | **0 vulnerabilities** |
| Prove (Cursor Environment) | `npm ci`, `npm audit`, `npm run typecheck`, `npm run build` — pass. `php vendor/bin/phpunit` requires **PHP ≥8.4** per `composer.lock` (Symfony 8.1); Ubuntu 24.04 agent image ships PHP 8.3 only — run full suite on deploy host or PHP 8.4+ dev box |
| Runtime on server | PHP/Laravel only; JS toolchain is dev/build-time |

---

## Merged fix PRs (staging)

| PR | Title | Merge commit on `staging` |
|----|-------|---------------------------|
| [#76](https://github.com/one-dev-studio/marketingmanager-laravel/pull/76) | Billing webhook signature verification | `f2ba98023d3ad8b665a1b067fe012183a16701c7` |
| [#77](https://github.com/one-dev-studio/marketingmanager-laravel/pull/77) | HSTS on staging HTTPS | `659e3744705dbe09c487a30e0d0642dada39bca2` |
| [#78](https://github.com/one-dev-studio/marketingmanager-laravel/pull/78) | Composer dependency security bumps | `91960321c4df4a765b8e809fc0fded9e5c292f0a` |
| [#79](https://github.com/one-dev-studio/marketingmanager-laravel/pull/79) | Webhook CSRF exemption | `17c1c49c07b9f0abeaf4544be04a0fc430a59ded` |
| [#81](https://github.com/one-dev-studio/marketingmanager-laravel/pull/81) | Social webhooks, HTML sanitization, CSP nonces, npm lock refresh | `ede3653c07b9f0abeaf4544be04a0fc430a59ded` |

---

## What remains

1. **CoS redeploy** staging to `ede3653…` and configure social webhook secrets (appendix).
2. **Authenticated builder XSS** — product risk acceptance or further sandboxing for `v-html` / `dangerouslySetInnerHTML` in admin UI.
3. **CSP** — remove `style-src 'unsafe-inline'` when Tailwind build emits hashed/nonce styles.
4. **Integration tests** — `PaymentProcessingTest` routes/assertions may be stale (pre-existing); full `phpunit` needs PHP ≥8.4 locally.

---

## Appendix — CoS handoff (no secret values)

| Item | Action |
|------|--------|
| Staging `.env` | `STRIPE_WEBHOOK_SECRET`, PayPal webhook keys (see prior audit). |
| Social webhooks | `FACEBOOK_WEBHOOK_APP_SECRET`, `FACEBOOK_WEBHOOK_VERIFY_TOKEN`, `TWITTER_CONSUMER_SECRET`, `LINKEDIN_WEBHOOK_SECRET`, `TIKTOK_CLIENT_SECRET`, `PINTEREST_WEBHOOK_SECRET` (names in `.env.example`). |
| Deploy | Staging deploy after tip includes #81; post-deploy smoke **`GET /up` only**. |
| Webhook URLs | Register platform callbacks to `https://…/webhooks/{facebook,instagram,linkedin,twitter,tiktok,pinterest}`. |

**Deploy workflow:** `.github/workflows/deploy-staging.yml` — review only; production workflows unchanged.
