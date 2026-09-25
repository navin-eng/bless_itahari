<section class="section-block bg-light" style="padding: 70px 0;">
    <div class="container content-relative">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Academic Excellence</span>
            <h2 class="section-title mt-2">Our School Levels & Programs</h2>
            <div class="section-divider center"></div>
            <p class="text-muted mt-3 mx-auto" style="max-width: 650px;">
                Comprehensive learning journeys from Playgroup (PG) through Grade 12 (+2 Science & Management) affiliated with NEB and CDC Nepal.
            </p>
        </div>
        <div class="row g-4">
            @forelse($courses as $course)
                <div class="col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="course-card h-100 d-flex flex-column shadow-sm border-0" style="border-radius: 16px; overflow: hidden; background: #fff;">
                        <div class="card-img" style="position: relative; height: 170px; background: linear-gradient(135deg, var(--bs-primary, #0d6efd) 0%, #1e3a8a 100%);">
                            @if($course->image && file_exists(public_path($course->image)) && !str_contains($course->image, 'default.jpg'))
                                <img src="{{ asset($course->image) }}" alt="{{ $course->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-white opacity-25">
                                    <i class="bi bi-mortarboard-fill" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            <span class="card-badge bg-white text-primary fw-bold shadow-sm" style="position: absolute; top: 12px; left: 12px; font-size: 11px; padding: 4px 12px; border-radius: 20px;">
                                {{ $course->academic_level ?? 'Academic Tier' }}
                            </span>
                            @if($course->grade_span)
                                <span class="badge bg-dark bg-opacity-75 text-white" style="position: absolute; bottom: 12px; right: 12px; font-size: 10.5px; border-radius: 20px; padding: 4px 10px;">
                                    {{ $course->grade_span }}
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold mb-2">
                                <a href="{{ url('course/' . $course->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $course->name }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3" style="min-height: 44px; line-height: 1.6;">
                                {{ Str::limit(strip_tags($course->description), 105) }}
                            </p>
                            
                            {{-- Topic Quick links --}}
                            <div class="d-flex gap-1 mb-3 pt-2 border-top" style="font-size: 11px;">
                                <a href="{{ url('course/' . $course->slug) }}#curriculum" class="btn btn-sm btn-light border flex-grow-1 text-muted py-1 px-1 text-center" title="Curriculum">
                                    <i class="bi bi-journal-text text-primary"></i> Curriculum
                                </a>
                                <a href="{{ url('course/' . $course->slug) }}#rules" class="btn btn-sm btn-light border flex-grow-1 text-muted py-1 px-1 text-center" title="Rules">
                                    <i class="bi bi-shield-check text-success"></i> Rules
                                </a>
                                <a href="{{ url('course/' . $course->slug) }}#admission" class="btn btn-sm btn-light border flex-grow-1 text-muted py-1 px-1 text-center" title="Admission">
                                    <i class="bi bi-person-check text-info"></i> Admission
                                </a>
                            </div>

                            <a href="{{ url('course/' . $course->slug) }}" class="btn btn-outline-primary w-100 mt-auto py-2 fw-semibold" style="border-radius: 8px; font-size: 13.5px;">
                                View Level Details <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-footer-gplc px-4 py-2.5 bg-light border-top d-flex justify-content-between align-items-center" style="font-size: 12px;">
                            <span class="text-muted">
                                <i class="fa-regular fa-calendar-check text-primary me-1"></i>
                                {{ $course->duration }}
                            </span>
                            <span class="text-primary fw-semibold">
                                NEB / CDC Nepal
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No academic programs available at the moment.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ url('course') }}" class="btn btn-primary px-4 py-2.5 fw-semibold shadow-sm" style="border-radius: 25px;">
                <i class="bi bi-grid-fill me-1"></i> View All School Levels & Curriculum
            </a>
        </div>
    </div>
</section>
