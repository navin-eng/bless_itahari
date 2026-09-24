@if($messages->count() > 0)
    <section class="section-block bg-light" style="padding: 100px 0; overflow: hidden; position: relative;">
        <div class="container content-relative">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag" style="background: rgba(13, 122, 62, 0.1); color: var(--primary);">Leadership Voice</span>
                <h2 class="section-title mt-2">Messages from Officials</h2>
                <div class="section-divider center"></div>
                <p class="mt-3 text-muted">Guiding our institution towards academic excellence and holistic development.</p>
            </div>
            
            @if($messages->count() <= 2)
                <div class="row g-5 justify-content-center">
                    @foreach($messages as $msg)
                        <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="official-card-modern glass-panel h-100">
                                <div class="card-wave-bg"></div>
                                
                                <div class="official-avatar-wrapper">
                                    <img src="{{ $msg->image ? asset($msg->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $msg->name }}" class="official-avatar shadow-sm">
                                    <div class="quote-icon-badge shadow">
                                        <i class="fa-solid fa-quote-right"></i>
                                    </div>
                                </div>
                                
                                <div class="official-card-body text-center">
                                    <h4 class="official-name">{{ $msg->name }}</h4>
                                    <span class="official-designation">{{ $msg->designation }}</span>
                                    
                                    <p class="official-quote-text mt-4">
                                        "{{ \Illuminate\Support\Str::limit($msg->message, 180) }}"
                                    </p>
                                    
                                    <button type="button" class="btn-read-message mt-3" data-bs-toggle="modal" data-bs-target="#officialMessageModal{{ $msg->id }}">
                                        Read Full Message <i class="fa-solid fa-arrow-right ms-2 transition-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="swiper official-messages-swiper" data-aos="fade-up" style="padding-top: 20px;">
                    <div class="swiper-wrapper" style="padding-bottom: 60px;">
                        @foreach($messages as $msg)
                            <div class="swiper-slide" style="height: auto;">
                                <div class="official-card-modern glass-panel h-100 mx-2">
                                    <div class="card-wave-bg"></div>
                                    
                                    <div class="official-avatar-wrapper">
                                        <img src="{{ $msg->image ? asset($msg->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $msg->name }}" class="official-avatar shadow-sm">
                                        <div class="quote-icon-badge shadow">
                                            <i class="fa-solid fa-quote-right"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="official-card-body text-center">
                                        <h4 class="official-name">{{ $msg->name }}</h4>
                                        <span class="official-designation">{{ $msg->designation }}</span>
                                        
                                        <p class="official-quote-text mt-4">
                                            "{{ \Illuminate\Support\Str::limit($msg->message, 160) }}"
                                        </p>
                                        
                                        <button type="button" class="btn-read-message mt-3" data-bs-toggle="modal" data-bs-target="#officialMessageModal{{ $msg->id }}">
                                            Read Full Message <i class="fa-solid fa-arrow-right ms-2 transition-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination official-msg-pagination"></div>
                </div>
            @endif
        </div>
    </section>

    @foreach($messages as $msg)
        <div class="modal fade" id="officialMessageModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                    <div class="modal-header border-0 pb-0 position-relative" style="background: linear-gradient(135deg, var(--dark), var(--primary)); padding: 40px 30px;">
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="d-flex align-items-center gap-4 text-white">
                                <img src="{{ $msg->image ? asset($msg->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $msg->name }}" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,0.2); box-shadow: var(--shadow-sm);">
                            <div>
                                <h4 class="fw-bold mb-1" style="font-family: var(--font-heading);">{{ $msg->name }}</h4>
                                <span style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.8);">{{ $msg->designation }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-4 p-lg-5 position-relative bg-white">
                        <i class="fa-solid fa-quote-left position-absolute" style="font-size: 80px; color: rgba(13, 122, 62, 0.05); top: 30px; left: 30px; z-index: 0;"></i>
                        <div style="position: relative; z-index: 1; font-size: 1.1rem; line-height: 1.8; color: #4b5563; font-family: var(--font-body);">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 pe-4 justify-content-end bg-white">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('styles')
    <style>
        .official-card-modern {
            background: #ffffff;
            border-radius: 24px;
            padding: 0 30px 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            position: relative;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid rgba(0,0,0,0.03);
            margin-top: 60px; /* Space for overlapping avatar */
        }

        .official-card-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px rgba(13, 122, 62, 0.08);
        }

        .card-wave-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(135deg, rgba(13, 122, 62, 0.05) 0%, transparent 100%);
            border-radius: 24px 24px 0 0;
            z-index: 0;
        }

        .official-avatar-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: -70px auto 20px;
            z-index: 2;
        }

        .official-avatar, .official-avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #ffffff;
            background: #ffffff;
            transition: transform 0.5s ease;
        }

        .official-avatar-placeholder {
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .official-card-modern:hover .official-avatar {
            transform: scale(1.05);
        }

        .quote-icon-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            border: 3px solid #ffffff;
            transition: transform 0.3s ease;
        }

        .official-card-modern:hover .quote-icon-badge {
            transform: rotate(10deg) scale(1.1);
            background: var(--accent);
        }

        .official-card-body {
            position: relative;
            z-index: 2;
        }

        .official-name {
            font-family: var(--font-heading);
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 5px;
            font-size: 1.4rem;
        }

        .official-designation {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .official-quote-text {
            font-size: 15px;
            line-height: 1.8;
            color: #55606d;
            font-style: italic;
            position: relative;
            margin-bottom: 25px;
        }

        .btn-read-message {
            background: transparent;
            border: 2px solid #e5e7eb;
            color: #374151;
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-read-message .transition-icon {
            transition: transform 0.3s ease;
        }

        .btn-read-message:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .btn-read-message:hover .transition-icon {
            transform: translateX(4px);
        }
        
        .official-msg-pagination .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: rgba(13, 122, 62, 0.4);
            opacity: 1;
            transition: all 0.3s;
        }
        .official-msg-pagination .swiper-pagination-bullet-active {
            background: var(--primary);
            width: 24px;
            border-radius: 5px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(document.querySelector('.official-messages-swiper')) {
                new Swiper('.official-messages-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.official-msg-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 30,
                        },
                        1100: {
                            slidesPerView: 3,
                            spaceBetween: 40,
                        }
                    }
                });
            }
        });
    </script>
    @endpush
@endif
