# AGENTS.md — Engineering Guidelines for AI Agents

These standing org rules apply to any AI coding agent (Claude, Cursor, Copilot, etc.) working in this repository. Read this file fully before making changes.

**Precedence:** Sections 1–10 and the org standing notes below **override** any conflicting project-specific notes, Cursor rules, playbooks, or agent instructions in this repo. The Project-specific notes section may only add stack commands, example paths, module maps, and hard no-touch lists — it must not weaken, skip, or contradict the standing rules.

**Core principle:** Writing code is cheap; verifying it is expensive. Optimise every change for being easy to read locally, easy to check automatically, and easy to review.

---

## 1. Before you write code

- Read the relevant module and at least one existing example of what you're about to build. Copy its pattern.
- If the task is ambiguous, or touches more than one module, state your plan in a few lines before editing.
- Do not invent APIs, config keys, table columns, or package functions. Check the code, schema, or installed version first.
- Prefer the smallest change that fully solves the task. Do not refactor unrelated code in the same change.

## 2. Explicit over magic

- Make behaviour visible from the file you're in. Avoid hidden wiring: deep inheritance, runtime macros, dynamic method/property access, global helpers that hide dependencies, and "convention" auto-discovery where an explicit registration is possible.
- Inject dependencies explicitly (constructor injection). Don't reach into service locators or globals from business logic.
- Prefer composition over inheritance. Max inheritance depth: 2 (excluding framework base classes).
- No clever one-liners. Clear, boring code is the goal.

## 3. Types are guardrails

- Use the strictest type settings the language supports, and keep them passing:
  - PHP: `declare(strict_types=1);`, typed properties, params, and returns; PHPStan/Larastan at the project's configured level (aim for max).
  - TypeScript: `strict: true`; no `any` (use `unknown` and narrow); no non-null `!` assertions without a comment explaining why.
  - Dart: sound null safety; no `dynamic` unless unavoidable; `analysis_options.yaml` lints must pass.
- Pass structured data as typed objects (DTOs, value objects, records, interfaces), not loose arrays or maps.
- Use enums for fixed sets of values, not magic strings or ints.

## 4. Tests are the feedback loop

- Every behaviour change includes or updates a test. Bug fixes start with a failing test that reproduces the bug.
- Tests must be fast and deterministic: no real network calls, no reliance on current time or random values without control, no order dependence.
- Test behaviour through public interfaces, not private implementation details.
- Run the relevant tests, type checks, and linter before declaring a task done. If you cannot run them, say so explicitly.
- Never delete, skip, or weaken a test to make it pass. If a test seems wrong, flag it and explain why.

## 5. Consistency beats cleverness

- Follow the existing pattern for the thing you're building (controller, job, component, resource, repository, screen). There should be one obvious way to do each thing.
- If you believe a pattern is wrong, follow it anyway and raise the concern separately. Don't introduce a second style.
- Naming: descriptive and domain-specific (`calculateLandedCost`, not `process` or `handle2`). Match existing naming conventions exactly.
- Don't add new dependencies without saying why and checking an existing one doesn't already cover it.

## 6. Structure for locality

- Keep files small and focused: one primary responsibility per file. Guideline: split files over ~300 lines.
- Organise by feature/domain (vertical slices) where the project allows, so related code lives together.
- A little duplication within a module is acceptable. Only extract a shared abstraction when the same logic appears 3+ times *and* the cases are genuinely the same concept.
- Respect module boundaries. Modules talk to each other through their public interfaces only — never reach into another module's internals, models, or tables directly.

## 7. Errors and observability

- Fail loudly and early. Validate input at the boundary; don't let bad data travel.
- Error messages must say what went wrong *and* what to do about it, including relevant IDs/values (never secrets).
- Never swallow exceptions silently. Catch only what you can handle; otherwise let it propagate.
- Use structured logging with context (entity IDs, operation name) at important state changes.

## 8. Data and safety

- Database changes go through migrations only. Migrations must be reversible where possible and must not destroy data without an explicit instruction.
- Wrap multi-step writes that must succeed together in a transaction.
- Never commit secrets, keys, or credentials. Use environment config.
- Never run destructive commands (dropping tables, force-pushing, deleting files outside the task, resetting data) without explicit human approval.
- Treat money, stock quantities, and pricing with extra care: use decimal/integer-cents types, never floats.

## 9. Documentation in the repo

- Comments explain *why*, not *what*. Delete comments that restate the code.
- Public functions/classes with non-obvious behaviour get a short docblock.
- Significant design decisions get a short record in `docs/decisions/` (context, decision, consequences — a few paragraphs).
- If you establish a new convention, add it to this file.

## 10. Definition of done

A change is done only when:

- [ ] It solves the stated task and nothing unrelated
- [ ] It follows existing patterns in this codebase
- [ ] Types/static analysis pass
- [ ] Linter/formatter pass
- [ ] Relevant tests exist and pass
- [ ] No secrets, debug output, or commented-out code left behind
- [ ] You've summarised what changed, what you verified, and anything you're unsure about

When reporting back, be honest about what you did *not* verify.

---

## Org standing (do not weaken)

- **Prove runtime:** Suite / PHPUnit / Pint / static analysis / Spec smoke / TEST acceptance run on this app’s **Cursor Environment**. Do not burn GitHub Actions minutes on tip CI or full prove.
- **GitHub Actions:** **deploy-only** (`/deploy` and deploy-tied workflows). Quality may run on the deploy path via `workflow_call` only.
- **References:** When citing work to humans, use `App #N — short descriptive title` (plus link), never bare numbers or naked URLs alone.

---

## Project-specific notes

<!-- Fill in per project. ADDITIVE only — cannot override sections 1–10 or Org standing. -->

- **Stack:** Laravel 13 (PHP 8.3+), MySQL/PostgreSQL/SQLite, Redis (queues/cache/broadcasting), Sanctum, Spatie Permission; frontend Blade + Vue 3 + Alpine.js + Tailwind via Vite (`resources/js`, `resources/views`).
- **Run tests:** `php vendor/bin/phpunit` (config: `phpunit.xml`; suites under `tests/Unit`, `tests/Feature`, `tests/Integration`). Copy `.env.example` to `.env` and `php artisan key:generate` before first run.
- **Run type checks / static analysis:**
- **Run linter/formatter:** `./vendor/bin/pint` (Laravel Pint; no PHPStan/Larastan config in repo at time of writing).
- **Key modules and boundaries:** HTTP in `app/Http/Controllers` (thin); domain logic in `app/Services/*` (Campaign, AI, Billing, Brand, EmailMarketing, etc.); data access in `app/Repositories`; Eloquent models in `app/Models`; policies in `app/Policies`; jobs/listeners under `app/Jobs`, `app/Listeners`; Livewire under `app/Livewire`.
- **Patterns to copy (example files):** Feature tests e.g. `tests/Feature/CampaignCrudTest.php`, `tests/Feature/AiGenerationTest.php`; local setup and health check in `README.md` (`GET /up`).
- **Things to never touch without asking:** Production deploy workflows (`.github/workflows/deploy-production*.yml`); dropping/reseeding databases; payment webhook secrets and live Stripe/PayPal configuration.
