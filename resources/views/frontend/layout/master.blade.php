<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $siteSettings = \App\Models\SiteSetting::current();
        try {
            $stickyNotices = ($siteSettings->show_sticky_notice ?? true) ? \App\Models\Notice::latest()->take($siteSettings->sticky_notice_limit ?? 5)->get() : collect();
        } catch (\Throwable $e) {
            $stickyNotices = collect();
        }
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $siteSettings->site_name ?? 'Bless Itahari' }} — Quality education from Playgroup to Secondary in {{ $siteSettings->contact_address ?? 'Itahari, Nepal' }}.">
    <meta name="keywords" content="{{ $siteSettings->site_short_name ?? 'Bless Itahari' }}, {{ $siteSettings->site_name ?? 'Bless Itahari' }}, Itahari, Nepal, School in Belbari">
    <title>{{ $siteSettings->site_name ?? 'Bless Itahari' }} | {{ $siteSettings->site_tagline ?? 'Itahari' }}</title>
    @php
        $favIconUrl = ($siteSettings && $siteSettings->site_favicon) ? asset($siteSettings->site_favicon) : asset('favicon.ico');
        $favIconVer = ($siteSettings && $siteSettings->updated_at) ? $siteSettings->updated_at->timestamp : '1';
    @endphp
    <link rel="icon" href="{{ $favIconUrl }}?v={{ $favIconVer }}">
    <link rel="shortcut icon" href="{{ $favIconUrl }}?v={{ $favIconVer }}">
    
    <!-- PWA Config -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="{{ $siteSettings->primary_color ?? '#1a4d8c' }}">
    <link rel="apple-touch-icon" href="{{ $siteSettings->site_favicon ? asset($siteSettings->site_favicon) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('favicon.ico')) }}?v={{ $favIconVer }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <!-- LightGallery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css">
    <!-- GPLC Brand CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}?v={{ time() }}">

    {{-- Page-level styles injected by child views --}}
    @stack('styles')
    
    <style>
        :root {
            --primary: {{ $siteSettings->primary_color ?? '#25255F' }};
            --primary-dark: {{ $siteSettings->primary_dark ?? '#141E32' }};
            --primary-light: {{ $siteSettings->primary_light ?? '#334581' }};
            --accent: {{ $siteSettings->accent_color ?? '#BB3233' }};
            --maroon: #590E1F;
            --light-green: #D6E7D4;
            --light-blue: #D6E7EE;
            --white: #FEFEFE;
        }
    </style>

    @if($siteSettings->enable_analytics && !empty($siteSettings->google_analytics_id))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->google_analytics_id }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $siteSettings->google_analytics_id }}');
    </script>
    @endif

    @if($siteSettings->enable_analytics && !empty($siteSettings->microsoft_clarity_id))
    <!-- Microsoft Clarity -->
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "{{ $siteSettings->microsoft_clarity_id }}");
    </script>
    @endif

    <!-- PWA Service Worker Registration -->
    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
          navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('ServiceWorker registration successful');
          }, function(err) {
            console.log('ServiceWorker registration failed: ', err);
          });
        });
      }
    </script>
</head>

<body>
    @include('frontend.layout.header')
    @if($stickyNotices->count())
        <aside
            class="sticky-notice-widget"
            id="stickyNoticeWidget"
            data-desktop-collapsed="{{ ($siteSettings->sticky_notice_desktop_collapsed ?? false) ? '1' : '0' }}"
            data-mobile-collapsed="{{ ($siteSettings->sticky_notice_mobile_collapsed ?? true) ? '1' : '0' }}"
        >
            <div class="sticky-notice-head">
                <h6>
                    <i class="fas fa-bell"></i>
                    <span>{{ $siteSettings->sticky_notice_title ?: 'Latest Notices' }}</span>
                    <small class="sticky-notice-badge">{{ $stickyNotices->count() }}</small>
                </h6>
                <button type="button" class="sticky-notice-toggle" id="stickyNoticeToggle" aria-label="Toggle notices">
                    <i class="fas fa-angle-right"></i>
                </button>
            </div>
            <div class="sticky-notice-body">
                @foreach($stickyNotices as $stickyNotice)
                    <a href="{{ url('notice/detail/' . $stickyNotice->id) }}" class="sticky-notice-item">
                        <div class="sticky-notice-item-title">{{ \Illuminate\Support\Str::limit($stickyNotice->title, 62) }}</div>
                        <div class="sticky-notice-item-meta">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ optional($stickyNotice->created_at)->format('d M Y') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </aside>
    @endif
    @yield('frontend-content')
    @include('frontend.layout.footer')

    <!-- WhatsApp Float -->
    @if(!empty($siteSettings->show_whatsapp_button) && !empty($siteSettings->whatsapp_number))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', (string) ($siteSettings->whatsapp_number ?? '')) }}" target="_blank" class="wa-float" title="Chat on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif
    @if($siteSettings->show_back_to_top ?? true)
        <button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
            <span class="back-ring"></span>
            <i class="fa-solid fa-arrow-up"></i>
        </button>
    @endif

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- LightGallery -->
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/zoom/lg-zoom.min.js"></script>

    <script>
        AOS.init({ once: true, duration: 680, offset: 60, easing: 'ease-out-cubic' });

        /* Hero banner */
        if (document.querySelector('.MainSwiper')) {
            new Swiper('.MainSwiper', {
                loop: true, effect: 'fade', speed: 900,
                autoplay: { delay: 5500, disableOnInteraction: false },
                pagination: { el: '.hero-pagination', clickable: true },
            });
        }
        /* Testimonial */
        if (document.querySelector('.testiSwiper')) {
            new Swiper('.testiSwiper', {
                loop: true, spaceBetween: 24,
                breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
                autoplay: { delay: 4500 },
                pagination: { el: '.testi-pag', clickable: true },
            });
        }
        /* Events */
        if (document.querySelector('.EventSwiper')) {
            new Swiper('.EventSwiper', {
                loop: true, spaceBetween: 24,
                breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
                autoplay: { delay: 4200 },
                pagination: { el: '.event-pag', clickable: true },
            });
        }
        /* Counter */
        const counterSection = document.querySelector('.sectionWorkdata');
        if (counterSection) {
            new IntersectionObserver((entries, obs) => {
                if (!entries[0].isIntersecting) return;
                document.querySelectorAll('.counter-number').forEach(el => {
                    const target = parseInt(el.dataset.number) || 0;
                    const duration = 1400;
                    const start = performance.now();
                    const animate = (now) => {
                        const progress = Math.min((now - start) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = Math.round(target * eased).toLocaleString();
                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        }
                    };
                    requestAnimationFrame(animate);
                });
                obs.unobserve(counterSection);
            }, { threshold: 0.3 }).observe(counterSection);
        }
        /* LightGallery */
        const galleryEl = document.getElementById('galleryRow');
        if (galleryEl) {
            lightGallery(galleryEl, { speed: 500, plugins: [lgZoom], download: false });
        }
        /* Sticky header */
        window.addEventListener('scroll', () => {
            document.getElementById('gplcHeader')?.classList.toggle('scrolled', window.scrollY > 60);
        }, { passive: true });
        const backToTop = document.getElementById('backToTop');
        const syncBackToTop = () => {
            backToTop?.classList.toggle('is-visible', window.scrollY > 260);
        };
        syncBackToTop();
        window.addEventListener('scroll', syncBackToTop, { passive: true });
        backToTop?.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        document.querySelectorAll('.notice-marquee').forEach((marquee) => {
            marquee.addEventListener('touchstart', () => marquee.stop(), { passive: true });
            marquee.addEventListener('touchend', () => marquee.start(), { passive: true });
            marquee.addEventListener('touchcancel', () => marquee.start(), { passive: true });
            marquee.querySelectorAll('a').forEach((link) => {
                link.addEventListener('touchstart', () => marquee.stop(), { passive: true });
                link.addEventListener('focus', () => marquee.stop());
                link.addEventListener('blur', () => marquee.start());
            });
        });
        /* Hamburger */
        document.getElementById('gplcHam')?.addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('gplcNav').classList.toggle('active');
        });
        const stickyNoticeWidget = document.getElementById('stickyNoticeWidget');
        const stickyNoticeToggle = document.getElementById('stickyNoticeToggle');
        const applyStickyNoticeState = () => {
            if (!stickyNoticeWidget || !stickyNoticeToggle) {
                return;
            }

            const isMobile = window.innerWidth <= 768;
            const shouldCollapse = isMobile
                ? stickyNoticeWidget.dataset.mobileCollapsed === '1'
                : stickyNoticeWidget.dataset.desktopCollapsed === '1';

            stickyNoticeWidget.classList.toggle('is-collapsed', shouldCollapse);
            stickyNoticeToggle.innerHTML = shouldCollapse
                ? '<i class="fas fa-angle-left"></i>'
                : '<i class="fas fa-angle-right"></i>';
        };

        applyStickyNoticeState();
        window.addEventListener('resize', applyStickyNoticeState, { passive: true });
        stickyNoticeToggle?.addEventListener('click', () => {
            stickyNoticeWidget?.classList.toggle('is-collapsed');
            stickyNoticeToggle.innerHTML = stickyNoticeWidget?.classList.contains('is-collapsed')
                ? '<i class="fas fa-angle-left"></i>'
                : '<i class="fas fa-angle-right"></i>';
        });
    </script>
    @stack('scripts')
</body>
</html>
