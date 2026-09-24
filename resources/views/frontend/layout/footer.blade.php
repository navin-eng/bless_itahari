@php
    $siteSettings = \App\Models\SiteSetting::current();
    try {
        $footerCourses = \App\Models\Course::where('status', 1)->get();
    } catch (\Throwable $e) {
        $footerCourses = collect();
    }
@endphp

<footer class="gplc-footer">
    <div class="container">
        <div class="row g-5">

            {{-- Brand --}}
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <img src="{{ $siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png') }}" width="110" alt="{{ $siteSettings->site_name }} Logo" style="margin-bottom: 20px;">
                    <p style="font-size: 0.95rem; line-height: 1.8; color: rgba(255,255,255,0.7);">{{ $siteSettings->site_name }}, providing quality education and shaping future leaders in {{ $siteSettings->contact_address }}.</p>
                    <div class="gplc-footer-social" style="display: flex; gap: 12px; margin-top: 24px;">
                        <a href="{{ $siteSettings->facebook_url }}" target="_blank" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $siteSettings->youtube_url }}" target="_blank" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none;"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $siteSettings->instagram_url }}" target="_blank" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/{{ $siteSettings->whatsapp_number }}" target="_blank" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none;"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <div class="footer-widget">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about.us') }}">About Us</a></li>
                        <li><a href="{{ route('calendar') }}">Calendar</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('member') }}">Faculties</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>

            {{-- Courses --}}
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h4>Our Courses</h4>
                    <ul class="footer-links">
                        @foreach($footerCourses as $fc)
                            <li><a href="{{ url('course/' . $fc->slug) }}">{{ $fc->name }}</a></li>
                        @endforeach
                        @if($footerCourses->isEmpty())
                            <li><a href="{{ route('home') }}">No courses yet</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Contact --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-widget">
                    <h4>Contact Us</h4>
                    <ul class="footer-contact list-unstyled">
                        <li><i class="fa-solid fa-location-dot"></i> <span>{{ $siteSettings->contact_address }}</span></li>
                        <li><i class="fa-solid fa-phone"></i> <a href="tel:{{ $siteSettings->contact_phone }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ $siteSettings->contact_phone }}</a></li>
                        <li><i class="fa-solid fa-envelope"></i> <a href="mailto:{{ $siteSettings->contact_email }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ $siteSettings->contact_email }}</a></li>
                        <li><i class="fa-brands fa-whatsapp"></i> <a href="https://wa.me/{{ $siteSettings->whatsapp_number }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">+977 {{ $siteSettings->whatsapp_number }}</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0">&copy; {{ now()->year }} {{ $siteSettings->site_name }}. All Rights Reserved.</p>
            <p class="mb-0">
                <a href="{{ route('privacy.policy') }}" style="color: var(--accent); text-decoration: none;">Privacy Policy</a> &nbsp;|&nbsp;
                Designed by <a href="https://nstudios1.blogspot.com/" target="_blank" style="color: var(--accent); text-decoration: none;">nstudios</a>
            </p>
        </div>
    </div>
</footer>
