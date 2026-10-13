<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}Marketing Manager — AI marketing automation</title>

    <meta name="description" content="Marketing Manager helps teams plan campaigns, create content with AI, and publish across channels from one workspace.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/public.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-brand">
                    <a href="{{ route('home') }}" class="logo" aria-label="Marketing Manager home">
                        <span class="logo-mark" aria-hidden="true">
                            <svg class="logo-mark-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                            </svg>
                        </span>
                        <span class="logo-wordmark">Marketing <span class="logo-text">Manager</span></span>
                    </a>
                </div>
                
                <div class="navbar-menu" id="navbarMenu">
                    <a href="{{ route('features') }}" class="nav-link">Features</a>
                    <a href="{{ route('pricing') }}" class="nav-link">Pricing</a>
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                    <a href="{{ route('login') }}" class="nav-link navbar-menu-auth">Log in</a>
                    <a href="{{ route('register') }}" class="nav-link navbar-menu-auth btn btn-primary btn-nav-cta">Start free trial</a>
                </div>

                <div class="navbar-actions">
                    <a href="{{ route('login') }}" class="btn btn-secondary">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Start free trial</a>
                </div>

                <button class="navbar-toggle" id="navbarToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer footer-light">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-brand">
                        <span class="logo-mark logo-mark-sm" aria-hidden="true">
                            <svg class="logo-mark-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                            </svg>
                        </span>
                        <span class="logo-wordmark footer-logo-wordmark">Marketing <span class="logo-text">Manager</span></span>
                    </div>
                    <p class="footer-description">
                        Plan campaigns, create with AI, and publish across channels — one workspace for modern marketing teams.
                    </p>
                </div>

                <div class="footer-col">
                    <h4 class="footer-title">Product</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('features') }}">Features</a></li>
                        <li><a href="{{ route('pricing') }}">Pricing</a></li>
                        <li><a href="{{ route('integrations') }}">Integrations</a></li>
                        <li><a href="{{ route('developer') }}">API</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-title">Company</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('blog') }}">Blog</a></li>
                        <li><a href="{{ route('careers') }}">Careers</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('help') }}">Help Center</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-title">Legal</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                        <li><a href="{{ route('cookies') }}">Cookie Policy</a></li>
                        <li><a href="{{ route('gdpr') }}">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Marketing Manager. All rights reserved.</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const navbarToggle = document.getElementById('navbarToggle');
        const navbarMenu = document.getElementById('navbarMenu');
        
        if (navbarToggle) {
            navbarToggle.addEventListener('click', () => {
                navbarMenu.classList.toggle('active');
                navbarToggle.classList.toggle('active');
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    
    <div id="cookie-banner" class="cookie-banner" style="display:none" role="dialog" aria-label="Cookie notice">
        <p class="cookie-banner-text">We use cookies for analytics and to improve the site. <a href="{{ route('privacy') }}">Learn more</a>.</p>
        <button type="button" class="btn btn-primary cookie-banner-accept" onclick="localStorage.setItem('mm_cookies','1');document.getElementById('cookie-banner').style.display='none'">Accept</button>
    </div>
    <script>
        if (!localStorage.getItem('mm_cookies') && !localStorage.getItem('mp_cookies')) {
            document.getElementById('cookie-banner').style.display = 'flex';
        }
    </script>
    @stack('scripts')
</body>
</html>


