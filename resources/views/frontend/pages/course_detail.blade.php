@extends('frontend.layout.master')
@section('frontend-content')

@php
    $otherCourses = \App\Models\Course::where('status', 1)->where('id', '!=', $course->id)->get();
@endphp

{{-- ===== LEVEL DETAIL HERO ===== --}}
<div class="course-detail-hero" style="position: relative; padding: 100px 0 80px; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff; background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(30, 58, 138, 0.88) 100%);">
    <div class="container" style="position: relative; z-index: 2;">
        <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
            <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('course') }}" class="text-white text-decoration-none opacity-75">Academic Levels</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $course->name }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-center gap-2 mb-3">
            <span class="badge bg-primary px-3 py-2 rounded-pill fw-semibold" style="font-size: 13px;">
                <i class="bi bi-mortarboard-fill me-1"></i> {{ $course->academic_level ?? 'Academic Program' }}
            </span>
            @if($course->grade_span)
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-semibold" style="font-size: 13px;">
                    <i class="bi bi-layers-fill me-1"></i> {{ $course->grade_span }}
                </span>
            @endif
        </div>

        <h1 style="font-size: 2.75rem; font-weight: 800; font-family: var(--font-heading); margin-bottom: 16px;">
            {{ $course->name }}
        </h1>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 750px; margin: 0 auto; line-height: 1.6;">
            {{ $course->description }}
        </p>
    </div>
</div>

{{-- ===== OVERLAPPING META / HIGHLIGHTS BAR ===== --}}
<div class="container" style="margin-top: -40px; position: relative; z-index: 10;">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); background: #fff; padding: 25px 30px;">
                <div class="row text-center g-3">
                    <div class="col-6 col-md-3 border-end">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 6px;"><i class="bi bi-calendar3"></i></div>
                        <h6 style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">Session</h6>
                        <strong style="font-size: 14px; color: #1e293b;">{{ $course->duration }}</strong>
                    </div>
                    <div class="col-6 col-md-3 border-end">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 6px;"><i class="bi bi-patch-check"></i></div>
                        <h6 style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">Evaluation System</h6>
                        <strong style="font-size: 14px; color: #1e293b;" title="{{ $course->evaluation_system }}">
                            {{ Str::limit($course->evaluation_system ?: 'CDC / NEB Standards', 32) }}
                        </strong>
                    </div>
                    <div class="col-6 col-md-3 border-end">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 6px;"><i class="bi bi-clock"></i></div>
                        <h6 style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">School Hours</h6>
                        <strong style="font-size: 14px; color: #1e293b;">
                            {{ $course->starting_time && $course->closing_time ? $course->starting_time . ' – ' . $course->closing_time : 'Standard School Hours' }}
                        </strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 6px;"><i class="bi bi-person-check"></i></div>
                        <h6 style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">Eligibility</h6>
                        <strong style="font-size: 14px; color: #1e293b;" title="{{ $course->requirement }}">
                            {{ Str::limit($course->requirement ?: 'As per School Norms', 30) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== LEVEL CONTENT BODY ===== --}}
<section class="course-content-body" style="padding: 60px 0 90px; background: #f8fafc;">
    <div class="container">
        <div class="row g-5">

            {{-- Main Content --}}
            <div class="col-lg-8" data-aos="fade-up">

                {{-- Interactive Section Navigation Tabs --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white border-bottom p-2">
                        <ul class="nav nav-pills nav-fill gap-2" id="levelDetailsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2.5 px-3 fw-bold" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculumPane" type="button" role="tab" style="border-radius: 10px;">
                                    <i class="bi bi-journal-bookmark-fill me-1"></i> Curriculum & Subjects
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2.5 px-3 fw-bold" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rulesPane" type="button" role="tab" style="border-radius: 10px;">
                                    <i class="bi bi-shield-check me-1"></i> Rules & Regulations
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2.5 px-3 fw-bold" id="admission-tab" data-bs-toggle="tab" data-bs-target="#admissionPane" type="button" role="tab" style="border-radius: 10px;">
                                    <i class="bi bi-person-plus-fill me-1"></i> Admission Procedure
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2.5 px-3 fw-bold" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overviewPane" type="button" role="tab" style="border-radius: 10px;">
                                    <i class="bi bi-info-circle-fill me-1"></i> Level Overview
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Tab Content Panes --}}
                <div class="tab-content" id="levelDetailsTabContent">

                    {{-- 1. Curriculum Pane --}}
                    <div class="tab-pane fade show active" id="curriculumPane" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #fff;">
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                                    <i class="bi bi-book fs-3"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1 text-dark">Curriculum & Subject Framework</h3>
                                    <p class="text-muted small mb-0">Prescribed by Curriculum Development Centre (CDC) & National Examinations Board (NEB), Nepal</p>
                                </div>
                            </div>

                            @if($course->curriculum)
                                <div class="rich-content" style="font-size: 1.02rem; line-height: 1.8; color: #334155;">
                                    {!! $course->curriculum !!}
                                </div>
                            @else
                                <div class="alert alert-info border-0 rounded-3">
                                    <i class="bi bi-info-circle me-2"></i> Curriculum structure for {{ $course->name }} follows CDC Nepal & NEB standards. Please contact the academic administration for syllabus copies.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Rules Pane --}}
                    <div class="tab-pane fade" id="rulesPane" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #fff;">
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                                    <i class="bi bi-shield-check fs-3"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1 text-dark">School Rules & Academic Code of Conduct</h3>
                                    <p class="text-muted small mb-0">Standards of discipline, attendance guidelines, uniform requirements, and examination decorum</p>
                                </div>
                            </div>

                            @if($course->rules)
                                <div class="rich-content" style="font-size: 1.02rem; line-height: 1.8; color: #334155;">
                                    {!! $course->rules !!}
                                </div>
                            @else
                                <div class="alert alert-info border-0 rounded-3">
                                    <i class="bi bi-info-circle me-2"></i> All enrolled students must maintain minimum 75% verified classroom attendance and adhere to the official school uniform and handbook.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3. Admission Pane --}}
                    <div class="tab-pane fade" id="admissionPane" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #fff;">
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                                    <i class="bi bi-person-badge fs-3"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1 text-dark">Admission Procedure & Documentation</h3>
                                    <p class="text-muted small mb-0">Eligibility criteria, admission calendar, required certificates, and registration steps</p>
                                </div>
                            </div>

                            @if($course->admission_procedure)
                                <div class="rich-content" style="font-size: 1.02rem; line-height: 1.8; color: #334155;">
                                    {!! $course->admission_procedure !!}
                                </div>
                            @else
                                <div class="alert alert-info border-0 rounded-3">
                                    <i class="bi bi-info-circle me-2"></i> Admissions for {{ $course->name }} open annually for the new academic session. Required documents include Municipal Birth Certificate and previous grade marksheet.
                                </div>
                            @endif

                            <div class="mt-4 p-4 rounded-3 text-center" style="background: #f1f5f9; border: 1px dashed #cbd5e1;">
                                <h5 class="fw-bold mb-2">Ready to apply for {{ $course->name }}?</h5>
                                <p class="text-muted small mb-3">Submit an online admission inquiry or contact our desk for form verification.</p>
                                <a href="{{ route('apply') }}" class="btn btn-primary px-4 py-2 fw-semibold" style="border-radius: 8px;">
                                    <i class="bi bi-send me-1"></i> Apply Online Now
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Overview Pane --}}
                    <div class="tab-pane fade" id="overviewPane" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #fff;">
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                                    <i class="bi bi-building fs-3"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1 text-dark">Detailed Level Overview & Facilities</h3>
                                    <p class="text-muted small mb-0">Learning philosophy, educational pedagogy, laboratories, and support mechanisms</p>
                                </div>
                            </div>

                            <div class="rich-content" style="font-size: 1.02rem; line-height: 1.8; color: #334155;">
                                {!! $course->fulldescription !!}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div style="position: sticky; top: 100px;">
                    
                    {{-- Quick Summary Card --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden; background: #fff;">
                        <div class="p-4 bg-primary text-white">
                            <h5 class="fw-bold mb-1"><i class="bi bi-info-circle me-2"></i>Quick Summary</h5>
                            <span class="small opacity-75">{{ $course->academic_level ?? 'Academic Tier' }}</span>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0" style="font-size: 13.5px;">
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted"><i class="bi bi-tag text-primary me-2"></i>Program</span>
                                    <span class="fw-semibold text-end text-dark" style="max-width: 55%;">{{ $course->name }}</span>
                                </li>
                                @if($course->grade_span)
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted"><i class="bi bi-layers text-primary me-2"></i>Classes</span>
                                    <span class="fw-semibold text-dark">{{ $course->grade_span }}</span>
                                </li>
                                @endif
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted"><i class="bi bi-calendar3 text-primary me-2"></i>Session</span>
                                    <span class="fw-semibold text-dark">{{ $course->duration }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted"><i class="bi bi-patch-check text-primary me-2"></i>Affiliation</span>
                                    <span class="fw-semibold text-dark">NEB / CDC Nepal</span>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted"><i class="bi bi-clock text-primary me-2"></i>School Hours</span>
                                    <span class="fw-semibold text-dark">{{ $course->starting_time && $course->closing_time ? $course->starting_time . ' - ' . $course->closing_time : 'Standard' }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2">
                                    <span class="text-muted"><i class="bi bi-card-text text-primary me-2"></i>Requirement</span>
                                    <span class="fw-semibold text-dark text-end" style="max-width: 50%;">{{ $course->requirement ?: 'General' }}</span>
                                </li>
                            </ul>

                            <div class="mt-4">
                                <a href="{{ route('apply') }}" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" style="border-radius: 10px;">
                                    <i class="bi bi-send me-1"></i> Apply for Admission
                                </a>
                                <a href="{{ route('contact') }}" class="btn btn-outline-secondary w-100 py-2 mt-2 fw-semibold" style="border-radius: 10px;">
                                    <i class="bi bi-telephone me-1"></i> Contact Desk
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Other Academic Levels --}}
                    @if($otherCourses->count() > 0)
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; background: #fff;">
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-grid-fill text-primary me-2"></i>Other Academic Levels</h6>
                            </div>
                            <div class="p-3">
                                <ul class="list-unstyled mb-0">
                                    @foreach($otherCourses as $oc)
                                        <li class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <a href="{{ url('course/' . $oc->slug) }}" class="text-decoration-none text-dark d-flex align-items-center justify-content-between hover-primary py-1">
                                                <div>
                                                    <div class="fw-semibold" style="font-size: 13.5px;">{{ $oc->name }}</div>
                                                    @if($oc->grade_span)
                                                        <small class="text-muted" style="font-size: 11px;">{{ $oc->grade_span }}</small>
                                                    @endif
                                                </div>
                                                <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Support hash navigation e.g. #curriculum, #rules, #admission, #overview
        const hash = window.location.hash;
        if (hash) {
            if (hash === '#curriculum') {
                const triggerEl = document.querySelector('#curriculum-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            } else if (hash === '#rules') {
                const triggerEl = document.querySelector('#rules-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            } else if (hash === '#admission') {
                const triggerEl = document.querySelector('#admission-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            } else if (hash === '#overview') {
                const triggerEl = document.querySelector('#overview-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }
    });
</script>
@endpush

@endsection
