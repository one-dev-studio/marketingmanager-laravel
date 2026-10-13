@use('App\Http\Support\SubdirectoryRequestUriNormalizer')
@extends('layouts.public')

@section('content')
<!-- Hero -->
<section class="hero home-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <p class="hero-eyebrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                    </svg>
                    AI-assisted marketing workspace
                </p>
                <h1 class="hero-title">
                    Plan campaigns.<br>
                    Ship content.<br>
                    <span class="text-gradient">See what works.</span>
                </h1>
                <p class="hero-description">
                    Marketing Manager is one light hub for brands, channels, approvals, and reporting — built for agencies and in-house teams.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start free trial</a>
                    <a href="{{ route('features') }}" class="btn btn-secondary btn-lg">See product tour</a>
                </div>
            </div>
            <div class="hero-image">
                <figure class="product-shot">
                    <img
                        src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/hero-dashboard.png') }}"
                        alt="Marketing Manager dashboard showing campaigns and channel overview"
                        width="720"
                        height="405"
                        loading="eager"
                        decoding="async"
                    >
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- Logo strip -->
<section class="social-proof home-logos" aria-label="Teams using Marketing Manager">
    <div class="container">
        <p class="social-proof-title">Built for modern marketing teams</p>
        <div class="logo-wordmarks" aria-hidden="true">
            <span>Northline</span>
            <span>Studio 48</span>
            <span>Harbor Co</span>
            <span>Brightpath</span>
            <span>Elm Digital</span>
        </div>
    </div>
</section>

<!-- Feature pillars -->
<section class="features home-features" id="features">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Everything in one place</h2>
            <p class="section-description">Four pillars — shown in the product, not long copy decks.</p>
        </div>

        <div class="features-grid features-grid--home">
            <article class="feature-card">
                <div class="feature-icon feature-icon--brand">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
                        <path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/>
                    </svg>
                </div>
                <h3 class="feature-title">AI content</h3>
                <p class="feature-description">Draft posts, emails, and assets for every channel.</p>
            </article>

            <article class="feature-card">
                <div class="feature-icon feature-icon--success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                    </svg>
                </div>
                <h3 class="feature-title">Campaigns</h3>
                <p class="feature-description">Plan timelines, assign work, and launch on schedule.</p>
            </article>

            <article class="feature-card">
                <div class="feature-icon feature-icon--warning">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="feature-title">Approvals</h3>
                <p class="feature-description">Client-ready workflows with clear sign-off states.</p>
            </article>

            <article class="feature-card">
                <div class="feature-icon feature-icon--violet">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>
                    </svg>
                </div>
                <h3 class="feature-title">Insights</h3>
                <p class="feature-description">Reports and KPIs your team can share without exports.</p>
            </article>
        </div>
    </div>
</section>

<!-- Product band -->
<section class="home-product-band" aria-labelledby="home-product-band-title">
    <div class="container">
        <div class="home-product-band-inner">
            <div class="home-product-band-copy">
                <h2 id="home-product-band-title" class="section-title">Your command center</h2>
                <p class="section-description home-product-band-lead">
                    Campaigns, content, and approvals in one indigo-accented workspace — captured from a demo org (sensitive data redacted).
                </p>
                <ul class="home-product-band-list">
                    <li>Multi-brand switching without losing context</li>
                    <li>Calendar and tasks tied to live campaigns</li>
                    <li>AI assists where you want a draft, not a black box</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-primary">Create your workspace</a>
            </div>
            <figure class="product-shot product-shot--wide">
                <img
                    src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/product-workspace.png') }}"
                    alt="Marketing Manager workspace with campaign list and activity panels"
                    width="800"
                    height="500"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta home-cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to simplify your stack?</h2>
            <p class="cta-description">
                Start free with a trial workspace — explore campaigns, AI tools, and reporting with your team.
            </p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-white btn-lg">Start your free trial</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg">Talk to us</a>
            </div>
            <p class="cta-note">No credit card required for trial orgs · Cancel anytime</p>
        </div>
    </div>
</section>
@endsection
