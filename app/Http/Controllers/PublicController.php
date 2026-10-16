<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles public marketing site pages
 */
class PublicController extends Controller
{
    /**
     * Show the homepage/landing page
     */
    public function index(): View
    {
        return view('public.home');
    }

    /**
     * Show the features page
     */
    public function features(): View
    {
        return view('public.features');
    }

    /**
     * Show the pricing page
     */
    public function pricing(): View
    {
        return view('public.pricing');
    }

    /**
     * Show the about page
     */
    public function about(): View
    {
        return view('public.about');
    }

    /**
     * Show the contact page
     */
    public function contact(): View
    {
        return view('public.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'company' => 'nullable|string|max:255',
        ]);

        ContactInquiry::create($validated);

        return back()->with('success', 'Thanks — we received your message.');
    }

    public function legal(?string $page = null): View
    {
        $page ??= request()->route('page');
        $contactUrl = route('contact');

        $pages = [
            'privacy' => [
                'heading' => 'Privacy Policy',
                'summary' => 'How Marketing Manager handles account and product data.',
                'body' => '<p>We collect information you provide when you sign up, invite teammates, and use the product — for example organization name, email address, campaign content, and usage events needed to operate the service.</p>'
                    .'<p>We use this data to deliver features, secure accounts, and improve reliability. We do not sell personal information.</p>'
                    .'<p>Organization administrators can manage many requests directly in the app. You may also <a href="'.e($contactUrl).'">contact us</a> to ask about access, correction, or deletion where applicable.</p>',
            ],
            'terms' => [
                'heading' => 'Terms of Service',
                'summary' => 'Rules for using the Marketing Manager workspace.',
                'body' => '<p>Your use of Marketing Manager is subject to your organization agreement and these terms. You are responsible for content published through connected channels and for keeping credentials secure.</p>'
                    .'<ul><li>Do not attempt to break, probe, or overload the service.</li><li>Do not upload unlawful content or infringe others’ rights.</li><li>We may suspend access for abuse or non-payment per your plan.</li></ul>',
            ],
            'cookies' => [
                'heading' => 'Cookie Policy',
                'summary' => 'Cookies on the marketing site and in the app.',
                'body' => '<p>Essential cookies keep you signed in and protect forms. Optional analytics cookies on the public site help us understand which pages are useful — you can dismiss the cookie notice on first visit.</p>'
                    .'<p>See also our <a href="'.e(route('privacy')).'">Privacy Policy</a>.</p>',
            ],
            'gdpr' => [
                'heading' => 'GDPR',
                'summary' => 'Rights for individuals in the European Economic Area and UK.',
                'body' => '<p>Depending on your relationship with us, you may have rights to access, rectify, erase, restrict, or export personal data, and to object to certain processing.</p>'
                    .'<p>Start with your organization administrator. If you cannot resolve a request internally, <a href="'.e($contactUrl).'">contact us</a> with enough detail for us to verify your identity.</p>',
            ],
            'help' => [
                'heading' => 'Help Center',
                'summary' => 'Guides for everyday marketing workflows.',
                'body' => '<p>Topics we document for teams:</p>'
                    .'<ul><li>Brands, channels, and approvals</li><li>Campaigns, calendars, and content review</li><li>Billing, seats, and organization settings</li></ul>'
                    .'<p>Need hands-on help? Use the <a href="'.e($contactUrl).'">contact form</a> and include your workspace URL.</p>',
                'cta_label' => 'Contact support',
                'cta_url' => $contactUrl,
            ],
            'blog' => [
                'heading' => 'Blog',
                'summary' => 'Product news and practical playbooks.',
                'body' => '<p>We publish release notes and workflow tips here as they are ready. For now, explore <a href="'.e(route('features')).'">features</a> or start a <a href="'.e(route('register')).'">free trial</a>.</p>',
            ],
            'careers' => [
                'heading' => 'Careers',
                'summary' => 'Build marketing software with a small, product-focused team.',
                'body' => '<p>We are not listing open roles at the moment. If you would like to introduce yourself, send a short note through the <a href="'.e($contactUrl).'">contact page</a>.</p>',
            ],
            'integrations' => [
                'heading' => 'Integrations',
                'summary' => 'Connect channels, email, storage, and billing.',
                'body' => '<p>Organizations connect social profiles, email delivery, file storage, and payment providers from in-app settings. Availability can vary by plan.</p>'
                    .'<ul><li>Social publishing and inbox tools</li><li>Email campaigns and templates</li><li>Exports to storage and reporting</li></ul>'
                    .'<p>Developers can use authenticated JSON under <code>/api</code> — see the <a href="'.e(route('developer')).'">API overview</a>.</p>',
            ],
            'api' => [
                'heading' => 'API',
                'summary' => 'Authenticated JSON for integrations and scripts.',
                'body' => '<p>Marketing Manager exposes REST-style JSON endpoints under <code>/api</code>. Authenticate with a Sanctum personal access token created from your account.</p>'
                    .'<p>Run <code>php artisan route:list</code> in your deployment for the current inventory. For questions about scopes or rate limits, <a href="'.e($contactUrl).'">contact us</a>.</p>',
            ],
        ];

        abort_unless(isset($pages[$page]), 404);

        return view('public.legal', $pages[$page] + [
            'title' => $pages[$page]['heading'],
            'cta_label' => $pages[$page]['cta_label'] ?? null,
            'cta_url' => $pages[$page]['cta_url'] ?? null,
        ]);
    }

    public function sitemap()
    {
        $pages = [
            url('/'),
            url('/features'),
            url('/pricing'),
            url('/about'),
            url('/contact'),
            url('/privacy'),
            url('/terms'),
            url('/cookies'),
            url('/gdpr'),
            url('/help'),
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($pages as $loc) {
            $xml .= '<url><loc>'.e($loc).'</loc></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
