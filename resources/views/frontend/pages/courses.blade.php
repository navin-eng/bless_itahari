@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Academic Programs</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Academics
            </nav>
        </div>
    </div>
</div>

{{-- ===== COURSES LIST ===== --}}
<section class="section-block" style="padding: 80px 0;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">What We Offer</span>
            <h2 class="section-title mt-2">Explore Our Programs</h2>
            <div class="section-divider center"></div>
        </div>
        <div class="row g-4">
            @forelse($courses as $course)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="course-card h-100">
                        <div class="card-img">
                            <img src="{{ $course->image ? asset($course->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $course->name }}">
                            <span class="card-badge">Program</span>
                        </div>
                        <div class="card-body">
                            <h5>{{ $course->name }}</h5>
                            <p class="text-muted">{{ Str::limit(strip_tags($course->description), 100) }}</p>
                            <a href="{{ url('course/' . $course->slug) }}" class="btn-gplc mt-2">
                                <i class="fa-solid fa-arrow-right"></i> Learn More
                            </a>
                        </div>
                        <div class="card-footer-gplc">
                            <span class="duration">
                                <i class="fa-regular fa-clock"></i>
                                {{ $course->duration }} Years
                            </span>
                            <a href="{{ url('course/' . $course->slug) }}">View Details &rarr;</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa-solid fa-book-open fa-3x mb-3 text-muted"></i>
                    <p class="text-muted fs-5">No programs available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
