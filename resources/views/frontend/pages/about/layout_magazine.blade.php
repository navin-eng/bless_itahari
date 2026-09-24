{{-- MAGAZINE LAYOUT (Editorial, Bold, Typography-focused) --}}
<section class="about-hero-magazine pt-5" style="background: white;">
    <div class="container pt-5 pb-4 border-bottom border-dark border-3">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <span class="text-uppercase tracking-wider fw-bold text-muted small d-block mb-3">Est. {{ $siteSettings->about_established_year ?? '1990' }} — {{ $siteSettings->about_affiliation ?? 'Government Board' }}</span>
                <h1 class="display-2 fw-bolder mb-0" style="letter-spacing: -2px; color: #111;">ABOUT US.</h1>
            </div>
            <div class="col-lg-4 text-lg-end pb-3">
                <p class="text-muted fst-italic mb-0" style="font-family: serif; font-size: 1.1rem;">"Dedicated to excellence in education."</p>
            </div>
        </div>
    </div>
</section>

<section class="about-content-magazine py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Left Column: Intro & Image -->
            <div class="col-lg-8">
                <p class="lead fw-medium mb-5" style="font-size: 1.5rem; line-height: 1.6; color: #333;">
                    <span class="display-4 fw-bold float-start me-3 lh-1" style="color: var(--bs-primary); margin-top: -5px;">{{ substr($siteSettings->about_intro ?? 'We', 0, 1) }}</span>
                    {{ substr($siteSettings->about_intro ?? 'We are dedicated to providing excellent education.', 1) }}
                </p>
                
                <figure class="figure w-100 mb-5">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&q=80" class="figure-img img-fluid w-100" alt="Campus Life" style="filter: grayscale(20%);">
                    <figcaption class="figure-caption text-end fst-italic mt-2">Students at our campus facility.</figcaption>
                </figure>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-6 border-end border-2">
                        <h3 class="fw-bold mb-3 text-uppercase tracking-wide fs-5">Our Mission</h3>
                        <p class="text-muted" style="font-family: serif; font-size: 1.1rem;">{{ $siteSettings->about_mission ?? 'To empower students through quality education.' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-3 text-uppercase tracking-wide fs-5">Our Vision</h3>
                        <p class="text-muted" style="font-family: serif; font-size: 1.1rem;">{{ $siteSettings->about_vision ?? 'To be a globally recognized center of excellence.' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Sidebar (Principal) -->
            <div class="col-lg-4">
                <div class="p-4 bg-light border-top border-dark border-4">
                    <h5 class="fw-bold text-uppercase tracking-wider mb-4 fs-6">Leadership</h5>
                    
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80" alt="Principal" class="img-fluid w-100 mb-4" style="filter: contrast(110%) sepia(10%);">
                    
                    <h4 class="fw-bolder mb-1" style="color: #111;">{{ $siteSettings->about_principal_name ?? 'Jane Doe' }}</h4>
                    <p class="text-uppercase text-muted small fw-bold tracking-wide mb-4">{{ $siteSettings->about_principal_designation ?? 'Principal' }}</p>
                    
                    <div class="position-relative">
                        <i class="bi bi-quote position-absolute top-0 start-0 text-muted opacity-25" style="font-size: 3rem; transform: translate(-10px, -15px);"></i>
                        <p class="fst-italic text-dark position-relative z-index-1" style="font-family: serif; font-size: 1.1rem; line-height: 1.7;">
                            "{!! nl2br(e($siteSettings->about_principal_message ?? 'Welcome to our school. We are committed to nurturing the leaders of tomorrow.')) !!}"
                        </p>
                    </div>
                </div>
                
                <!-- Extra Magazine Ad / Callout block -->
                <div class="mt-4 p-4 text-center bg-dark text-white">
                    <h5 class="text-uppercase tracking-wider fw-bold text-primary mb-2">Join Us</h5>
                    <p class="small text-muted mb-0">Become a part of our legacy. Admissions are now open for the upcoming session.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .tracking-wide { letter-spacing: 1px; }
    .tracking-wider { letter-spacing: 2px; }
</style>
