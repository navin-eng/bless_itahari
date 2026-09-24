{{-- TIMELINE LAYOUT (Story-driven, Interactive) --}}
<section class="about-hero-timeline text-center py-5" style="background: #111827; color: white;">
    <div class="container py-5">
        <h6 class="text-uppercase tracking-wider text-primary mb-3">Our Story</h6>
        <h1 class="display-3 fw-bold mb-4">Journey of Excellence</h1>
        <p class="lead mx-auto text-secondary" style="max-width: 600px;">
            {{ $siteSettings->about_intro ?? 'We are dedicated to providing excellent education and nurturing the leaders of tomorrow.' }}
        </p>
    </div>
</section>

<section class="about-timeline py-5 bg-light position-relative">
    <div class="container py-5">
        <div class="timeline-container position-relative">
            <!-- Center Line -->
            <div class="position-absolute h-100 bg-primary opacity-25" style="width: 4px; left: 50%; transform: translateX(-50%); top: 0;"></div>
            
            <!-- Node 1: Established -->
            <div class="row w-100 mx-0 mb-5 align-items-center">
                <div class="col-6 pe-5 text-end">
                    <h3 class="fw-bold text-dark mb-2">The Beginning</h3>
                    <p class="text-muted mb-0">Our institution was founded with a vision to transform education in the region.</p>
                </div>
                <div class="col-6 ps-5 position-relative">
                    <div class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 50px; height: 50px; left: 0; top: 50%; transform: translate(-50%, -50%); z-index: 2;">
                        <i class="bi bi-flag-fill"></i>
                    </div>
                    <div class="bg-white p-4 rounded-4 shadow-sm border-start border-primary border-4">
                        <h4 class="fw-bold text-primary mb-0">Established {{ $siteSettings->about_established_year ?? '1990' }}</h4>
                    </div>
                </div>
            </div>

            <!-- Node 2: Mission -->
            <div class="row w-100 mx-0 mb-5 align-items-center flex-row-reverse">
                <div class="col-6 ps-5 text-start">
                    <h3 class="fw-bold text-dark mb-2">Our Mission</h3>
                    <p class="text-muted mb-0">{{ $siteSettings->about_mission ?? 'To empower students through quality education.' }}</p>
                </div>
                <div class="col-6 pe-5 position-relative text-end">
                    <div class="position-absolute bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 50px; height: 50px; right: 0; top: 50%; transform: translate(50%, -50%); z-index: 2;">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&q=80" alt="Mission" class="img-fluid rounded-4 shadow-sm">
                </div>
            </div>

            <!-- Node 3: Vision -->
            <div class="row w-100 mx-0 mb-5 align-items-center">
                <div class="col-6 pe-5 text-end">
                    <h3 class="fw-bold text-dark mb-2">Our Vision</h3>
                    <p class="text-muted mb-0">{{ $siteSettings->about_vision ?? 'To be a globally recognized center of excellence.' }}</p>
                </div>
                <div class="col-6 ps-5 position-relative">
                    <div class="position-absolute bg-info text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 50px; height: 50px; left: 0; top: 50%; transform: translate(-50%, -50%); z-index: 2;">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div class="bg-white p-4 rounded-4 shadow-sm border-start border-info border-4">
                        <p class="mb-0 fw-medium">"We look forward to creating global leaders who make a difference."</p>
                    </div>
                </div>
            </div>
            
            <!-- Node 4: Principal -->
            <div class="row w-100 mx-0 align-items-center flex-row-reverse">
                <div class="col-6 ps-5 text-start">
                    <h4 class="fw-bold text-dark mb-1">{{ $siteSettings->about_principal_name ?? 'Jane Doe' }}</h4>
                    <p class="text-primary mb-3">{{ $siteSettings->about_principal_designation ?? 'Principal' }}</p>
                    <p class="text-muted fst-italic">"{!! nl2br(e($siteSettings->about_principal_message ?? 'Welcome to our school.')) !!}"</p>
                </div>
                <div class="col-6 pe-5 position-relative text-end">
                    <div class="position-absolute bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 50px; height: 50px; right: 0; top: 50%; transform: translate(50%, -50%); z-index: 2;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80" alt="Principal" class="rounded-circle shadow-lg" style="width: 200px; height: 200px; object-fit: cover; border: 5px solid white; display: inline-block;">
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .tracking-wider { letter-spacing: 2px; }
    @media (max-width: 768px) {
        .timeline-container > div.position-absolute { left: 20px !important; transform: none !important; }
        .timeline-container .row { flex-direction: column !important; }
        .timeline-container .col-6 { width: 100% !important; padding: 0 0 0 60px !important; text-align: left !important; margin-bottom: 20px; }
        .timeline-container .col-6.position-relative > div.position-absolute { left: 20px !important; right: auto !important; transform: translate(-50%, -50%) !important; }
    }
</style>
