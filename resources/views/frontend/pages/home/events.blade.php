@if($events->count() > 0)
    <section class="section-block" style="background: #f8f9fa; padding: 100px 0;">
        <div class="container content-relative">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5" data-aos="fade-up">
                <div>
                    <span class="section-tag" style="background: rgba(13, 122, 62, 0.1); color: var(--primary);">Campus Life</span>
                    <h2 class="section-title mt-2 mb-0">Events &amp; Calendar Highlights</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('events.index') }}" class="btn-read-more">
                        <i class="fa-solid fa-calendar-week me-2"></i> View All Events
                    </a>
                    <a href="{{ route('calendar') }}" class="btn-read-more" style="background: #e5e7eb; color: #374151; border-color: #e5e7eb;">
                        <i class="fa-solid fa-calendar-days me-2"></i> View Calendar
                    </a>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($events->take(6) as $event)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ url('event/' . $event->slug) }}" class="event-card-animated glass-panel h-100 text-decoration-none d-block">
                            <div class="event-image-wrapper">
                                <img src="{{ $event->image ? asset($event->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $event->name }}" class="event-img">
                                <div class="event-date-badge">
                                    <span class="day">{{ format_system_date($event->visit_date, 'd') }}</span>
                                    <span class="month">{{ format_system_date($event->visit_date, 'M') }}</span>
                                </div>
                                <div class="event-type-badge">
                                    {{ $event->event_type_label ?? 'Campus Event' }}
                                </div>
                            </div>
                            
                            <div class="event-card-content">
                                <h4 class="event-title">{{ $event->name }}</h4>
                                <p class="event-desc">{{ Str::limit(strip_tags($event->description), 100) }}</p>
                                
                                <div class="event-footer mt-4">
                                    <span class="event-link-text">Read Details <i class="fa-solid fa-arrow-right ms-2 transition-icon"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .event-card-animated {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
        }

        .event-card-animated:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(13, 122, 62, 0.1);
        }

        .event-image-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .event-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .event-card-animated:hover .event-img {
            transform: scale(1.1);
        }

        .event-date-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ffffff;
            border-radius: 12px;
            padding: 8px 12px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 2;
            min-width: 65px;
            transition: transform 0.3s ease;
        }
        
        .event-card-animated:hover .event-date-badge {
            transform: scale(1.05);
        }

        .event-date-badge .day {
            display: block;
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .event-date-badge .month {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .event-type-badge {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            letter-spacing: 0.5px;
        }

        .event-card-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            height: calc(100% - 220px);
        }

        .event-title {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--dark);
            margin-bottom: 12px;
            transition: color 0.3s ease;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .event-card-animated:hover .event-title {
            color: var(--primary);
        }

        .event-desc {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
            flex-grow: 1;
        }

        .event-link-text {
            color: var(--primary);
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }

        .event-link-text .transition-icon {
            transition: transform 0.3s ease;
        }

        .event-card-animated:hover .transition-icon {
            transform: translateX(5px);
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
    </style>
    @endpush
@endif
