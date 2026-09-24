@extends('frontend.layout.master')
@section('frontend-content')

{{-- Hero Section --}}
<div class="event-hero" style="background-image: url('{{ asset($event->image) }}');">
    <div class="hero-overlay"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="event-hero-badge mb-3">
                    {{ $event->event_type_label ?? 'Campus Event' }}
                </div>
                <h1 class="event-hero-title">{{ $event->name }}</h1>
                <div class="d-flex flex-wrap gap-4 mt-4 text-white" style="opacity: 0.9;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-calendar fa-lg text-warning"></i>
                        <span class="fs-5">{{ format_system_date($event->visit_date) }}</span>
                    </div>
                    @if($event->venue)
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-location-dot fa-lg text-warning"></i>
                        <span class="fs-5">{{ $event->venue }}</span>
                    </div>
                    @endif
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
                <div class="event-content-card">
                    <h3 class="mb-4" style="font-family: var(--font-heading); font-weight: 800; color: var(--dark);">About This Event</h3>
                    
                    <div class="event-body-text">
                        {!! $event->description !!}
                    </div>

                    @if(!empty($event->gallery))
                        <div class="mt-5 pt-4" style="border-top: 1px solid #e5e7eb;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                                <h4 style="font-family: var(--font-heading); font-weight: 800; color: var(--dark); margin: 0;">Event Gallery</h4>
                                <span style="font-size: 14px; color: #6b7280; font-weight: 600;">Moments captured</span>
                            </div>
                            <div id="eventGalleryRow" class="event-gallery-grid">
                                @foreach(($event->gallery ?? []) as $img)
                                    <a href="{{ asset($img) }}" data-lg-size="1400-900" class="gallery-item">
                                        <div class="gallery-img-wrapper">
                                            <img src="{{ $img ? asset($img) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $event->name }}">
                                            <div class="gallery-overlay"><i class="fa-solid fa-expand fa-2x text-white"></i></div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="event-sidebar-widget">
                    <div class="widget-header">
                        <h4>Event Summary</h4>
                    </div>
                    <div class="widget-body">
                        <ul class="event-meta-list">
                            <li>
                                <div class="icon-box"><i class="fa-regular fa-calendar-check"></i></div>
                                <div class="meta-content">
                                    <span>Date</span>
                                    <strong>{{ format_system_date($event->visit_date) }}</strong>
                                </div>
                            </li>
                            <li>
                                <div class="icon-box"><i class="fa-solid fa-map-location-dot"></i></div>
                                <div class="meta-content">
                                    <span>Venue</span>
                                    <strong>{{ $event->venue ?: ($siteSettings->site_short_name ?? 'School') . ' Campus' }}</strong>
                                </div>
                            </li>
                            <li>
                                <div class="icon-box"><i class="fa-solid fa-tag"></i></div>
                                <div class="meta-content">
                                    <span>Category</span>
                                    <strong>{{ $event->event_type_label ?? 'Campus Event' }}</strong>
                                </div>
                            </li>
                        </ul>

                        @if($event->result_link)
                            <a href="{{ $event->result_link }}" target="_blank" class="btn btn-outline-primary w-100 mt-4 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-file-pdf"></i> View Result / File
                            </a>
                        @endif
                        
                        <a href="{{ route('contact') }}" class="btn-gplc w-100 mt-3 d-flex align-items-center justify-content-center gap-2" style="border-radius: 50px;">
                            <i class="fa-solid fa-paper-plane"></i> Have Questions?
                        </a>
                    </div>
                </div>

                @php $otherEvents = App\Models\Event::where('status',1)->where('id','!=',$event->id)->latest()->take(3)->get(); @endphp
                @if($otherEvents->count() > 0)
                    <div class="mt-4">
                        <h4 class="mb-3" style="font-family: var(--font-heading); font-weight: 800; color: var(--dark); font-size: 1.25rem;">More Events</h4>
                        <div class="d-flex flex-column gap-3">
                            @foreach($otherEvents as $oe)
                                <a href="{{ url('event/' . $oe->slug) }}" class="other-event-card">
                                    <img src="{{ $oe->image ? asset($oe->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $oe->name }}">
                                    <div class="other-event-content">
                                        <h5>{{ Str::limit($oe->name, 45) }}</h5>
                                        <span><i class="fa-regular fa-calendar me-1"></i> {{ format_system_date($oe->visit_date) }}</span>
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
    .event-hero {
        position: relative;
        padding: 120px 0;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-color: var(--dark);
    }
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 100%);
        z-index: 1;
    }
    .event-hero-badge {
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
    .event-hero-title {
        color: #fff;
        font-family: var(--font-heading);
        font-weight: 900;
        font-size: 3.5rem;
        line-height: 1.2;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    @media (max-width: 768px) {
        .event-hero-title { font-size: 2.2rem; }
        .event-hero { padding: 80px 0; }
    }

    .event-content-card {
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .event-body-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #4b5563;
    }
    .event-body-text img {
        max-width: 100%;
        border-radius: 12px;
        height: auto;
        margin: 20px 0;
    }

    /* Sidebar Widget */
    .event-sidebar-widget {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .widget-header {
        background: linear-gradient(135deg, var(--dark), var(--primary));
        padding: 24px;
        color: #fff;
    }
    .widget-header h4 {
        margin: 0;
        font-family: var(--font-heading);
        font-weight: 800;
    }
    .widget-body {
        padding: 24px;
    }
    .event-meta-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .event-meta-list li {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .event-meta-list li:last-child {
        border-bottom: none;
    }
    .icon-box {
        width: 45px;
        height: 45px;
        background: rgba(13, 122, 62, 0.1);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .meta-content span {
        display: block;
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .meta-content strong {
        display: block;
        color: var(--dark);
        font-size: 15px;
        font-weight: 700;
    }

    /* Other Events */
    .other-event-card {
        display: flex;
        gap: 15px;
        background: #fff;
        padding: 12px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .other-event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: rgba(13, 122, 62, 0.1);
    }
    .other-event-card img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .other-event-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .other-event-content h5 {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 5px 0;
        line-height: 1.4;
        transition: color 0.3s ease;
    }
    .other-event-card:hover .other-event-content h5 {
        color: var(--primary);
    }
    .other-event-content span {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
    }

    /* Gallery */
    .event-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
    .gallery-item {
        display: block;
        border-radius: 12px;
        overflow: hidden;
    }
    .gallery-img-wrapper {
        position: relative;
        padding-top: 75%;
    }
    .gallery-img-wrapper img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(13, 122, 62, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const eventGallery = document.getElementById('eventGalleryRow');
        if (eventGallery && typeof lightGallery !== 'undefined') {
            lightGallery(eventGallery, {
                speed: 500,
                plugins: [lgZoom],
                download: false
            });
        }
    });
</script>
@endpush
