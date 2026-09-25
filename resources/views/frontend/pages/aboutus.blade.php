@extends('frontend.layout.master')

@php
    $siteSettings = $siteSettings ?? \App\Models\SiteSetting::current();
    $messages = $messages ?? collect();
    $faqs = $faqs ?? collect();
    $aboutData = $aboutData ?? null;
    $counter = $counter ?? null;
@endphp

@push('styles')
<style>
    /* ─── ABOUT REDESIGN STYLES ─────────────────────────────────────────── */
    .about-page-wrap {
        color: #1e293b;
        font-family: var(--font-sans, system-ui, -apple-system, sans-serif);
        overflow-x: hidden;
    }

    /* Hero Section */
    .about-hero-cinematic {
        position: relative;
        background: radial-gradient(circle at 80% 20%, rgba(30, 58, 138, 0.4) 0%, transparent 60%),
                    linear-gradient(135deg, #091a32 0%, #0f2747 60%, #081426 100%);
        color: #ffffff;
        padding: 95px 0 80px;
        overflow: hidden;
    }
    .about-hero-cinematic::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 28px 28px;
        opacity: 0.6;
        pointer-events: none;
    }
    .about-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #f8fafc;
        margin-bottom: 20px;
    }
    .about-hero-title {
        font-size: clamp(2.3rem, 4.8vw, 3.6rem);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 18px;
        letter-spacing: -0.5px;
        color: #ffffff;
    }
    .about-hero-title span {
        background: linear-gradient(120deg, #60a5fa 0%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .about-hero-sub {
        font-size: 1.15rem;
        line-height: 1.7;
        color: #cbd5e1;
        max-width: 720px;
        margin: 0 auto 30px;
    }

    /* Sub-nav quick jump */
    .about-quick-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
    }
    .about-quick-nav a {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        color: #e2e8f0;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .about-quick-nav a:hover {
        background: #ffffff;
        color: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Section Styles */
    .about-section {
        padding: 85px 0;
        position: relative;
    }
    .about-section-tag {
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--bs-primary, #0d6efd);
        background: rgba(13, 110, 253, 0.08);
        padding: 6px 14px;
        border-radius: 50px;
        margin-bottom: 12px;
    }
    .about-section-title {
        font-size: clamp(1.8rem, 3.2vw, 2.6rem);
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 16px;
    }
    .about-lead-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #475569;
    }

    /* Glass and Elevated Cards */
    .feature-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 32px 28px;
        height: 100%;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .feature-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--bs-primary, #0d6efd), #3b82f6);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .feature-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }
    .feature-card-modern:hover::before {
        opacity: 1;
    }

    .feature-icon-bubble {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 22px;
        transition: transform 0.3s ease;
    }
    .feature-card-modern:hover .feature-icon-bubble {
        transform: scale(1.1) rotate(4deg);
    }

    /* Pillars List */
    .pillar-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 20px;
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.25s ease;
        margin-bottom: 12px;
    }
    .pillar-item:hover {
        transform: translateX(6px);
        border-color: var(--bs-primary, #0d6efd);
        box-shadow: 0 10px 25px -10px rgba(13, 110, 253, 0.15);
    }
    .pillar-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.1);
        color: var(--bs-primary, #0d6efd);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Image Collage */
    .about-image-collage {
        position: relative;
        padding: 20px;
    }
    .about-main-img-box {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.2);
        position: relative;
    }
    .about-main-img-box img {
        width: 100%;
        height: 480px;
        object-fit: cover;
        display: block;
        transition: transform 0.6s ease;
    }
    .about-main-img-box:hover img {
        transform: scale(1.03);
    }

    .about-float-badge {
        position: absolute;
        bottom: 0px;
        right: 0px;
        background: #ffffff;
        border-radius: 18px;
        padding: 20px 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid #e2e8f0;
        max-width: 250px;
        z-index: 2;
        animation: floatUpDown 4s ease-in-out infinite;
    }
    @keyframes floatUpDown {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Stat Counters */
    .stat-counter-box {
        text-align: center;
        padding: 36px 20px;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.05);
    }
    .stat-counter-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px -10px rgba(13, 110, 253, 0.12);
        border-color: #bfdbfe;
    }
    .stat-number {
        font-size: clamp(2.2rem, 4vw, 3.2rem);
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
        background: linear-gradient(135deg, #0d6efd 0%, #1e40af 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .stat-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #64748b;
        margin: 0;
    }

    /* Leadership Message Cards */
    .leader-card-modern {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 15px 35px -10px rgba(15, 23, 42, 0.08);
        transition: all 0.35s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .leader-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 50px -15px rgba(15, 23, 42, 0.15);
    }
    .leader-header-bar {
        padding: 24px 28px 16px;
        display: flex;
        align-items: center;
        gap: 18px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafafa;
    }
    .leader-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        flex-shrink: 0;
    }
    .leader-quote-body {
        padding: 28px;
        position: relative;
        flex-grow: 1;
        font-size: 1rem;
        line-height: 1.8;
        color: #334155;
        font-style: italic;
    }
    .leader-quote-body i.quote-bg {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 4rem;
        color: rgba(13, 110, 253, 0.05);
        pointer-events: none;
    }

    /* School Level Road Map */
    .tier-badge-pill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Facility Cards */
    .facility-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        height: 100%;
    }
    .facility-card:hover {
        transform: translateY(-5px);
        border-color: #3b82f6;
        box-shadow: 0 16px 32px -10px rgba(15, 23, 42, 0.1);
    }
    .facility-ico-wrap {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #eff6ff;
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* FAQ Custom Styling */
    .about-faq-card .accordion-button {
        padding: 18px 24px;
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        background: #ffffff;
        border-radius: 14px !important;
        box-shadow: none;
    }
    .about-faq-card .accordion-button:not(.collapsed) {
        background: #f0f7ff;
        color: var(--bs-primary, #0d6efd);
    }
    .about-faq-card .accordion-body {
        padding: 20px 24px 24px;
        color: #475569;
        font-size: 0.98rem;
        line-height: 1.8;
    }

    /* CTA Section */
    .about-cta-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        border-radius: 28px;
        padding: 60px 45px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.3);
    }
    .about-cta-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, transparent 70%);
        pointer-events: none;
    }
</style>
@endpush

@section('frontend-content')
<div class="about-page-wrap">

    {{-- ===== 1. CINEMATIC HERO SECTION ===== --}}
    <section class="about-hero-cinematic text-center">
        <div class="container position-relative z-1" data-aos="fade-up">
            <div class="about-hero-badge">
                <i class="bi bi-patch-check-fill text-warning"></i>
                <span>Est. {{ $siteSettings->about_established_year ?? '1993' }} • Quality Education You Can Trust</span>
            </div>
            
            <h1 class="about-hero-title">
                Fostering Curiosity, Character & <br class="d-none d-md-block">
                <span>Academic Excellence</span>
            </h1>

            <p class="about-hero-sub">
                At {{ $siteSettings->site_name ?? 'Blooming Lotus Secondary English School' }}, we empower students from Playgroup to Grade 12 with Nepal CDC & NEB curriculum rigor, moral discipline, and 21st-century technological literacy.
            </p>

            {{-- Quick Jump Navigation --}}
            <div class="about-quick-nav">
                <a href="#who-we-are"><i class="bi bi-buildings me-1"></i> Who We Are</a>
                <a href="#mission-vision"><i class="bi bi-compass me-1"></i> Mission & Vision</a>
                <a href="#leadership"><i class="bi bi-person-badge me-1"></i> Leadership</a>
                <a href="#academic-continuum"><i class="bi bi-mortarboard me-1"></i> School Levels</a>
                <a href="#campus-amenities"><i class="bi bi-shield-check me-1"></i> Campus Life</a>
                <a href="#about-faqs"><i class="bi bi-question-circle me-1"></i> FAQs</a>
            </div>
        </div>
    </section>

    {{-- ===== 2. WHO WE ARE & HERITAGE ===== --}}
    <section class="about-section bg-white" id="who-we-are">
        <div class="container">
            <div class="row g-5 align-items-center">
                
                {{-- Left Image Collage --}}
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-image-collage">
                        <div class="about-main-img-box">
                            @php
                                $aboutImg = null;
                                if (!empty($siteSettings->about_school_image) && file_exists(public_path($siteSettings->about_school_image))) {
                                    $aboutImg = asset($siteSettings->about_school_image);
                                } elseif (!empty($siteSettings->about_hero_image) && file_exists(public_path($siteSettings->about_hero_image))) {
                                    $aboutImg = asset($siteSettings->about_hero_image);
                                } elseif (file_exists(public_path('frontend/images/about_campus.jpg'))) {
                                    $aboutImg = asset('frontend/images/about_campus.jpg');
                                } elseif (file_exists(public_path('frontend/image/swiper1.jpg'))) {
                                    $aboutImg = asset('frontend/image/swiper1.jpg');
                                } else {
                                    $aboutImg = asset('backend/images/logo.png');
                                }
                            @endphp
                            <img src="{{ $aboutImg }}" alt="{{ $siteSettings->site_name ?? 'School Campus' }}">
                        </div>

                        {{-- Floating Highlight Badge --}}
                        <div class="about-float-badge">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.4rem;">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-5 mb-0">30+ Years</div>
                                    <small class="text-muted fw-semibold">Academic Legacy</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Narrative & Key Pillars --}}
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="about-section-tag">About Our School</span>
                    <h2 class="about-section-title">
                        A Premier Educational Sanctuary in {{ $siteSettings->contact_address ?? 'Itahari, Koshi Province' }}
                    </h2>
                    
                    <p class="about-lead-text mb-4">
                        {{ $siteSettings->about_intro ?: 'Founded with a profound commitment to academic distinction, moral development, and holistic student care, our institution provides a progressive learning ecosystem tailored to the developmental needs of every learner.' }}
                    </p>

                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Bless Itahari combines experiential learning pedagogy, dedicated teacher mentorship, and technology-empowered classrooms to foster intellectual agility, ethical conscience, and creative confidence. From our nurturing Montessori pre-primary wing to rigorous SEE and NEB +2 academic programs, we cultivate students who excel locally and globally.
                    </p>

                    {{-- 4 Core Pillars --}}
                    <div class="row g-3 pt-2">
                        <div class="col-sm-6">
                            <div class="pillar-item">
                                <div class="pillar-icon"><i class="bi bi-person-hearts"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Child-Centered Care</h6>
                                    <small class="text-muted">Personalized pacing & emotional safety</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="pillar-item">
                                <div class="pillar-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="bi bi-cpu-fill"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">STEM & Practical Labs</h6>
                                    <small class="text-muted">Digital literacy & hands-on science</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="pillar-item">
                                <div class="pillar-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="bi bi-trophy-fill"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Holistic Co-Curriculars</h6>
                                    <small class="text-muted">Sports, public speaking & creative arts</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="pillar-item">
                                <div class="pillar-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;"><i class="bi bi-shield-check"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Safe & Caring Campus</h6>
                                    <small class="text-muted">CCTV secured & caring pastoral guidance</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- ===== 3. MISSION, VISION & CORE VALUES ===== --}}
    <section class="about-section" style="background: #f8fafc;" id="mission-vision">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="about-section-tag">Institutional Guiding Lights</span>
                <h2 class="about-section-title">Our Vision, Mission & Values</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mx-auto mt-2" style="max-width: 650px;">
                    Anchored by the educational directives of the Ministry of Education & NEB Nepal, our principles define every classroom engagement.
                </p>
            </div>

            <div class="row g-4">
                {{-- Vision --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                    <div class="feature-card-modern">
                        <div class="feature-icon-bubble" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">Our Vision</h4>
                        <p class="text-muted mb-0" style="line-height: 1.8;">
                            {{ $siteSettings->about_vision ?: 'To stand as an exemplary center of academic and ethical excellence in Eastern Nepal, inspiring young scholars to lead purposeful lives marked by intellectual curiosity, social empathy, and visionary global citizenship.' }}
                        </p>
                    </div>
                </div>

                {{-- Mission --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card-modern">
                        <div class="feature-icon-bubble" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">Our Mission</h4>
                        <p class="text-muted mb-0" style="line-height: 1.8;">
                            {{ $siteSettings->about_mission ?: 'To impart comprehensive, inclusive, and learner-centered education that fuses Nepal National Curriculum standards with digital fluency, scientific inquiry, moral fortitude, and robust athletic development.' }}
                        </p>
                    </div>
                </div>

                {{-- Core Values --}}
                <div class="col-lg-4 col-md-12" data-aos="fade-up" data-aos-delay="150">
                    <div class="feature-card-modern">
                        <div class="feature-icon-bubble" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <i class="bi bi-gem"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">Core Values (संस्कार र मर्यादा)</h4>
                        <ul class="list-unstyled mb-0" style="line-height: 2;">
                            <li><i class="bi bi-check-circle-fill text-primary me-2"></i><strong>Integrity (सत्यता):</strong> Honesty in thought, speech, and academic pursuit.</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Discipline (अनुशासन):</strong> Punctuality, self-regulation, and respect.</li>
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Innovation (सिर्जनशीलता):</strong> Creative questioning and problem-solving.</li>
                            <li><i class="bi bi-check-circle-fill text-info me-2"></i><strong>Empathy (सद्भाव):</strong> Mutual respect and community service.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 4. STATISTICAL MILESTONES ===== --}}
    <section class="about-section py-5" style="background: linear-gradient(135deg, #091a32 0%, #173256 100%); color: #fff;">
        <div class="container py-3">
            <div class="row g-4">
                <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="50">
                    <div class="stat-counter-box bg-white">
                        <div class="stat-number">30+</div>
                        <p class="stat-label">Years of Heritage</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-counter-box bg-white">
                        <div class="stat-number">1,200+</div>
                        <p class="stat-label">Enrolled Students</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="150">
                    <div class="stat-counter-box bg-white">
                        <div class="stat-number">100%</div>
                        <p class="stat-label">SEE & NEB Success</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-counter-box bg-white">
                        <div class="stat-number">45+</div>
                        <p class="stat-label">Qualified Teachers</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 5. LEADERSHIP MESSAGES ===== --}}
    <section class="about-section bg-white" id="leadership">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="about-section-tag">Institutional Voices</span>
                <h2 class="about-section-title">Words from Our Leadership</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mx-auto mt-2" style="max-width: 650px;">
                    Guided by experienced academic leaders committed to cultivating a safe, inspiring, and achievement-oriented school community.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse($messages as $msg)
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="leader-card-modern">
                            <div class="leader-header-bar">
                                @php
                                    $msgImg = (!empty($msg->image) && file_exists(public_path('backend/images/messages/' . $msg->image)))
                                        ? asset('backend/images/messages/' . $msg->image)
                                        : asset('frontend/images/about_principal.jpg');
                                @endphp
                                <img src="{{ $msgImg }}" alt="{{ $msg->name }}" class="leader-avatar">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $msg->name }}</h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill" style="font-size: 11.5px;">
                                        {{ $msg->designation }}
                                    </span>
                                </div>
                            </div>
                            <div class="leader-quote-body">
                                <i class="bi bi-quote quote-bg"></i>
                                {!! nl2br(strip_tags($msg->message)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Default Leadership Fallback --}}
                    <div class="col-lg-6" data-aos="fade-up">
                        <div class="leader-card-modern">
                            <div class="leader-header-bar">
                                <img src="{{ asset('frontend/images/about_principal.jpg') }}" alt="Principal" class="leader-avatar">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $siteSettings->about_principal_name ?? 'Mr. Ramesh Koirala' }}</h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill" style="font-size: 11.5px;">
                                        {{ $siteSettings->about_principal_designation ?? 'Principal' }}
                                    </span>
                                </div>
                            </div>
                            <div class="leader-quote-body">
                                <i class="bi bi-quote quote-bg"></i>
                                {!! nl2br(e($siteSettings->about_principal_message ?: 'Welcome to Bless Itahari. Every learner carries immense potential waiting to be ignited. Our teachers nurture that spark with passion, personal care, and high academic standards.')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="leader-card-modern">
                            <div class="leader-header-bar">
                                <img src="{{ asset('frontend/images/about_hero.jpg') }}" alt="Chairperson" class="leader-avatar">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Mrs. Anju Thapa</h5>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill" style="font-size: 11.5px;">
                                        Chairperson
                                    </span>
                                </div>
                            </div>
                            <div class="leader-quote-body">
                                <i class="bi bi-quote quote-bg"></i>
                                Education is the single most transformative investment in our youth. At Bless Itahari, we continuously modernize our facilities, empower our faculty, and uphold the values of honesty, hard work, and civic responsibility.
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ===== 6. ACADEMIC CONTINUUM (PG TO GRADE 12) ===== --}}
    <section class="about-section" style="background: #f8fafc;" id="academic-continuum">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="about-section-tag">Education Roadmap</span>
                <h2 class="about-section-title">Comprehensive School Levels: PG to Grade 12</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mx-auto mt-2" style="max-width: 650px;">
                    Carefully scaffolded developmental pathways regulated by the Curriculum Development Centre (CDC) and National Examinations Board (NEB).
                </p>
            </div>

            <div class="row g-4">
                {{-- 1. Pre-Primary --}}
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="50">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="tier-badge-pill bg-warning bg-opacity-15 text-warning border border-warning border-opacity-25">Tier 1</span>
                            <small class="text-muted fw-semibold">Ages 2.5 - 5</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Pre-Primary Wing</h5>
                        <p class="text-muted small mb-3 flex-grow-1">Montessori-inspired playgroup, Nursery, LKG & UKG. Fosters phonics, motor agility, rhythm, and joyful social adaptation.</p>
                        <a href="{{ url('course') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto fw-semibold">Explore Wing &rarr;</a>
                    </div>
                </div>

                {{-- 2. Basic Level --}}
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="tier-badge-pill bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25">Tier 2</span>
                            <small class="text-muted fw-semibold">Grades 1 – 8</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Basic Level Education</h5>
                        <p class="text-muted small mb-3 flex-grow-1">Strong bilingual foundations in English & Nepali, core mathematics, environmental science, arts, and continuous internal assessment.</p>
                        <a href="{{ url('course') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto fw-semibold">Explore Wing &rarr;</a>
                    </div>
                </div>

                {{-- 3. Secondary Level (SEE) --}}
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="150">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="tier-badge-pill bg-success bg-opacity-15 text-success border border-success border-opacity-25">Tier 3</span>
                            <small class="text-muted fw-semibold">Grades 9 & 10 (SEE)</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Secondary Level (SEE)</h5>
                        <p class="text-muted small mb-3 flex-grow-1">Rigorous CDC Nepal curriculum, dedicated laboratory experiments, comprehensive mock exams, and focused SEE board preparation.</p>
                        <a href="{{ url('course') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto fw-semibold">Explore Wing &rarr;</a>
                    </div>
                </div>

                {{-- 4. Higher Secondary (+2) --}}
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="tier-badge-pill bg-danger bg-opacity-15 text-danger border border-danger border-opacity-25">Tier 4</span>
                            <small class="text-muted fw-semibold">+2 Science / Mgmt</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Higher Secondary (+2)</h5>
                        <p class="text-muted small mb-3 flex-grow-1">NEB affiliated science and management faculties with specialized faculty, entrance preparation guidance, and career seminars.</p>
                        <a href="{{ url('course') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto fw-semibold">Explore Wing &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 7. CAMPUS AMENITIES & INFRASTRUCTURE ===== --}}
    <section class="about-section bg-white" id="campus-amenities">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="about-section-tag">Campus Facilities</span>
                <h2 class="about-section-title">Designed for Flourishing Minds</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mx-auto mt-2" style="max-width: 650px;">
                    Modern infrastructure, safety protocols, and stimulating spaces that turn learning into a vibrant daily discovery.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="facility-card">
                        <div class="facility-ico-wrap"><i class="bi bi-laptop"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Computer & Coding Labs</h6>
                            <p class="text-muted small mb-0">High-speed internet workstations, coding curricula, and digital projection systems.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="facility-card">
                        <div class="facility-ico-wrap" style="background: #ecfdf5; color: #059669;"><i class="bi bi-flask"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Science Laboratories</h6>
                            <p class="text-muted small mb-0">Well-ventilated, safely equipped Physics, Chemistry, and Biology practical stations.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="facility-card">
                        <div class="facility-ico-wrap" style="background: #fef3c7; color: #d97706;"><i class="bi bi-book-half"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Resource-Rich Library</h6>
                            <p class="text-muted small mb-0">Curated collection of academic books, periodicals, encyclopedias, and quiet reading nooks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="facility-card">
                        <div class="facility-ico-wrap" style="background: #fee2e2; color: #dc2626;"><i class="bi bi-dribbble"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Sports & Play Arena</h6>
                            <p class="text-muted small mb-0">Spacious grounds for football, basketball, cricket, badminton, and early-childhood play.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                    <div class="facility-card">
                        <div class="facility-ico-wrap" style="background: #f3e8ff; color: #7c3aed;"><i class="bi bi-bus-front"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Safe School Transport</h6>
                            <p class="text-muted small mb-0">Punctual, attendant-monitored bus routes serving Itahari, Belbari, and adjoining areas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="facility-card">
                        <div class="facility-ico-wrap" style="background: #e0f2fe; color: #0284c7;"><i class="bi bi-cup-hot"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Hygienic Canteen & Pure Water</h6>
                            <p class="text-muted small mb-0">Freshly prepared nutritious meals and multi-stage RO purified drinking water facilities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 8. CUSTOM SUMMERNOTE RICH CONTENT (FROM ADMIN) ===== --}}
    @if(!empty($aboutData) && trim(strip_tags($aboutData->desc ?? '')) !== '')
        <section class="about-section" style="background: #f8fafc;">
            <div class="container">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <span class="about-section-tag">Institutional Profile</span>
                        <h3 class="fw-bold text-dark">Additional Institutional Background</h3>
                    </div>
                    <div class="about-rich-article" style="line-height: 1.8; color: #475569;">
                        {!! $aboutData->desc !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== 9. FREQUENTLY ASKED QUESTIONS ===== --}}
    <section class="about-section bg-white" id="about-faqs">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="about-section-tag">Help & Answers</span>
                <h2 class="about-section-title">Frequently Asked Questions</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mx-auto mt-2" style="max-width: 650px;">
                    Common questions from parents and students regarding our campus, curriculum, and admissions.
                </p>
            </div>

            <div class="accordion about-faq-card mx-auto" id="aboutFaqAccordion" style="max-width: 820px;" data-aos="fade-up">
                @if($faqs->count() > 0)
                    @foreach($faqs as $faq)
                        <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header" id="faq-heading-{{ $faq->id }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="bi bi-question-circle-fill text-primary me-2.5"></i> {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq-collapse-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#aboutFaqAccordion">
                                <div class="accordion-body">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Default Standard FAQs for School --}}
                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                        <h2 class="accordion-header" id="faq-h1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c1" aria-expanded="true">
                                <i class="bi bi-question-circle-fill text-primary me-2.5"></i> What are the school operating hours and academic session?
                            </button>
                        </h2>
                        <div id="faq-c1" class="accordion-collapse collapse show" data-bs-parent="#aboutFaqAccordion">
                            <div class="accordion-body">
                                Pre-Primary classes (PG to UKG) operate from <strong>09:30 AM to 02:30 PM</strong>. Basic and Secondary levels (Grade 1 to 10) operate from <strong>09:45 AM to 04:00 PM</strong>, Sunday through Friday. The academic calendar commences every year in mid-April (Baisakh) following Government of Nepal education timelines.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                        <h2 class="accordion-header" id="faq-h2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c2">
                                <i class="bi bi-question-circle-fill text-primary me-2.5"></i> Which curriculum board is the school affiliated with?
                            </button>
                        </h2>
                        <div id="faq-c2" class="accordion-collapse collapse" data-bs-parent="#aboutFaqAccordion">
                            <div class="accordion-body">
                                Bless Itahari is fully recognized and affiliated with the <strong>Curriculum Development Centre (CDC), Ministry of Education, Science & Technology, Government of Nepal</strong> for Grade PG through 10 (SEE), and with the <strong>National Examinations Board (NEB)</strong> for Higher Secondary (+2 Science and Management).
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                        <h2 class="accordion-header" id="faq-h3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c3">
                                <i class="bi bi-question-circle-fill text-primary me-2.5"></i> Does the school offer school bus transportation?
                            </button>
                        </h2>
                        <div id="faq-c3" class="accordion-collapse collapse" data-bs-parent="#aboutFaqAccordion">
                            <div class="accordion-body">
                                Yes. The school runs a dedicated fleet of supervised buses covering diverse routes across Itahari, Belbari, and adjoining neighborhood areas with safety attendants on board every bus.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                        <h2 class="accordion-header" id="faq-h4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c4">
                                <i class="bi bi-question-circle-fill text-primary me-2.5"></i> How does the school evaluate and report student performance?
                            </button>
                        </h2>
                        <div id="faq-c4" class="accordion-collapse collapse" data-bs-parent="#aboutFaqAccordion">
                            <div class="accordion-body">
                                For Pre-Primary, evaluation is 100% continuous milestone observation without test anxiety. For Grades 1 through 10, we combine continuous formative assessment (homework, class projects, participation, lab work) with term examinations. Periodic Parent-Teacher Meetings (PTMs) are held to discuss comprehensive progress.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== 10. CALL TO ACTION BANNER ===== --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="about-cta-banner text-center" data-aos="zoom-in">
                <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">
                    <i class="bi bi-sparkles text-primary me-1"></i> Start Your Journey
                </span>
                <h2 class="display-6 fw-bold mb-3 text-white">Join Our Academic Family Today</h2>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 620px; font-size: 1.1rem; line-height: 1.7;">
                    Give your child the foundation of academic brilliance, moral integrity, and modern capabilities. Admissions are currently welcoming inquiries across all levels.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('apply') }}" class="btn btn-primary btn-lg px-4 py-2.5 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i> Apply for Admission
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4 py-2.5 rounded-pill fw-bold">
                        <i class="bi bi-geo-alt me-2"></i> Visit Our Campus
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection