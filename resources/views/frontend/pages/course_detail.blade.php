@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== COURSE DETAIL HERO ===== --}}
<div class="course-detail-hero" style="position: relative; height: 450px; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff; background: url('{{ asset($course->image) }}') center/cover no-repeat;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(8, 19, 12, 0.9) 0%, rgba(30, 80, 50, 0.7) 100%);"></div>
    <div class="container" style="position: relative; z-index: 2; padding-top: 40px;">
        <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
            <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75">Home</a></li>
                <li class="breadcrumb-item text-white opacity-75">Courses</li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $course->name }}</li>
            </ol>
        </nav>
        <h1 style="font-size: 3rem; font-weight: 800; font-family: var(--font-heading); margin-bottom: 20px;">{{ $course->name }}</h1>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">{{ $course->description }}</p>
    </div>
</div>

{{-- ===== OVERLAPPING META BAR ===== --}}
<div class="container" style="margin-top: -50px; position: relative; z-index: 10;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); background: #fff; padding: 25px 40px;">
                <div class="row text-center g-4">
                    <div class="col-6 col-md-3">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 8px;"><i class="fa-regular fa-clock"></i></div>
                        <h6 style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Duration</h6>
                        <strong style="font-size: 15px; color: #111827;">{{ $course->duration }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 8px;"><i class="fa-solid fa-layer-group"></i></div>
                        <h6 style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Semesters</h6>
                        <strong style="font-size: 15px; color: #111827;">{{ $course->semester }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 8px;"><i class="fa-solid fa-school"></i></div>
                        <h6 style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Requirement</h6>
                        <strong style="font-size: 15px; color: #111827;">{{ $course->requirement }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="color: var(--bs-primary); font-size: 24px; margin-bottom: 8px;"><i class="fa-regular fa-calendar-check"></i></div>
                        <h6 style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Timing</h6>
                        <strong style="font-size: 15px; color: #111827;">{{ $course->starting_time }} - {{ $course->closing_time }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== COURSE CONTENT ===== --}}
<section class="course-content-body" style="padding: 80px 0;">
    <div class="container">
        <div class="row g-5">

            {{-- Main Content --}}
            <div class="col-lg-8" data-aos="fade-up">
                <div class="rich-content" style="font-size: 1.05rem; line-height: 1.8; color: #4b5563;">
                    <h3 style="font-family: var(--font-heading); color: #111827; font-weight: 700; margin-bottom: 24px;">Course Overview</h3>
                    {!! $course->fulldescription !!}
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; position: sticky; top: 110px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    {{-- Sidebar Header --}}
                    <div style="background: rgba(var(--bs-primary-rgb), 0.04); padding: 24px; border-bottom: 1px solid #e5e7eb;">
                        <h5 style="font-family: var(--font-heading); font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Quick Summary</h5>
                    </div>
                    {{-- Sidebar Body --}}
                    <div style="padding: 24px;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #e5e7eb;">
                                <span style="font-size: 14px; color: #6b7280;"><i class="fa-solid fa-book text-primary me-2"></i> Program Name</span>
                                <span style="font-size: 14px; font-weight: 600; color: #111827; text-align: right; max-width: 50%;">{{ $course->name }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #e5e7eb;">
                                <span style="font-size: 14px; color: #6b7280;"><i class="fa-regular fa-clock text-primary me-2"></i> Duration</span>
                                <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $course->duration }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #e5e7eb;">
                                <span style="font-size: 14px; color: #6b7280;"><i class="fa-solid fa-layer-group text-primary me-2"></i> Semesters</span>
                                <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $course->semester }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #e5e7eb;">
                                <span style="font-size: 14px; color: #6b7280;"><i class="fa-solid fa-school text-primary me-2"></i> Requirement</span>
                                <span style="font-size: 14px; font-weight: 600; color: #111827; text-align: right; max-width: 50%;">{{ $course->requirement }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;">
                                <span style="font-size: 14px; color: #6b7280;"><i class="fa-regular fa-calendar-check text-primary me-2"></i> Class Timing</span>
                                <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ $course->starting_time }} &ndash; {{ $course->closing_time }}</span>
                            </li>
                        </ul>
                        <div style="margin-top: 30px;">
                            <a href="{{ route('contact') }}" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" style="border-radius: 8px;">
                                Apply Now <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                        <div class="mt-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center" style="background: #f3f4f6; padding: 8px 16px; border-radius: 50px;">
                                <i class="fa-solid fa-shield-check text-success me-2"></i>
                                <span style="font-size: 12px; font-weight: 600; color: #4b5563;">Affiliated with NEB Nepal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
