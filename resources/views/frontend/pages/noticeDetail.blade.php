@extends('frontend.layout.master')
@section('frontend-content')

{{-- Hero Section --}}
<div class="notice-hero" style="background-image: url('{{ $notice->image ? asset($notice->image) : asset('frontend/images/default-notice-bg.jpg') }}');">
    <div class="hero-overlay"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="notice-hero-badge mb-3">
                    <i class="fa-solid fa-bell me-2"></i> Official Notice
                </div>
                <h1 class="notice-hero-title">{{ $notice->title }}</h1>
                <div class="d-flex flex-wrap gap-4 mt-4 text-white" style="opacity: 0.9;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-clock fa-lg text-warning"></i>
                        <span class="fs-5">Published: {{ format_system_date($notice->created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-block" style="padding: 80px 0; background: #f9fafb;">
    <div class="container">
        <div class="row g-5">
            {{-- Main Content --}}
            <div class="col-lg-8" data-aos="fade-up">
                <div class="notice-content-card">
                    <div class="notice-body-text">
                        {!! $notice->description !!}
                    </div>

                    <div class="mt-5 pt-4 d-flex gap-3 flex-wrap" style="border-top: 1px solid #e5e7eb;">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Back to Home
                        </a>
                        <a href="{{ route('contact') }}" class="btn-read-more">
                            Have Questions? <i class="fa-solid fa-arrow-right ms-2 transition-icon"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                {{-- Contact card --}}
                <div class="help-widget mb-4">
                    <div class="help-widget-header">
                        <h4>Need Help?</h4>
                    </div>
                    <div class="help-widget-body">
                        <p class="text-white opacity-75 mb-4" style="font-size: 14px; line-height: 1.6;">For any queries regarding this notice, please contact our administration office directly.</p>
                        
                        <a href="tel:{{ $siteSettings->phone ?? '021-546236' }}" class="help-contact-link">
                            <div class="icon-circle"><i class="fa-solid fa-phone"></i></div>
                            <span>{{ $siteSettings->phone ?? '021-546236' }}</span>
                        </a>
                        
                        <a href="mailto:{{ $siteSettings->email ?? 'info@shikshasandesh.edu.np' }}" class="help-contact-link">
                            <div class="icon-circle"><i class="fa-solid fa-envelope"></i></div>
                            <span style="word-break: break-all;">{{ $siteSettings->email ?? 'info@shikshasandesh.edu.np' }}</span>
                        </a>
                    </div>
                </div>

                {{-- Other notices --}}
                @php $otherNotices = App\Models\Notice::where('id','!=',$notice->id)->latest()->take(4)->get(); @endphp
                @if($otherNotices->count() > 0)
                    <div class="other-notices-widget">
                        <div class="other-notices-header">
                            <h4>Other Notices</h4>
                        </div>
                        <div class="other-notices-list">
                            @foreach($otherNotices as $on)
                                <a href="{{ url('notice/detail/' . $on->id) }}" class="other-notice-item">
                                    <div class="notice-icon"><i class="fa-solid fa-thumbtack"></i></div>
                                    <div class="notice-content">
                                        <h6>{{ Str::limit($on->title, 55) }}</h6>
                                        <span>{{ format_system_date($on->created_at) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .notice-hero {
        position: relative;
        padding: 100px 0;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-color: #1f2937;
    }
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to right, rgba(17, 24, 39, 0.9) 0%, rgba(17, 24, 39, 0.6) 100%);
        z-index: 1;
    }
    .notice-hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        color: #fff;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .notice-hero-title {
        color: #fff;
        font-family: var(--font-heading);
        font-weight: 900;
        font-size: 3rem;
        line-height: 1.25;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    @media (max-width: 768px) {
        .notice-hero-title { font-size: 2rem; }
        .notice-hero { padding: 70px 0; }
    }

    .notice-content-card {
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .notice-body-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #374151;
    }
    .notice-body-text img {
        max-width: 100%;
        border-radius: 12px;
        height: auto;
        margin: 20px 0;
    }

    .btn-read-more {
        background: var(--primary);
        border: 2px solid var(--primary);
        color: #ffffff;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-read-more:hover {
        background: var(--dark);
        border-color: var(--dark);
        color: #ffffff;
        transform: translateY(-2px);
    }
    .btn-read-more .transition-icon {
        transition: transform 0.3s ease;
    }
    .btn-read-more:hover .transition-icon {
        transform: translateX(4px);
    }

    /* Help Widget */
    .help-widget {
        background: linear-gradient(135deg, #1f2937, #0d7a3e);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(13, 122, 62, 0.15);
    }
    .help-widget-header {
        padding: 24px 24px 15px;
    }
    .help-widget-header h4 {
        color: #fff;
        margin: 0;
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 1.5rem;
    }
    .help-widget-body {
        padding: 0 24px 24px;
    }
    .help-contact-link {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #fff;
        text-decoration: none;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 12px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s ease;
    }
    .help-contact-link:last-child {
        margin-bottom: 0;
    }
    .help-contact-link:hover {
        background: rgba(255,255,255,0.1);
        transform: translateX(5px);
        color: #fff;
    }
    .help-contact-link .icon-circle {
        width: 35px;
        height: 35px;
        background: #fff;
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .help-contact-link span {
        font-weight: 600;
        font-size: 14px;
    }

    /* Other Notices Widget */
    .other-notices-widget {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .other-notices-header {
        background: rgba(13, 122, 62, 0.05);
        padding: 20px 24px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .other-notices-header h4 {
        margin: 0;
        font-family: var(--font-heading);
        font-weight: 800;
        color: var(--dark);
        font-size: 1.15rem;
    }
    .other-notice-item {
        display: flex;
        gap: 15px;
        padding: 15px 24px;
        text-decoration: none;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    .other-notice-item:last-child {
        border-bottom: none;
    }
    .other-notice-item:hover {
        background: rgba(13, 122, 62, 0.02);
    }
    .notice-icon {
        color: var(--primary);
        font-size: 16px;
        margin-top: 2px;
    }
    .notice-content h6 {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 5px 0;
        line-height: 1.5;
        transition: color 0.3s ease;
    }
    .other-notice-item:hover .notice-content h6 {
        color: var(--primary);
    }
    .notice-content span {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
    }
</style>
@endpush
