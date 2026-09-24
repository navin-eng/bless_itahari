{{-- MODERN SPLIT LAYOUT (Dynamic, Vibrant, Glassmorphism) --}}
<section class="about-hero-modern py-5" style="background: #f8fafc;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="pe-lg-4 position-relative z-index-1">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill text-uppercase tracking-wide">
                        <i class="bi bi-star-fill text-warning me-1"></i> Since {{ $siteSettings->about_established_year ?? '1990' }}
                    </span>
                    <h1 class="display-4 fw-bold mb-4" style="color: #1e293b;">
                        Empowering Minds, <span class="text-primary position-relative">Shaping Futures
                            <svg class="position-absolute bottom-0 start-0 w-100" style="height: 12px; z-index: -1;" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0,10 Q50,20 100,10 L100,20 L0,20 Z" fill="rgba(var(--bs-primary-rgb), 0.2)"/></svg>
                        </span>
                    </h1>
                    <p class="lead text-secondary mb-5" style="font-size: 1.25rem;">
                        {{ $siteSettings->about_intro ?? 'We are dedicated to providing excellent education and nurturing the leaders of tomorrow.' }}
                    </p>
                    
                    <div class="d-flex gap-4">
                        <div class="card border-0 shadow-sm rounded-4 flex-fill modern-hover-card">
                            <div class="card-body p-4 text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                                    <i class="bi bi-rocket-takeoff fs-3"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Mission</h5>
                                <p class="text-muted small mb-0">{{ Str::limit($siteSettings->about_mission ?? 'To empower students.', 60) }}</p>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-4 flex-fill modern-hover-card">
                            <div class="card-body p-4 text-center">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                                    <i class="bi bi-eye fs-3"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Vision</h5>
                                <p class="text-muted small mb-0">{{ Str::limit($siteSettings->about_vision ?? 'To be a global center of excellence.', 60) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="position-relative">
                    <!-- Blob shape behind image -->
                    <div class="position-absolute top-50 start-50 translate-middle" style="width: 120%; height: 120%; background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1), rgba(var(--bs-success-rgb), 0.1)); border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; z-index: 0; filter: blur(40px);"></div>
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80" alt="Campus" class="img-fluid rounded-5 shadow-lg position-relative z-index-1" style="border: 8px solid white;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-principal-modern py-5" style="background: white;">
    <div class="container py-5">
        <div class="card border-0 rounded-5 overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
            <div class="row g-0 align-items-center">
                <div class="col-lg-8 p-5 p-md-5">
                    <h3 class="fw-bold mb-4 text-dark">A Message from the Principal</h3>
                    <p class="fs-5 text-secondary fst-italic mb-4" style="line-height: 1.8;">
                        "{!! nl2br(e($siteSettings->about_principal_message ?? 'Welcome to our school. We are committed to nurturing the leaders of tomorrow.')) !!}"
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary rounded-circle" style="width: 50px; height: 3px;"></div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $siteSettings->about_principal_name ?? 'Jane Doe' }}</h5>
                            <p class="text-muted mb-0">{{ $siteSettings->about_principal_designation ?? 'Principal' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center text-lg-end p-5">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80" alt="Principal" class="rounded-circle shadow-lg" style="width: 250px; height: 250px; object-fit: cover; border: 8px solid white;">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .modern-hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .modern-hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .tracking-wide { letter-spacing: 1px; }
</style>
