<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Get In Touch</span>
            <h2 class="section-title mt-2">Contact Us</h2>
            <div class="section-divider center"></div>
        </div>
        <div class="row g-4 mt-2">
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
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="gplc-map">
                    <iframe
                        src="https://maps.google.com/maps?q={{ urlencode($siteSettings->contact_address ?? 'Belbari, Morang, Nepal') }}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-form-card">
                    <h4>Send Message</h4>
                    <form action="{{ route('message.send') }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="Your Name" class="gplc-input" required>
                        <input type="email" name="email" placeholder="Email Address" class="gplc-input" required>
                        <input type="tel" name="phone" placeholder="Phone Number" class="gplc-input">
                        <input type="text" name="address" placeholder="Your Address" class="gplc-input">
                        <textarea name="desc" placeholder="Write your message..." class="gplc-input"></textarea>
                        <button type="submit" class="btn-gplc w-100 justify-content-center">
                            <i class="fa-solid fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
