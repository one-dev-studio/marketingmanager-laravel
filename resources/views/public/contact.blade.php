@extends('layouts.public')

@php
    $title = 'Contact';
@endphp

@section('content')
<section class="contact-page-hero">
    <div class="container">
        <p class="contact-page-crumb">Home › Contact</p>
        <h1 class="contact-page-title">Talk to our team</h1>
        <p class="contact-page-lead">
            Questions about plans, onboarding, or your workspace? Send a message — we reply by email on business days.
        </p>
    </div>
</section>

<section class="contact-page-main" aria-label="Contact form">
    <div class="container contact-page-grid">
        <div class="contact-form-panel">
            <h2 class="contact-form-heading">Send a message</h2>

            @if (session('success'))
                <p class="contact-flash-success" role="status">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div class="contact-flash-errors" role="alert">
                    <p>Please fix the highlighted fields and try again.</p>
                </div>
            @endif

            <form class="contact-form" action="{{ route('contact.submit') }}" method="POST" novalidate>
                @csrf
                <div class="contact-form-row">
                    <div class="contact-field">
                        <label for="first_name" class="contact-label">First name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="contact-input @error('first_name') contact-input-invalid @enderror"
                            value="{{ old('first_name') }}"
                            required
                            autocomplete="given-name"
                        >
                        @error('first_name')
                            <p class="contact-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="contact-field">
                        <label for="last_name" class="contact-label">Last name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="contact-input @error('last_name') contact-input-invalid @enderror"
                            value="{{ old('last_name') }}"
                            required
                            autocomplete="family-name"
                        >
                        @error('last_name')
                            <p class="contact-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="contact-field">
                    <label for="email" class="contact-label">Work email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="contact-input @error('email') contact-input-invalid @enderror"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <p class="contact-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="contact-field">
                    <label for="company" class="contact-label">Company <span class="contact-label-optional">(optional)</span></label>
                    <input
                        type="text"
                        id="company"
                        name="company"
                        class="contact-input"
                        value="{{ old('company') }}"
                        autocomplete="organization"
                    >
                </div>

                <div class="contact-field">
                    <label for="subject" class="contact-label">Topic</label>
                    <select id="subject" name="subject" class="contact-input" required>
                        <option value="" disabled @selected(old('subject') === null)>Choose a topic</option>
                        <option value="sales" @selected(old('subject') === 'sales')>Plans and pricing</option>
                        <option value="support" @selected(old('subject') === 'support')>Product support</option>
                        <option value="partnership" @selected(old('subject') === 'partnership')>Partnerships</option>
                        <option value="feedback" @selected(old('subject') === 'feedback')>Feedback</option>
                        <option value="other" @selected(old('subject') === 'other')>Other</option>
                    </select>
                </div>

                <div class="contact-field">
                    <label for="message" class="contact-label">Message</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="5"
                        class="contact-input contact-textarea @error('message') contact-input-invalid @enderror"
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="contact-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg contact-submit">Send message</button>
                <p class="contact-form-note">We typically reply within one business day.</p>
            </form>
        </div>

        <aside class="contact-aside" aria-label="Other ways to reach us">
            <div class="contact-aside-card">
                <div class="contact-aside-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <h2 class="contact-aside-title">Email first</h2>
                <p class="contact-aside-text">
                    The form is the fastest way to reach us. Include your workspace URL if you already have an account.
                </p>
            </div>

            <div class="contact-aside-card contact-aside-muted">
                <h3 class="contact-aside-subtitle">Self-serve help</h3>
                <p class="contact-aside-text">Browse guides for brands, campaigns, and billing.</p>
                <a href="{{ route('help') }}" class="btn btn-secondary">Open Help Center</a>
            </div>
        </aside>
    </div>
</section>

<section class="contact-faq-band" aria-label="Contact FAQ">
    <div class="container">
        <h2 class="section-title">Quick answers</h2>
        <div class="contact-faq-grid">
            <article class="contact-faq-card">
                <h3 class="contact-faq-question">Can I book a product walkthrough?</h3>
                <p class="contact-faq-answer">Choose <strong>Plans and pricing</strong> in the form and mention a demo — we will follow up by email.</p>
            </article>
            <article class="contact-faq-card">
                <h3 class="contact-faq-question">Where is the documentation?</h3>
                <p class="contact-faq-answer">Start in the <a href="{{ route('help') }}">Help Center</a> or the <a href="{{ route('developer') }}">API overview</a> for authenticated JSON endpoints.</p>
            </article>
        </div>
    </div>
</section>
@endsection
