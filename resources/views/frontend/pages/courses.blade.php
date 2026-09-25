@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Academic Programs & Levels</h1>
            <p class="mt-2 text-white-50" style="max-width: 650px; font-size: 1.05rem;">
                Comprehensive school education from Playgroup (PG) through Grade 12 (+2 Science & Management), strictly aligned with NEB & CDC Nepal guidelines.
            </p>
            <nav class="breadcrumb-nav mt-3">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Academics
            </nav>
        </div>
    </div>
</div>

{{-- ===== ACADEMIC TIERS & PROGRAMS LIST ===== --}}
<section class="section-block" style="padding: 70px 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Educational Structure</span>
            <h2 class="section-title mt-2">School Levels: PG to Grade 12</h2>
            <div class="section-divider center"></div>
            <p class="text-muted mt-3 mx-auto" style="max-width: 700px;">
                Explore curriculum frameworks, school code of conduct, and admission guidelines across all academic tiers at Bless Itahari.
            </p>

            {{-- Level Filters --}}
            @php
                $levelsList = $courses->pluck('academic_level')->filter()->unique()->values();
            @endphp
            @if($levelsList->count() > 1)
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4" id="academicLevelFilterNav">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-semibold active level-filter-btn" data-filter="all">
                        All Levels (PG - 12)
                    </button>
                    @foreach($levelsList as $lvl)
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-semibold level-filter-btn" data-filter="{{ Str::slug($lvl) }}">
                            {{ $lvl }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="row g-4" id="coursesGridContainer">
            @forelse($courses as $course)
                <div class="col-lg-4 col-md-6 course-item-col" data-level="{{ Str::slug($course->academic_level ?? 'general') }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; background: #fff;">
                        
                        {{-- Top Header / Image Banner --}}
                        <div style="position: relative; height: 180px; overflow: hidden; background: linear-gradient(135deg, var(--bs-primary, #0d6efd) 0%, #1e3a8a 100%);">
                            @if($course->image && file_exists(public_path($course->image)) && !str_contains($course->image, 'default.jpg'))
                                <img src="{{ asset($course->image) }}" alt="{{ $course->name }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.85;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-white opacity-25">
                                    <i class="bi bi-mortarboard-fill" style="font-size: 5rem;"></i>
                                </div>
                            @endif
                            <div style="position: absolute; top: 14px; left: 14px; right: 14px; display: flex; justify-content: space-between; align-items: center;">
                                <span class="badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">
                                    <i class="bi bi-bookmark-check-fill me-1"></i> {{ $course->academic_level ?? 'Academic Tier' }}
                                </span>
                                @if($course->grade_span)
                                    <span class="badge bg-dark bg-opacity-75 text-white px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">
                                        {{ $course->grade_span }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title fw-bold text-dark mb-2" style="font-size: 1.25rem;">
                                <a href="{{ url('course/' . $course->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $course->name }}
                                </a>
                            </h4>
                            <p class="text-muted small mb-3" style="line-height: 1.6; min-height: 48px;">
                                {{ Str::limit(strip_tags($course->description), 115) }}
                            </p>

                            {{-- Highlight Pills --}}
                            <div class="p-3 rounded-3 mb-3" style="background: #f1f5f9; font-size: 12.5px;">
                                <div class="d-flex align-items-center mb-1 text-secondary">
                                    <i class="bi bi-award-fill text-primary me-2"></i>
                                    <span class="fw-semibold text-dark me-1">Evaluation:</span> 
                                    <span class="text-truncate">{{ Str::limit($course->evaluation_system ?: 'Annual Evaluation (CDC/NEB)', 30) }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 text-secondary">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    <span class="fw-semibold text-dark me-1">Session:</span> {{ $course->duration }}
                                </div>
                                @if($course->starting_time || $course->closing_time)
                                    <div class="d-flex align-items-center text-secondary">
                                        <i class="bi bi-clock-fill text-primary me-2"></i>
                                        <span class="fw-semibold text-dark me-1">Hours:</span> {{ $course->starting_time }} – {{ $course->closing_time }}
                                    </div>
                                @endif
                            </div>

                            {{-- Direct Topic Shortcuts --}}
                            <div class="d-flex gap-2 mb-4" style="font-size: 12px;">
                                <a href="{{ url('course/' . $course->slug) }}#curriculum" class="btn btn-sm btn-light border flex-grow-1 text-muted" title="View Curriculum">
                                    <i class="bi bi-journal-text text-primary me-1"></i> Curriculum
                                </a>
                                <a href="{{ url('course/' . $course->slug) }}#rules" class="btn btn-sm btn-light border flex-grow-1 text-muted" title="View Rules & Conduct">
                                    <i class="bi bi-shield-check text-success me-1"></i> Rules
                                </a>
                                <a href="{{ url('course/' . $course->slug) }}#admission" class="btn btn-sm btn-light border flex-grow-1 text-muted" title="View Admission Criteria">
                                    <i class="bi bi-pencil-square text-info me-1"></i> Admission
                                </a>
                            </div>

                            {{-- Primary Action Button --}}
                            <div class="mt-auto">
                                <a href="{{ url('course/' . $course->slug) }}" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
                                    <span>Explore Level Guidelines</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-mortarboard fa-3x mb-3 text-muted"></i>
                    <p class="text-muted fs-5">No academic levels configured at the moment.</p>
                </div>
            @endforelse
        </div>

        {{-- School Accreditation Banner --}}
        <div class="mt-5 p-4 rounded-4 shadow-sm" style="background: #fff; border: 1px solid #e2e8f0;" data-aos="fade-up">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-patch-check-fill text-primary me-2"></i>Accreditation & Government Affiliation</h5>
                    <p class="text-muted small mb-0">
                        Bless Itahari operates under the strict regulatory standards of the Curriculum Development Centre (CDC), Government of Nepal Ministry of Education, Science and Technology, and the National Examinations Board (NEB).
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('apply') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-send me-1"></i> Online Admission Inquiry
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.level-filter-btn');
        const courseCols = document.querySelectorAll('.course-item-col');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                courseCols.forEach(col => {
                    if (filter === 'all' || col.getAttribute('data-level') === filter) {
                        col.style.display = 'block';
                    } else {
                        col.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection
