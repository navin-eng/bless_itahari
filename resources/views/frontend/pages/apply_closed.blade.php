@extends('frontend.layout.master')

@section('frontend-content')

<!-- Page Banner Section -->
<section class="page-banner" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('{{ asset('frontend/images/banner2.jpg') }}') center/cover; padding: 100px 0; text-align: center; color: white;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3" data-aos="fade-down">{{ $settings->admission_title ?? 'Admissions are Closed' }}</h1>
    </div>
</section>

<section class="py-5" style="background-color: #f8fafc; min-height: 50vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 text-center">
                <div class="mb-4" data-aos="fade-down">
                    <i class="bi bi-door-closed text-muted" style="font-size: 80px; opacity: 0.5;"></i>
                </div>
                <h2 class="mb-4 fw-bold" style="color: #0f172a;" data-aos="fade-up">
                    {{ $settings->admission_title ?? 'We are not currently accepting new applications.' }}
                </h2>
                <p class="lead text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                    {{ $settings->admission_description ?? 'Please check back later or contact us directly if you have any urgent inquiries.' }}
                </p>
                <div data-aos="zoom-in" data-aos-delay="200">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg px-5 shadow-sm" style="border-radius: 30px;">
                        <i class="bi bi-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
