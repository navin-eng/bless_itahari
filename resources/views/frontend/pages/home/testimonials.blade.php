@if($testimonials->count() > 0)
    <section class="section-block bg-light position-relative" style="padding: 100px 0; overflow: hidden;">
        <!-- Background decorative elements -->
        <div class="position-absolute" style="top: -50px; left: -50px; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(13, 122, 62, 0.05) 0%, rgba(255,255,255,0) 70%);"></div>
        <div class="position-absolute" style="bottom: -100px; right: -50px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(13, 122, 62, 0.05) 0%, rgba(255,255,255,0) 70%);"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="text-center mb-5 pb-2" data-aos="fade-up">
                <span class="section-tag" style="background: rgba(13, 122, 62, 0.1); color: var(--primary);">Student Voice</span>
                <h2 class="section-title mt-2 text-dark">What Our Students Say</h2>
                <div class="section-divider center"></div>
            </div>
            
            <div class="swiper testiSwiper-modern" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @foreach($testimonials as $testi)
                        <div class="swiper-slide h-auto">
                            <div class="testi-card-modern glass-panel h-100">
                                <div class="quote-watermark">
                                    <i class="fa-solid fa-quote-right"></i>
                                </div>
                                <div class="testi-content-modern">
                                    <p class="testi-text-modern">"{{ $testi->description }}"</p>
                                </div>
                                <div class="testi-author-modern">
                                    <div class="testi-avatar-wrapper">
                                        @if($testi->image)
                                            <img src="{{ asset($testi->image) }}" alt="{{ $testi->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="testi-avatar-fallback" style="display: none;">
                                                <span>{{ substr($testi->name, 0, 1) }}</span>
                                            </div>
                                        @else
                                            <div class="testi-avatar-fallback">
                                                <span>{{ substr($testi->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="testi-author-info">
                                        <h5 class="testi-name-modern">{{ $testi->name }}</h5>
                                        <span class="testi-role-modern">{{ $testi->role ?? 'Student' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination testi-pagination-modern mt-5 position-relative"></div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .testi-card-modern {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0,0,0,0.02);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .testi-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(13, 122, 62, 0.08);
        }

        .quote-watermark {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 60px;
            color: rgba(13, 122, 62, 0.05);
            z-index: 1;
            line-height: 1;
        }

        .testi-content-modern {
            position: relative;
            z-index: 2;
            flex-grow: 1;
            margin-bottom: 30px;
        }

        .testi-text-modern {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #4b5563;
            font-style: italic;
            margin: 0;
        }

        .testi-author-modern {
            display: flex;
            align-items: center;
            gap: 15px;
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }

        .testi-avatar-wrapper {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 2px solid #ffffff;
            background: var(--primary);
        }

        .testi-avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .testi-avatar-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 22px;
            background: linear-gradient(135deg, var(--primary), var(--dark));
        }

        .testi-name-modern {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--dark);
            margin: 0 0 2px 0;
        }

        .testi-role-modern {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Swiper Pagination Styling */
        .testi-pagination-modern .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: #d1d5db;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .testi-pagination-modern .swiper-pagination-bullet-active {
            background: var(--primary);
            width: 30px;
            border-radius: 10px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if(typeof Swiper !== 'undefined') {
                new Swiper('.testiSwiper-modern', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.testi-pagination-modern',
                        clickable: true,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    }
                });
            }
        });
    </script>
    @endpush
@endif
