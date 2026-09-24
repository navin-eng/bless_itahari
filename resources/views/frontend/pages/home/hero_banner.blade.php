<div class="gplc-hero" style="position:relative;">
    <div class="swiper MainSwiper">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide gplc-hero-slide">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title2 }}">
                    <div class="gplc-hero-overlay"></div>
                    <div class="gplc-hero-content">
                        <span class="tag-line">
                            <i class="fa-solid fa-graduation-cap"></i>
                            {{ $siteSettings->site_tagline ?? 'Excellence in Education Since 1993' }}
                        </span>
                        <h1>{{ $banner->title2 }}</h1>
                        <p>Nurturing Young Minds & Inspiring Tomorrow's Leaders</p>
                        <div class="gplc-hero-actions">
                            <a href="{{ route('about.us') }}" class="btn-gplc">
                                <i class="fa-solid fa-compass"></i> Explore School
                            </a>
                            <a href="{{ route('contact') }}" class="btn-gplc-light">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide gplc-hero-slide" style="background: linear-gradient(135deg, var(--dark) 0%, var(--primary-dark, #0e2d54) 100%);">
                    <div class="gplc-hero-overlay"></div>
                    <div class="gplc-hero-content">
                        <span class="tag-line">
                            <i class="fa-solid fa-graduation-cap"></i>
                            {{ $siteSettings->site_tagline ?? 'Excellence in Education Since 1993' }}
                        </span>
                        <h1>{{ $siteSettings->site_name ?? 'Shiksha Sandesh English School' }}</h1>
                        <p>Nurturing Young Minds & Inspiring Tomorrow's Leaders</p>
                        <div class="gplc-hero-actions">
                            <a href="{{ route('about.us') }}" class="btn-gplc">
                                <i class="fa-solid fa-compass"></i> Explore School
                            </a>
                            <a href="{{ route('contact') }}" class="btn-gplc-light">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="hero-pagination swiper-pagination"></div>
        
        <!-- Animated SVG Wave overlapping the slider -->
        <div class="svg-wave-bottom" style="z-index: 10; pointer-events: none;">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#ffffff"></path>
            </svg>
        </div>
    </div>
</div>
