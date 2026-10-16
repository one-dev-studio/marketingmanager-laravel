@use('App\Http\Support\SubdirectoryRequestUriNormalizer')
@extends('layouts.public')

@php
    $title = 'Pricing';
@endphp

@section('content')
<section class="pricing-page-hero">
    <div class="container pricing-page-hero-grid">
        <div class="pricing-page-hero-copy">
            <p class="pricing-page-crumb">Home › Pricing</p>
            <h1 class="pricing-page-title">Plans that scale with your team</h1>
            <p class="pricing-page-lead">
                Every plan includes a 14-day trial. Pick a tier — upgrade anytime.
            </p>
            <ul class="pricing-page-highlights">
                <li>No credit card to start</li>
                <li>Cancel anytime</li>
                <li>Annual billing saves 20%</li>
            </ul>
        </div>
        <figure class="product-shot pricing-page-hero-shot">
            <img
                src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/hero-dashboard.png') }}"
                alt="Marketing Manager workspace with campaigns and channel overview"
                width="720"
                height="405"
                loading="eager"
                decoding="async"
            >
        </figure>
    </div>
</section>

<section class="pricing-plans-section" aria-label="Pricing plans">
    <div class="container">
        <div class="pricing-cards">
            <article class="pricing-card">
                <h2 class="pricing-card-name">Starter</h2>
                <p class="pricing-card-price"><span class="pricing-card-amount">$29</span><span class="pricing-card-period">/mo</span></p>
                <p class="pricing-card-tagline">Solo marketers and small teams</p>
                <ul class="pricing-card-features">
                    <li>5 social accounts</li>
                    <li>100 scheduled posts / month</li>
                    <li>Basic analytics</li>
                    <li>1 brand workspace</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-secondary btn-block">Start free trial</a>
            </article>

            <article class="pricing-card pricing-card--featured">
                <p class="pricing-card-badge">Most popular</p>
                <h2 class="pricing-card-name">Professional</h2>
                <p class="pricing-card-price"><span class="pricing-card-amount">$79</span><span class="pricing-card-period">/mo</span></p>
                <p class="pricing-card-tagline">Growing agencies and in-house teams</p>
                <ul class="pricing-card-features">
                    <li>20 social accounts</li>
                    <li>Unlimited scheduling</li>
                    <li>Advanced reporting</li>
                    <li>5 brands · 10 teammates</li>
                    <li>Approval workflows</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-primary btn-block">Start free trial</a>
            </article>

            <article class="pricing-card">
                <h2 class="pricing-card-name">Enterprise</h2>
                <p class="pricing-card-price"><span class="pricing-card-amount">$199</span><span class="pricing-card-period">/mo</span></p>
                <p class="pricing-card-tagline">Large orgs and multi-client agencies</p>
                <ul class="pricing-card-features">
                    <li>Unlimited accounts & brands</li>
                    <li>White-label reports</li>
                    <li>Dedicated support</li>
                    <li>API & custom integrations</li>
                </ul>
                <a href="{{ route('contact') }}" class="btn btn-secondary btn-block">Contact sales</a>
            </article>
        </div>
    </div>
</section>

<section class="pricing-compare" aria-labelledby="pricing-compare-title">
    <div class="container">
        <div class="section-header">
            <h2 id="pricing-compare-title" class="section-title">Compare at a glance</h2>
            <p class="section-description">Core limits — full detail inside the product.</p>
        </div>
        <div class="pricing-compare-table-wrap">
            <table class="pricing-compare-table">
                <thead>
                    <tr>
                        <th scope="col">Capability</th>
                        <th scope="col">Starter</th>
                        <th scope="col">Professional</th>
                        <th scope="col">Enterprise</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Brand workspaces</th>
                        <td>1</td>
                        <td>5</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <th scope="row">AI content credits</th>
                        <td>100 / mo</td>
                        <td>500 / mo</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <th scope="row">Email marketing contacts</th>
                        <td>—</td>
                        <td>5,000</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <th scope="row">Support</th>
                        <td>Email</td>
                        <td>Priority email &amp; chat</td>
                        <td>Dedicated account manager</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="pricing-faq" aria-labelledby="pricing-faq-title">
    <div class="container">
        <h2 id="pricing-faq-title" class="section-title">FAQ</h2>
        <div class="pricing-faq-grid">
            <article class="pricing-faq-item">
                <h3 class="pricing-faq-question">Can I change plans later?</h3>
                <p class="pricing-faq-answer">Yes — upgrades and downgrades prorate on your next invoice.</p>
            </article>
            <article class="pricing-faq-item">
                <h3 class="pricing-faq-question">Is there a free trial?</h3>
                <p class="pricing-faq-answer">14 days on every tier. No card required to explore.</p>
            </article>
            <article class="pricing-faq-item">
                <h3 class="pricing-faq-question">What payment methods work?</h3>
                <p class="pricing-faq-answer">Major cards and PayPal. Enterprise can pay by invoice.</p>
            </article>
            <article class="pricing-faq-item">
                <h3 class="pricing-faq-question">What happens to my data if I cancel?</h3>
                <p class="pricing-faq-answer">Export anytime. We retain data 30 days after cancellation.</p>
            </article>
        </div>
    </div>
</section>

<section class="cta pricing-cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to try Marketing Manager?</h2>
            <p class="cta-description">Spin up a trial org and invite your team in minutes.</p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-white btn-lg">Start free trial</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg">Talk to sales</a>
            </div>
        </div>
    </div>
</section>
@endsection
