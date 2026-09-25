@php
    try {
        $siteSettings = \App\Models\SiteSetting::current();
    } catch (\Throwable $e) {
        $siteSettings = null;
    }

    try {
        $navCourses = \App\Models\Course::where('status', 1)->get();
    } catch (\Throwable $e) {
        $navCourses = collect([]);
    }

    try {
        $navbarMenus = \Illuminate\Support\Facades\Schema::hasTable('navbar_menus')
            ? \App\Models\NavbarMenu::where('status', 1)->orderBy('order', 'asc')->get()
            : collect([]);
    } catch (\Throwable $e) {
        $navbarMenus = collect([]);
    }

    // Fetch marquee notice directly in header so it's available on all pages
    try {
        $headerMarqueeNotice = \App\Models\Notice::where('show_in', 'm')->latest()->first();
    } catch (\Throwable $e) {
        $headerMarqueeNotice = null;
    }
@endphp

{{-- ===== TOP BAR ===== --}}
@if($siteSettings->show_topbar ?? true)
<div class="gplc-topbar">

    {{-- DESKTOP topbar row (hidden on mobile and tablet) --}}
    <div class="topbar-desktop container d-none d-xl-flex align-items-center justify-content-between">
        <div class="topbar-info d-flex align-items-center gap-3">
            <div class="nepali-date-widget">
                <i class="fas fa-calendar-day"></i>
                <span>{{ get_today_nepali_date() }}</span>
            </div>
            <span><i class="fas fa-phone-alt"></i> {{ $siteSettings->contact_phone }}</span>
            <span class="d-none d-lg-flex"><i class="fas fa-envelope"></i> {{ $siteSettings->contact_email }}</span>
            <span class="d-none d-xl-flex"><i class="fas fa-map-marker-alt"></i> {{ $siteSettings->contact_address }}</span>
        </div>
        <div class="topbar-actions d-flex align-items-center gap-2">
            <div class="topbar-social d-flex gap-1">
                <a href="{{ $siteSettings->facebook_url ?? '#' }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $siteSettings->youtube_url ?? '#' }}" target="_blank"><i class="fab fa-youtube"></i></a>
                @if(!empty($siteSettings->whatsapp_number))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', (string) $siteSettings->whatsapp_number) }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                @endif
            </div>
            @if(!empty($siteSettings->student_portal_text) && !empty($siteSettings->student_portal_url))
                <a href="{{ $siteSettings->student_portal_url }}" class="portal-btn ms-2" target="_blank">
                    <i class="fas fa-sign-in-alt"></i> {{ $siteSettings->student_portal_text }}
                </a>
            @endif
            @if(!empty($siteSettings->header_button_text))
                @php
                    $isApplyBtn = empty($siteSettings->header_button_url) || $siteSettings->header_button_url === '#' || str_contains($siteSettings->header_button_url, 'apply');
                    $showBtn = !$isApplyBtn || $siteSettings->admissions_open;
                @endphp
                @if($showBtn)
                    <a href="{{ $isApplyBtn ? route('apply') : $siteSettings->header_button_url }}" class="portal-btn portal-btn-secondary" {{ !$isApplyBtn ? 'target="_blank"' : '' }}>
                        <i class="fas fa-arrow-up-right-from-square"></i> {{ $siteSettings->header_button_text }}
                    </a>
                @endif
            @endif
        </div>
    </div>

    {{-- MOBILE & TABLET topbar — two rows —————————————————————————————— --}}
    <div class="topbar-mobile d-xl-none">

        {{-- Row 1: Nepali Date (left) + Marquee Notice (right) --}}
        <div class="mob-topbar-row1">
            <div class="mob-date-pill">
                <i class="fas fa-calendar-day"></i>
                <span>{{ get_today_nepali_date() }}</span>
            </div>
            @if(!empty($headerMarqueeNotice))
            <div class="mob-marquee-wrap">
                <span class="mob-notice-badge"><i class="fas fa-bell"></i></span>
                <marquee behavior="scroll" direction="left" scrollamount="4"
                    onmouseover="this.stop();" onmouseout="this.start();"
                    ontouchstart="this.stop();" ontouchend="this.start();">
                    <a href="{{ url('notice/detail/' . $headerMarqueeNotice->id) }}">
                        {{ $headerMarqueeNotice->title }}
                    </a>
                </marquee>
            </div>
            @endif
        </div>

        {{-- Row 2: Social icons (left) + CTA button (right) --}}
        <div class="mob-topbar-row2">
            <div class="mob-social">
                <a href="{{ $siteSettings->facebook_url ?? '#' }}" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $siteSettings->youtube_url ?? '#' }}" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                @if(!empty($siteSettings->whatsapp_number))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', (string) $siteSettings->whatsapp_number) }}" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                @endif
            </div>
            @if(!empty($siteSettings->header_button_text))
                @php
                    $isApplyBtn = empty($siteSettings->header_button_url) || $siteSettings->header_button_url === '#' || str_contains($siteSettings->header_button_url, 'apply');
                    $showBtn = !$isApplyBtn || $siteSettings->admissions_open;
                @endphp
                @if($showBtn)
                    <a href="{{ $isApplyBtn ? route('apply') : $siteSettings->header_button_url }}" class="mob-cta-btn" {{ !$isApplyBtn ? 'target="_blank"' : '' }}>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                        {{ $siteSettings->header_button_text }}
                    </a>
                @endif
            @elseif(!empty($siteSettings->student_portal_text) && !empty($siteSettings->student_portal_url))
                <a href="{{ $siteSettings->student_portal_url }}" class="mob-cta-btn" target="_blank">
                    <i class="fas fa-sign-in-alt"></i>
                    {{ $siteSettings->student_portal_text }}
                </a>
            @endif
        </div>

    </div>
    {{-- END MOBILE topbar --}}

</div>
@endif

{{-- ===== MAIN HEADER ===== --}}
@php
    $navLayoutClass = 'nav-layout-' . ($siteSettings->navbar_layout ?? 'default');
    $navThemeClass = 'nav-theme-' . ($siteSettings->navbar_theme ?? 'light');
    $isSticky = ($siteSettings->navbar_sticky ?? true) ? 'is-sticky' : '';
@endphp
<header class="gplc-header {{ $navLayoutClass }} {{ $navThemeClass }} {{ $isSticky }}" id="gplcHeader">
    <div class="container">
        <div class="header-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="gplc-logo">
                <img src="{{ $siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png') }}" alt="{{ $siteSettings->site_name }} Logo">
                <div class="gplc-logo-text">
                    <span class="college-name">{{ $siteSettings->site_name }}</span>
                    <span class="affiliation">{{ $siteSettings->site_tagline }}</span>
                </div>
            </a>

            {{-- Navigation --}}
            <nav class="gplc-nav" id="gplcNav">
                <ul>
                    @forelse($navbarMenus as $menu)
                        @if($menu->type == 'course_dropdown')
                            @if($navCourses->count() > 0)
                                <li class="dropdown nav-item">
                                    <a href="#" class="nav-link dropdown-toggle {{ request()->segment(1)=='course' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $menu->name }} <i class="fas fa-chevron-down" style="font-size:9px;margin-left:4px;"></i>
                                    </a>
                                    <ul class="dropdown-menu-gplc dropdown-menu border-0 shadow-sm" style="border-radius: 12px; padding: 10px; min-width: 280px;">
                                        <li>
                                            <a class="dropdown-item fw-bold text-primary py-2" href="{{ url('course') }}" style="border-radius: 8px;">
                                                <i class="bi bi-grid-fill me-2"></i> All Academic Levels (PG to 12)
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider" style="margin: 6px 0; opacity: 0.1;"></li>
                                        @foreach($navCourses as $nc)
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ url('course/' . $nc->slug) }}" style="border-radius: 8px;">
                                                    <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $nc->name }}</div>
                                                    @if($nc->grade_span)
                                                        <small class="text-muted d-block" style="font-size: 11px;"><i class="bi bi-mortarboard me-1"></i>{{ $nc->grade_span }}</small>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a href="{{ url('course') }}" class="{{ request()->segment(1)=='course' ? 'active' : '' }}">
                                        {{ $menu->name }}
                                    </a>
                                </li>
                            @endif
                        @else
                            @php
                                $rawUrl = (string) ($menu->url ?? '');
                                $cleanPath = ltrim($rawUrl, '/');
                                $isActive = ($cleanPath !== '' && (request()->is($cleanPath) || request()->is($cleanPath . '/*')))
                                            || ($rawUrl === '/' && request()->path() === '/');
                                $linkHref = str_starts_with($rawUrl, 'http') ? $rawUrl : url($rawUrl);
                            @endphp
                            <li>
                                <a href="{{ $linkHref }}" class="{{ $isActive ? 'active' : '' }}">
                                    {{ $menu->name }}
                                </a>
                            </li>
                        @endif
                    @empty
                        {{-- Fallback if table is empty or migration not run yet --}}
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        <li><a href="{{ route('about.us') }}" class="{{ request()->routeIs('about.us') ? 'active' : '' }}">About Us</a></li>
                        <li><a href="{{ url('course') }}" class="{{ request()->segment(1)=='course' ? 'active' : '' }}">Academics</a></li>
                        <li><a href="{{ route('member') }}" class="{{ request()->routeIs('member') ? 'active' : '' }}">Faculties</a></li>
                        <li><a href="{{ route('calendar') }}" class="{{ request()->routeIs('calendar') ? 'active' : '' }}">Calendar</a></li>
                        <li><a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a></li>
                        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                    @endforelse
                </ul>
            </nav>

            {{-- Hamburger --}}
            <button class="gplc-hamburger" id="gplcHam" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
