@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Contact Us</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Contact Us
            </nav>
        </div>
    </div>
</div>

{{-- ===== CONTACT MAIN SECTION ===== --}}
<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Get In Touch</span>
            <h2 class="section-title mt-2">We'd Love to Hear From You</h2>
            <div class="section-divider center"></div>
        </div>

        <div class="row g-4">
            {{-- Contact Info Card --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="contact-info-card">
                    <h4>Contact Information</h4>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-text">
                            <div class="label">Address</div>
                            {{ $siteSettings->contact_address ?? 'Belbari-2, Lalbatti, Morang, Nepal' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-phone-alt"></i></div>
                        <div class="info-text">
                            <div class="label">Phone</div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->contact_phone ?? '021546236') }}">{{ $siteSettings->contact_phone ?? '021-546236' }}</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-envelope"></i></div>
                        <div class="info-text">
                            <div class="label">Email</div>
                            <a href="mailto:{{ $siteSettings->contact_email ?? 'info@shikshasandesh.edu.np' }}">{{ $siteSettings->contact_email ?? 'info@shikshasandesh.edu.np' }}</a>
                        </div>
                    </div>
                    @if($siteSettings->whatsapp_number)
                    <div class="info-item">
                        <div class="ico"><i class="fab fa-whatsapp"></i></div>
                        <div class="info-text">
                            <div class="label">WhatsApp</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->whatsapp_number) }}" target="_blank">{{ $siteSettings->whatsapp_number }}</a>
                        </div>
                    </div>
                    @endif
                    <div class="social-row">
                        @if($siteSettings->facebook_url)<a href="{{ $siteSettings->facebook_url }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
                        @if($siteSettings->instagram_url)<a href="{{ $siteSettings->instagram_url }}" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>@endif
                        @if($siteSettings->youtube_url)<a href="{{ $siteSettings->youtube_url }}" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>@endif
                        @if($siteSettings->whatsapp_number)<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->whatsapp_number) }}" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>@endif
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="gplc-map">
                    <iframe
                        src="https://maps.google.com/maps?q={{ urlencode($siteSettings->contact_address ?? 'Belbari, Morang, Nepal') }}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-form-card">
                    <h4>Send Message</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0" style="padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('message.send') }}" method="POST">
                        @csrf
                        
                        {{-- Honeypot field - Invisible to users --}}
                        <div style="display:none;" aria-hidden="true">
                            <label for="website_url">Website URL</label>
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <input type="text" name="name" placeholder="Your Full Name" class="gplc-input" required>
                        <input type="email" name="email" placeholder="Email Address" class="gplc-input" required>
                        <input type="tel" name="phone" placeholder="Phone Number" class="gplc-input">
                        <input type="text" name="address" placeholder="Your Address" class="gplc-input">
                        <textarea name="desc" placeholder="Write your message here..." class="gplc-input" required></textarea>
                        
                        {{-- Math Captcha --}}
                        <div style="margin-bottom: 18px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <label style="font-weight: 700; font-size: 14px; color: var(--dark); display: block; margin-bottom: 8px;">
                                Security Check: <span style="color: var(--primary);">{{ $captchaQuestion ?? 'What is 5 + 3?' }}</span>
                            </label>
                            <input type="number" name="captcha" placeholder="Enter your answer" class="gplc-input" style="margin-bottom: 0;" required>
                        </div>

                        <button type="submit" class="btn-gplc w-100 justify-content-center">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== QUICK INFO BOXES ROW ===== --}}
        <div class="row g-4 mt-4">
            @foreach([
                ['fas fa-map-marker-alt','Our Address', $siteSettings->contact_address ?? 'Belbari-2, Lalbatti, Morang, Nepal', '#'],
                ['fas fa-phone-alt','Call Us', $siteSettings->contact_phone ?? '021-546236', 'tel:' . preg_replace('/[^0-9+]/', '', $siteSettings->contact_phone ?? '021546236')],
                ['fas fa-envelope','Email Us', $siteSettings->contact_email ?? 'info@shikshasandesh.edu.np', 'mailto:' . ($siteSettings->contact_email ?? 'info@shikshasandesh.edu.np')],
            ] as [$icon, $label, $value, $link])
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div style="background:var(--bg-surface);border:1.5px solid var(--border-color);border-radius:12px;padding:36px 28px;text-align:center;height:100%;transition:all .3s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='var(--shadow-hover)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                    <div style="width:66px;height:66px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                        <i class="{{ $icon }} fa-lg" style="color:#fff;"></i>
                    </div>
                    <p style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:var(--text-muted);font-weight:700;margin-bottom:8px;">{{ $label }}</p>
                    <a href="{{ $link }}" style="font-size:15px;font-weight:700;color:var(--primary-dark);text-decoration:none;">{{ $value }}</a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

    @include('frontend.layout.sections', ['page' => 'contact'])
@endsection
