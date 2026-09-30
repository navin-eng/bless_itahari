@extends('frontend.layout.master')

@section('title', $notice->title . ' - Official Notice')
@section('meta_description', Str::limit(strip_tags($notice->description ?? $notice->title), 155))
@if(!empty($notice->image))
@section('og_image', asset($notice->image))
@endif

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ $notice->title }}",
  "datePublished": "{{ $notice->created_at ? $notice->created_at->toIso8601String() : '' }}",
  "dateModified": "{{ $notice->updated_at ? $notice->updated_at->toIso8601String() : '' }}",
  "description": "{{ Str::limit(strip_tags($notice->description ?? ''), 250) }}",
  "image": [
    "{{ !empty($notice->image) ? asset($notice->image) : asset('backend/images/logo.png') }}"
  ],
  "publisher": {
    "@type": "Organization",
    "name": "{{ $siteSettings->site_name ?? 'Bless Itahari' }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset($siteSettings->site_logo ?? 'favicon.png') }}"
    }
  }
}
</script>
@endpush

@section('frontend-content')

{{-- Hero Section --}}
<div class="notice-hero">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="notice-hero-badge mb-3">
                    <i class="fa-solid fa-bell me-2"></i> Official Notice
                </div>
                <h1 class="notice-hero-title">{{ $notice->title }}</h1>
                <div class="d-flex flex-wrap gap-4 mt-4 text-white align-items-center" style="opacity: 0.95;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-clock fa-lg text-warning"></i>
                        <span class="fs-6">Published: {{ format_system_date($notice->created_at) }}</span>
                    </div>
                    @if($notice->expires_at)
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-hourglass-half fa-lg {{ $notice->isExpired() ? 'text-danger' : 'text-info' }}"></i>
                            <span class="fs-6">Expires: {{ format_system_date($notice->expires_at) }}</span>
                        </div>
                    @endif
                    @if($notice->isExpired())
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-7 fw-bold">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Expired
                        </span>
                    @else
                        <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-7 fw-bold">
                            <i class="fa-solid fa-circle-check me-1"></i> Active
                        </span>
                    @endif
                    @if(!empty($notice->file))
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill fs-7 fw-bold">
                            <i class="fa-solid fa-paperclip me-1 text-primary"></i> Attachment Included
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-block" style="padding: 60px 0; background: #f9fafb;">
    <div class="container">
        <div class="row g-5">
            {{-- Main Content --}}
            <div class="col-lg-8" data-aos="fade-up">
                <div class="notice-content-card">
                    @if($notice->isExpired())
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-3 mb-4 rounded-3 p-3" style="background: #fffbeb; border-left: 4px solid #f59e0b !important;">
                            <i class="fa-solid fa-triangle-exclamation fs-4 text-warning mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">This Announcement Has Expired</h6>
                                <p class="small text-muted mb-0">This notice expired on <strong>{{ format_system_date($notice->expires_at) }}</strong>. It is preserved here for historical reference.</p>
                            </div>
                        </div>
                    @endif

                    @if(!empty($notice->image))
                        <div class="notice-featured-image-wrapper mb-4 text-center">
                            <a href="{{ asset($notice->image) }}" target="_blank" title="Click to view full image">
                                <img src="{{ asset($notice->image) }}" alt="{{ $notice->title }}" class="img-fluid rounded-3 shadow-sm notice-featured-img">
                            </a>
                            <div class="text-end mt-2">
                                <a href="{{ asset($notice->image) }}" target="_blank" class="btn btn-sm btn-light border text-secondary" style="font-size: 12px;">
                                    <i class="fa-solid fa-expand me-1"></i> View Full Resolution
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="notice-body-text mb-4">
                        {!! $notice->description !!}
                    </div>

                    {{-- Attached Document / PDF Viewer --}}
                    @if(!empty($notice->file))
                        <div class="notice-attachment-box my-4 p-4 rounded-4 shadow-sm border bg-white">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="file-icon-wrap rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(26, 77, 140, 0.1);">
                                        @if($notice->isPdf())
                                            <i class="fa-solid fa-file-pdf fs-3 text-danger"></i>
                                        @else
                                            <i class="fa-solid fa-file-lines fs-3 text-primary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark">{{ $notice->file_name ?? basename($notice->file) }}</h5>
                                        <div class="text-muted small">
                                            <span>{{ strtoupper($notice->getFileExtension()) }} Document</span>
                                            @if($notice->file_size)
                                                <span> • {{ $notice->file_size }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    @if($notice->isPdf())
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="togglePdfFullscreen()" id="pdfFullscreenBtn">
                                            <i class="fa-solid fa-expand me-1"></i> Fullscreen
                                        </button>
                                    @endif
                                    <a href="{{ asset($notice->file) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Open in New Tab
                                    </a>
                                    <a href="{{ asset($notice->file) }}" download="{{ $notice->file_name ?? basename($notice->file) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fa-solid fa-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>

                            @if($notice->isPdf())
                                {{-- Embedded PDF Viewer --}}
                                <div class="pdf-viewer-container position-relative rounded-3 overflow-hidden border shadow-sm" id="pdfViewerContainer" style="height: 680px; background: #525659;">
                                    <iframe src="{{ asset($notice->file) }}#toolbar=1&navpanes=1" width="100%" height="100%" style="border: none;" title="{{ $notice->title }}">
                                        <p class="p-4 text-white text-center">Your browser does not support embedding PDF files. 
                                            <a href="{{ asset($notice->file) }}" target="_blank" class="text-warning text-decoration-underline">Click here to view or download the PDF</a>.
                                        </p>
                                    </iframe>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 text-muted small d-flex align-items-center justify-content-between">
                                    <span><i class="fa-solid fa-circle-info me-2 text-primary"></i>Document ready for download.</span>
                                    <a href="{{ asset($notice->file) }}" download class="fw-bold text-primary">Download File &rarr;</a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-5 pt-4 d-flex gap-3 flex-wrap" style="border-top: 1px solid #e5e7eb;">
                        <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> All Notices
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-house"></i> Home
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
                        
                        <a href="mailto:{{ $siteSettings->email ?? 'info@blessitahari.edu.np' }}" class="help-contact-link">
                            <div class="icon-circle"><i class="fa-solid fa-envelope"></i></div>
                            <span style="word-break: break-all;">{{ $siteSettings->email ?? 'info@blessitahari.edu.np' }}</span>
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
        padding: 60px 0;
        background: linear-gradient(135deg, var(--primary, #1e293b) 0%, var(--primary-dark, #0f172a) 100%);
        color: #fff;
    }
    .notice-featured-img {
        max-height: 600px;
        width: auto;
        max-width: 100%;
        object-fit: contain;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease;
    }
    .notice-featured-img:hover {
        transform: scale(1.01);
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

@push('scripts')
<script>
    function togglePdfFullscreen() {
        const container = document.getElementById('pdfViewerContainer');
        if (!container) return;
        
        if (!document.fullscreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.msRequestFullscreen) {
                container.msRequestFullscreen();
            }
            const btn = document.getElementById('pdfFullscreenBtn');
            if (btn) btn.innerHTML = '<i class="fa-solid fa-compress me-1"></i> Exit Fullscreen';
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            const btn = document.getElementById('pdfFullscreenBtn');
            if (btn) btn.innerHTML = '<i class="fa-solid fa-expand me-1"></i> Fullscreen';
        }
    }

    document.addEventListener('fullscreenchange', function() {
        const btn = document.getElementById('pdfFullscreenBtn');
        if (!btn) return;
        if (!document.fullscreenElement) {
            btn.innerHTML = '<i class="fa-solid fa-expand me-1"></i> Fullscreen';
        } else {
            btn.innerHTML = '<i class="fa-solid fa-compress me-1"></i> Exit Fullscreen';
        }
    });
</script>
@endpush
