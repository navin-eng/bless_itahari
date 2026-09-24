{{-- CLASSIC LAYOUT (Clean, Elegant, Traditional) --}}
<section class="about-hero-classic position-relative" style="padding: 100px 0; background: linear-gradient(rgba(0,30,80,0.85), rgba(0,30,80,0.85)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80') center/cover fixed;">
    <div class="container position-relative z-index-1 text-center text-white">
        <span class="badge bg-primary px-3 py-2 mb-3 rounded-pill text-uppercase tracking-wide">Established {{ $siteSettings->about_established_year ?? '1990' }}</span>
        <h1 class="display-3 fw-bold mb-4">About Our Institution</h1>
        <p class="lead fw-light mx-auto" style="max-width: 700px; font-size: 1.25rem;">
            {{ $siteSettings->about_intro ?? 'We are dedicated to providing excellent education and nurturing the leaders of tomorrow.' }}
        </p>
    </div>
</section>

<section class="about-mission-classic py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="pe-lg-4">
                    <h6 class="text-primary fw-bold text-uppercase tracking-wider mb-2">Our Core Purpose</h6>
                    <h2 class="display-6 fw-bold mb-4">Mission & Vision</h2>
                    
                    <div class="d-flex align-items-start mb-4 bg-white p-4 rounded-4 shadow-sm">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-4 flex-shrink-0">
                            <i class="bi bi-rocket fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-2">Our Mission</h4>
                            <p class="text-muted mb-0 fs-6">{{ $siteSettings->about_mission ?? 'To empower students through quality education.' }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start bg-white p-4 rounded-4 shadow-sm">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-3 me-4 flex-shrink-0">
                            <i class="bi bi-eye fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-2">Our Vision</h4>
                            <p class="text-muted mb-0 fs-6">{{ $siteSettings->about_vision ?? 'To be a globally recognized center of excellence.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=800&q=80" alt="Students" class="img-fluid rounded-4 shadow-lg">
                    <div class="position-absolute bottom-0 start-0 translate-middle-x bg-white p-4 rounded-4 shadow" style="margin-bottom: 20px;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-award text-warning fs-1"></i>
                            <div>
                                <h5 class="fw-bold mb-0">Affiliated With</h5>
                                <p class="text-muted mb-0">{{ $siteSettings->about_affiliation ?? 'Government Board' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-principal-classic py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=600&q=80" alt="Principal" class="img-fluid h-100 w-100 object-fit-cover">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body p-5 h-100 d-flex flex-column justify-content-center bg-white">
                                <i class="bi bi-quote fs-1 text-primary opacity-25 mb-3"></i>
                                <p class="fs-5 text-dark fst-italic mb-4" style="line-height: 1.8;">
                                    "{!! nl2br(e($siteSettings->about_principal_message ?? 'Welcome to our school. We are committed to nurturing the leaders of tomorrow.')) !!}"
                                </p>
                                <div>
                                    <h4 class="fw-bold mb-1">{{ $siteSettings->about_principal_name ?? 'Jane Doe' }}</h4>
                                    <p class="text-primary fw-semibold text-uppercase tracking-wide mb-0">{{ $siteSettings->about_principal_designation ?? 'Principal' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .tracking-wide { letter-spacing: 1px; }
    .tracking-wider { letter-spacing: 2px; }
</style>
