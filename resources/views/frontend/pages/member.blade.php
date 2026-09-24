@extends('frontend.layout.master')
@section('frontend-content')

<style>
    /* Premium Member Page Styles */
    .team-page-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding-bottom: 80px;
    }

    .team-hero {
        position: relative;
        text-align: center;
        padding: 80px 20px 60px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        overflow: hidden;
    }

    .team-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 60%);
        animation: rotateBg 30s linear infinite;
        z-index: 1;
    }

    @keyframes rotateBg {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .team-hero-content {
        position: relative;
        z-index: 2;
    }

    .team-hero-content h1 {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 15px;
        letter-spacing: -1px;
    }

    .team-hero-content p {
        font-size: 18px;
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto;
    }

    .team-section-title {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 40px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .team-section-title i {
        color: var(--brand-color, #3b82f6);
        background: rgba(59, 130, 246, 0.1);
        padding: 12px;
        border-radius: 12px;
    }

    /* Premium Faculty Card */
    .premium-faculty-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #f1f5f9;
        height: 100%;
        position: relative;
    }

    .premium-faculty-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }

    .pfc-img-wrapper {
        position: relative;
        width: 100%;
        height: 280px;
        overflow: hidden;
        background: #e2e8f0;
    }

    .pfc-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        transition: transform 0.6s ease;
    }

    .premium-faculty-card:hover .pfc-img-wrapper img {
        transform: scale(1.08);
    }

    .pfc-social-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.8) 0%, transparent 60%);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 20px;
        opacity: 0;
        transition: 0.3s ease;
    }

    .premium-faculty-card:hover .pfc-social-overlay {
        opacity: 1;
    }

    .social-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .premium-faculty-card:hover .social-btn {
        transform: translateY(0);
    }

    .social-btn:hover {
        background: #3b82f6;
        color: white;
    }

    .pfc-body {
        padding: 25px 20px;
        text-align: center;
        position: relative;
    }

    .pfc-body h4 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .pfc-role {
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        padding: 4px 12px;
        border-radius: 50px;
        margin-bottom: 10px;
    }

</style>

@php
    $administrativeStaff = App\Models\Teacher::where('staff_type', 'administrative')->orderBy('sort_order', 'asc')->get();
    $teachingStaff       = App\Models\Teacher::where('staff_type', 'teaching')->orderBy('sort_order', 'asc')->get();
    $nonTeachingStaff    = App\Models\Teacher::where('staff_type', 'non_teaching')->orderBy('sort_order', 'asc')->get();
    $hasAny = $administrativeStaff->count() || $teachingStaff->count() || $nonTeachingStaff->count();
@endphp

<div class="team-page-wrapper">
    
    <div class="team-hero">
        <div class="container team-hero-content" data-aos="zoom-out">
            <h1>Meet Our Exceptional Team</h1>
            <p>The dedicated professionals, educators, and leaders who make {{ $siteSettings->site_short_name ?? 'Shiksha Sandesh' }} extraordinary.</p>
        </div>
    </div>

    <div class="container mt-5">
        @if($hasAny)
            
            {{-- Administrative Staff --}}
            @if($administrativeStaff->count() > 0)
            <div class="mb-5 pb-3" data-aos="fade-up">
                <h3 class="team-section-title justify-content-center">
                    <i class="bi bi-building"></i> Administrative Leaders
                </h3>
                <div class="row g-4 justify-content-center">
                    @foreach($administrativeStaff as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="premium-faculty-card">
                            <div class="pfc-img-wrapper">
                                <img src="{{ $data->image ? asset($data->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $data->name }}">
                                <div class="pfc-social-overlay">
                                    @if($data->facebook_link)
                                        <a href="{{ $data->facebook_link }}" target="_blank" class="social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="pfc-body">
                                <h4>{{ $data->name }}</h4>
                                <div class="pfc-role">{{ $data->role }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Teaching Staff --}}
            @if($teachingStaff->count() > 0)
            <div class="mb-5 pb-3" data-aos="fade-up">
                <h3 class="team-section-title justify-content-center">
                    <i class="bi bi-book-half"></i> Teaching Faculty
                </h3>
                <div class="row g-4 justify-content-center">
                    @foreach($teachingStaff as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="premium-faculty-card">
                            <div class="pfc-img-wrapper">
                                <img src="{{ $data->image ? asset($data->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $data->name }}">
                                <div class="pfc-social-overlay">
                                    @if($data->facebook_link)
                                        <a href="{{ $data->facebook_link }}" target="_blank" class="social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="pfc-body">
                                <h4>{{ $data->name }}</h4>
                                <div class="pfc-role">{{ $data->role }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Non-Teaching Staff --}}
            @if($nonTeachingStaff->count() > 0)
            <div class="mb-5 pb-3" data-aos="fade-up">
                <h3 class="team-section-title justify-content-center">
                    <i class="bi bi-people-fill"></i> Support Staff
                </h3>
                <div class="row g-4 justify-content-center">
                    @foreach($nonTeachingStaff as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="premium-faculty-card">
                            <div class="pfc-img-wrapper">
                                <img src="{{ $data->image ? asset($data->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $data->name }}">
                                <div class="pfc-social-overlay">
                                    @if($data->facebook_link)
                                        <a href="{{ $data->facebook_link }}" target="_blank" class="social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="pfc-body">
                                <h4>{{ $data->name }}</h4>
                                <div class="pfc-role">{{ $data->role }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-people" style="font-size: 80px; color: #cbd5e1; margin-bottom: 20px; display: inline-block;"></i>
                <h2 style="color: #0f172a; font-weight: 800;">Team information coming soon.</h2>
                <p style="color: #64748b; font-size: 18px;">We are currently updating our faculty and staff profiles. Please check back later.</p>
            </div>
        @endif
    </div>
</div>

@include('frontend.layout.sections', ['page' => 'member'])

@endsection
