@use('App\Http\Support\SubdirectoryRequestUriNormalizer')
@extends('layouts.public')

@php
    $title = 'About';
@endphp

@section('content')
<section class="about-page-hero">
    <div class="container">
        <p class="about-page-crumb">Home › About</p>
        <h1 class="about-page-title">Built for marketers who ship</h1>
        <p class="about-page-lead">
            Marketing Manager is one workspace for brands, campaigns, AI assists, and reporting — designed for agencies and in-house teams.
        </p>
    </div>
</section>

<section class="about-story" aria-label="Our story">
    <div class="container">
        <article class="about-zigzag-row">
            <div class="about-zigzag-copy">
                <p class="about-zigzag-label">Why we exist</p>
                <h2 class="about-zigzag-title">Less tool juggling, more publishing</h2>
                <p class="about-zigzag-text">Teams were stitching spreadsheets, chat threads, and five apps together just to launch a campaign.</p>
                <ul class="about-zigzag-list">
                    <li>One hub per brand and client</li>
                    <li>Approvals clients actually use</li>
                    <li>Reporting you can share in a link</li>
                </ul>
            </div>
            <figure class="product-shot about-zigzag-shot">
                <img
                    src="{{ SubdirectoryRequestUriNormalizer::rootRelativePublicPath('images/marketing/product-workspace.png') }}"
                    alt="Marketing Manager kanban board in the demo workspace"
                    width="720"
                    height="405"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </article>
    </div>
</section>

<section class="about-approach" aria-labelledby="about-approach-title">
    <div class="container">
        <div class="section-header">
            <h2 id="about-approach-title" class="section-title">How we build</h2>
            <p class="section-description">Principles and pace — not vanity metrics.</p>
        </div>
        <ul class="about-zigzag-list about-approach-list">
            <li>Listen to agencies and in-house teams about where campaigns stall.</li>
            <li>Ship workflows, AI assists, and reporting in one workspace.</li>
            <li>Improve from real usage — not invented benchmarks.</li>
        </ul>
    </div>
</section>

<section class="about-values" aria-labelledby="about-values-title">
    <div class="container">
        <div class="section-header">
            <h2 id="about-values-title" class="section-title">What we stand for</h2>
            <p class="section-description">Four principles — one line each.</p>
        </div>
        <div class="about-values-grid">
            <article class="about-value-card">
                <div class="feature-icon feature-icon--brand" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18"/><path d="M3 12h18"/></svg>
                </div>
                <h3 class="feature-title">Ship faster</h3>
                <p class="feature-description">Defaults that match how marketing teams actually work.</p>
            </article>
            <article class="about-value-card">
                <div class="feature-icon feature-icon--success" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <h3 class="feature-title">Customer-first</h3>
                <p class="feature-description">Your outcomes drive our roadmap — not feature checklists.</p>
            </article>
            <article class="about-value-card">
                <div class="feature-icon feature-icon--warning" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                </div>
                <h3 class="feature-title">Trust & security</h3>
                <p class="feature-description">Org boundaries, roles, and audit-friendly permissions.</p>
            </article>
            <article class="about-value-card">
                <div class="feature-icon feature-icon--violet" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
                </div>
                <h3 class="feature-title">Practical AI</h3>
                <p class="feature-description">Assistive drafts with guardrails — not black-box magic.</p>
            </article>
        </div>
    </div>
</section>

<section class="cta about-cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Join the teams shipping on Marketing Manager</h2>
            <p class="cta-description">Start a trial or say hello — we read every message.</p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-white btn-lg">Start free trial</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg">Contact us</a>
            </div>
        </div>
    </div>
</section>
@endsection
