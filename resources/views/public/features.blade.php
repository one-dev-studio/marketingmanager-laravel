@use('App\Http\Support\SubdirectoryRequestUriNormalizer')
@extends('layouts.public')

@php
    $title = 'Features';
@endphp

@section('content')
<section class="features-page-hero">
    <div class="container">
        <p class="features-page-crumb">Home › Features</p>
        <h1 class="features-page-title">Powerful features in one workspace</h1>
        <p class="features-page-lead">
            Scan the product visually — each block pairs a screenshot with three bullets max.
        </p>
    </div>
</section>

<section class="features-zigzag" aria-label="Product feature tour">
    <div class="container">
        <article class="features-zigzag-row">
            <div class="features-zigzag-copy">
                <p class="features-zigzag-label">AI content</p>
                <h2 class="features-zigzag-title">Generate on-brand drafts in seconds</h2>
                <p class="features-zigzag-text">Social, email, blog, and press — tuned to each brand voice.</p>
                <ul class="features-zigzag-list">
                    <li>Channel-specific formats</li>
                    <li>Revision history and approvals</li>
                    <li>Guardrails per organization</li>
                </ul>
            </div>
            <figure class="product-shot features-zigzag-shot">
                <img
                    src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/hero-dashboard.png') }}"
                    alt="Marketing Manager AI content workspace in the demo org"
                    width="720"
                    height="405"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </article>

        <article class="features-zigzag-row features-zigzag-row--reverse">
            <div class="features-zigzag-copy">
                <p class="features-zigzag-label">Campaigns</p>
                <h2 class="features-zigzag-title">Plan, assign, and launch together</h2>
                <p class="features-zigzag-text">Kanban, calendar, and tasks without leaving the brand context.</p>
                <ul class="features-zigzag-list">
                    <li>Multi-brand workspaces</li>
                    <li>Scheduled publishing</li>
                    <li>Client review links</li>
                </ul>
            </div>
            <figure class="product-shot features-zigzag-shot">
                <img
                    src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/product-workspace.png') }}"
                    alt="Marketing Manager kanban task board in the demo workspace"
                    width="720"
                    height="405"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </article>

        <article class="features-zigzag-row">
            <div class="features-zigzag-copy">
                <p class="features-zigzag-label">Insights</p>
                <h2 class="features-zigzag-title">See what is working, share it fast</h2>
                <p class="features-zigzag-text">Dashboards and exports your team actually opens.</p>
                <ul class="features-zigzag-list">
                    <li>Campaign-level KPIs</li>
                    <li>Cross-channel rollups</li>
                    <li>Shareable report links</li>
                </ul>
            </div>
            <figure class="product-shot features-zigzag-shot">
                <img
                    src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/product-insights.png') }}"
                    alt="Marketing Manager insights and reporting view in the demo workspace"
                    width="720"
                    height="405"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </article>
    </div>
</section>

<section class="features-pillars" aria-labelledby="features-pillars-title">
    <div class="container">
        <div class="section-header">
            <h2 id="features-pillars-title" class="section-title">All the essentials</h2>
            <p class="section-description">Four tiles — one line each. Details live in the product.</p>
        </div>
        <div class="features-grid features-grid--home features-grid--features">
            <article class="feature-card">
                <div class="feature-icon feature-icon--brand">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3v18"/><path d="M3 12h18"/></svg>
                </div>
                <h3 class="feature-title">Multi-brand</h3>
                <p class="feature-description">Switch brands without losing campaign context.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon feature-icon--success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <h3 class="feature-title">Collaboration</h3>
                <p class="feature-description">Approvals, comments, and roles per workspace.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon feature-icon--warning">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                </div>
                <h3 class="feature-title">Scheduling</h3>
                <p class="feature-description">Calendar publishing across channels.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon feature-icon--violet">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                </div>
                <h3 class="feature-title">Security</h3>
                <p class="feature-description">Org boundaries, audit-friendly permissions.</p>
            </article>
        </div>
    </div>
</section>

<section class="features-integrations" aria-label="Integrations">
    <div class="container">
        <h2 class="section-title">Plays well with your stack</h2>
        <p class="section-description">Connect social, email, and analytics tools as you grow.</p>
        <div class="features-integration-grid">
            <div class="features-integration-tile">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <span>Email</span>
            </div>
            <div class="features-integration-tile">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                <span>Social</span>
            </div>
            <div class="features-integration-tile">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11 13v8"/></svg>
                <span>Ads</span>
            </div>
            <div class="features-integration-tile">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-3"/></svg>
                <span>Analytics</span>
            </div>
        </div>
    </div>
</section>

<section class="cta features-cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to see it in your workspace?</h2>
            <p class="cta-description">
                Start a free trial org and explore campaigns, AI assists, and reporting with your team.
            </p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-white btn-lg">Start free trial</a>
                <a href="{{ route('pricing') }}" class="btn btn-outline-white btn-lg">View pricing</a>
            </div>
        </div>
    </div>
</section>
@endsection
