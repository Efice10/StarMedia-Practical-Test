<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Social Share Tracker - Star Media Group</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/css/pages/demo.css','resources/js/app.js'])
</head>
<body>
    <nav class="nav">
        <div class="nav-content">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <span>ShareTracker</span>
            </div>
            <a href="/login" class="admin-btn">
                <i class="fas fa-lock"></i>
                Admin Dashboard
            </a>
        </div>
    </nav>

    <div class="hero">
        <div class="container">
            <div class="hero-badge">
                <i class="fas fa-bolt"></i>
                Star Media Group Project
            </div>
            <h1>Track Social Engagement<br><span class="gradient-text">Like Never Before</span></h1>
            <p>
                A powerful social share tracking system built with Laravel and modern architecture.
                Real-time analytics, beautiful dashboards, and enterprise-grade security.
            </p>
            <a href="#share" class="hero-cta">
                <i class="fas fa-arrow-down"></i>
                Try Share Buttons
            </a>
        </div>
    </div>

    <div class="container content-wrapper">
        <div class="content-grid">
            <div class="article-card">
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>{{ date('F d, Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-user"></i>
                        <span>Demo Team</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>3 min read</span>
                    </div>
                </div>

                <h2>Experience Next-Gen Social Tracking</h2>

                <div class="article-body">
                    <p>
                        Welcome to our cutting-edge social share tracking system. This platform demonstrates
                        enterprise-level architecture with Laravel, featuring Clean Architecture principles
                        and SOLID design patterns.
                    </p>

                    <p><strong>What Makes This Special?</strong></p>
                    <ul>
                        <li>Real-time tracking across multiple social platforms</li>
                        <li>Advanced analytics with interactive charts and filters</li>
                        <li>Role-based access control with granular permissions</li>
                        <li>Beautiful, responsive design for any device</li>
                        <li>Enterprise-grade security and validation</li>
                        <li>Flexible database schema for easy scaling</li>
                    </ul>

                    <p>
                        Try clicking any share button below to see our tracking system in action.
                        Each click is instantly recorded and ready for analysis in the admin dashboard!
                    </p>
                </div>
            </div>

            <div class="sidebar">
                <div class="stats-card">
                    <h3><i class="fas fa-chart-line"></i> Real-Time Analytics</h3>
                    <p>Every share is tracked, analyzed, and visualized in beautiful charts and graphs</p>
                </div>

                <div class="info-card">
                    <h4>Key Features</h4>
                    <ul>
                        <li><i class="fas fa-chart-line"></i> Advanced Analytics</li>
                        <li><i class="fas fa-shield-alt"></i> Enterprise Security</li>
                        <li><i class="fas fa-mobile-alt"></i> Fully Responsive</li>
                        <li><i class="fas fa-bolt"></i> Lightning Fast</li>
                        <li><i class="fas fa-code"></i> Clean Architecture</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="share-section" id="share">
            <h3>Share This Page</h3>
            <div class="social-share-buttons" id="shareButtons"></div>
        </div>

        <div class="section-header">
            <h2>Built for Performance & Scale</h2>
            <p>Enterprise-grade features designed for modern web applications</p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-rocket" aria-hidden="true"></i>
                </div>
                <h4>Lightning Fast</h4>
                <p>Optimized performance with efficient database queries and caching strategies for instant results</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                </div>
                <h4>Secure & Protected</h4>
                <p>Enterprise-grade security with authentication, authorization, and comprehensive input validation</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-bar" aria-hidden="true"></i>
                </div>
                <h4>Deep Insights</h4>
                <p>Comprehensive analytics with customizable date ranges and platform filters for detailed analysis</p>
            </div>
        </div>
    </div>

    <div class="notification" id="notification">
        Share tracked successfully!
    </div>

    <script>
        const API_URL = '/api';
        let platforms = [];
        // Share behavior modes:
        // 'redirect' -> navigate current tab to share URL
        // 'new_tab'  -> open share URL in a new browser tab (keep user on page)
        // 'popup'    -> open centered popup window (classic share dialog style)
        const SHARE_BEHAVIOR = 'new_tab';

        async function loadPlatforms() {
            try {
                const response = await fetch(`${API_URL}/social-shares/platforms`);
                const data = await response.json();

                if (data.success) {
                    platforms = data.data.platforms;
                    renderShareButtons();
                }
            } catch (error) {
                console.error('Error loading platforms:', error);
            }
        }

        function getShareUrl(platformName) {
            const currentUrl = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
                const bodyCombined = encodeURIComponent(`${document.title}\n\n${window.location.href}`);

            const shareUrls = {
                facebook: `https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`,
                x: `https://twitter.com/intent/tweet?url=${currentUrl}&text=${title}`,
                whatsapp: `https://wa.me/?text=${title}%20${currentUrl}`,
                telegram: `https://t.me/share/url?url=${currentUrl}&text=${title}`,
                    // Open Gmail compose window directly (user must be logged in). Subject = title, body = title + URL.
                    // Fallback: if Gmail blocked or user wants native client, replace with mailto variant above.
                    email: `https://mail.google.com/mail/?view=cm&fs=1&to=&su=${title}&body=${bodyCombined}`
            };

            return shareUrls[platformName] || '#';
        }

        function renderShareButtons() {
            const container = document.getElementById('shareButtons');
            container.innerHTML = '';

            platforms.forEach(platform => {
                const btn = document.createElement('a');
                btn.className = 'share-btn';
                btn.style.background = `linear-gradient(135deg, ${platform.color}, ${adjustColor(platform.color, -15)})`;
                const shareHref = getShareUrl(platform.name);
                // Always provide href for accessibility / long-press copying
                btn.href = shareHref;
                btn.rel = 'noopener noreferrer';
                btn.target = (SHARE_BEHAVIOR === 'redirect') ? '_self' : '_blank';
                btn.innerHTML = `<i class="${platform.icon}"></i> <span>${platform.display_name}</span>`;

                btn.addEventListener('click', (e) => {
                    // Email: let default behavior happen; track then continue
                    if (platform.name === 'email') {
                        trackShareFast(platform.id, platform.display_name);
                        return; // allow default
                    }

                    if (SHARE_BEHAVIOR === 'redirect') {
                        // Direct navigation in same tab; just fire tracking and allow default
                        trackShareFast(platform.id, platform.display_name);
                        return;
                    }

                    // For new_tab or popup we manage opening ourselves
                    e.preventDefault();
                    trackShareFast(platform.id, platform.display_name);

                    if (SHARE_BEHAVIOR === 'popup') {
                        openSharePopup(shareHref);
                    } else { // new_tab
                        window.open(shareHref, '_blank', 'noopener');
                    }
                });

                container.appendChild(btn);
            });
        }

        function trackShareFast(platformId, platformName) {
            const payload = JSON.stringify({
                url: window.location.href,
                page_title: document.title,
                social_platform_id: platformId,
                metadata: { platform_name: platformName, shared_at: new Date().toISOString(), mode: SHARE_BEHAVIOR }
            });

            const endpoint = `${API_URL}/social-shares`;
            // Try sendBeacon first (must use Blob with proper type)
            let sent = false;
            try {
                if (navigator.sendBeacon) {
                    const blob = new Blob([payload], { type: 'application/json' });
                    sent = navigator.sendBeacon(endpoint, blob);
                }
            } catch (_) { /* ignore */ }
            if (!sent) {
                // Fallback: fire a keepalive fetch (so it continues after navigation begins)
                try { fetch(endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: payload, keepalive: true }); } catch (_) {}
            }
        }

        function openSharePopup(url) {
            const w = 600; const h = 520;
            const left = (window.screen.width - w) / 2;
            const top = (window.screen.height - h) / 2;
            window.open(url, '_blank', `noopener,noreferrer,width=${w},height=${h},left=${left},top=${top}`);
        }

        function adjustColor(color, percent) {
            const num = parseInt(color.replace("#",""), 16);
            const amt = Math.round(2.55 * percent);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 +
                (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255))
                .toString(16).slice(1);
        }

        async function trackShare(platformId, platformName) {
            try {
                const response = await fetch(`${API_URL}/social-shares`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        url: window.location.href,
                        page_title: document.title,
                        social_platform_id: platformId,
                        metadata: {
                            platform_name: platformName,
                            shared_at: new Date().toISOString()
                        }
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(`Shared via ${platformName}!`);
                }
            } catch (error) {
                console.error('Error tracking share:', error);
            }
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.innerHTML = `<i class=\"fas fa-check-circle\" aria-hidden=\"true\"></i> ${message}`;
            notification.style.display = 'block';
            setTimeout(() => { notification.style.display = 'none'; }, 3000);
        }

        document.addEventListener('DOMContentLoaded', loadPlatforms);
    </script>
</body>
</html>
