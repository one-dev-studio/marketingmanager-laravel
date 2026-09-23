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
        $pages = [
            'privacy' => [
                'heading' => 'Privacy Policy',
                'body' => '<p>MarketPulse collects account, organization, and usage data to provide the product. We do not sell personal data. Contact us to request access or deletion.</p>',
            ],
            'terms' => [
                'heading' => 'Terms of Service',
                'body' => '<p>Use of MarketPulse is subject to your organization agreement. Do not misuse the service or attempt unauthorized access.</p>',
            ],
            'cookies' => [
                'heading' => 'Cookie Policy',
                'body' => '<p>We use essential cookies for authentication and optional analytics cookies to improve the site.</p>',
            ],
            'gdpr' => [
                'heading' => 'GDPR',
                'body' => '<p>European users can request export or deletion of personal data from their organization admin or by contacting us.</p>',
            ],
            'help' => [
                'heading' => 'Help Center',
                'body' => '<p>Find guides for brands, campaigns, channels, and billing. For account help, use the contact form.</p><p><a href="'.e(url('/contact')).'">Contact support</a></p>',
            ],
            'blog' => [
                'heading' => 'Blog',
                'body' => '<p>Product updates and marketing playbooks will appear here.</p>',
            ],
            'careers' => [
                'heading' => 'Careers',
                'body' => '<p>We are not listing open roles right now. Send a note via the contact page if you would like to work with us.</p>',
            ],
            'integrations' => [
                'heading' => 'Integrations',
                'body' => '<p>Connect social channels, email, storage, and billing providers from your organization settings.</p>',
            ],
            'api' => [
                'heading' => 'API',
                'body' => '<p>Authenticated JSON APIs are available under <code>/api</code>. Use a Sanctum token from your account.</p>',
            ],
        ];

        abort_unless(isset($pages[$page]), 404);

        return view('public.legal', $pages[$page] + ['title' => $pages[$page]['heading']]);
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


