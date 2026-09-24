@extends('frontend.layout.master')
@section('frontend-content')

    @php
        $typeColors = [
            'holiday' => '#ef4444',
            'exam' => '#8b5cf6',
            'test' => '#f97316',
            'cca_eca' => '#06b6d4',
            'result' => '#eab308',
            'other' => '#10b981',
        ];
    @endphp

    <div class="page-hero" style="background: linear-gradient(135deg, #1e1b4b, #312e81); padding: 80px 0;">
        <div class="container">
            <div class="page-hero-content text-center text-white" data-aos="fade-up">
                <h1 style="font-family: var(--font-heading); font-weight: 900; font-size: 3rem;">Academic Calendar</h1>
                <p class="mt-3 opacity-75" style="font-size: 1.1rem; color: #e0e7ff;">Explore holidays, exams, and events
                    throughout the academic year.</p>
            </div>
        </div>
    </div>

    <section class="section-block" style="padding: 80px 0; background: #f9fafb;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="calendar-shell">
                        {{-- Calendar Header --}}
                        <div class="calendar-header d-flex justify-content-between align-items-center mb-4">
                            <a href="{{ url('calendar?year=' . $prevYear . '&month=' . $prevMonth) }}"
                                class="btn btn-outline-secondary rounded-circle"
                                style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center;"><i
                                    class="fa-solid fa-chevron-left"></i></a>
                            <div class="text-center">
                                <h3
                                    style="font-family: var(--font-heading); font-weight: 800; color: var(--dark); margin: 0;">
                                    {{ $monthName }}
                                </h3>
                                <span
                                    style="color: #6b7280; font-weight: 600; font-size: 14px;">{{ $monthNameEnglish }}</span>
                            </div>
                            <a href="{{ url('calendar?year=' . $nextYear . '&month=' . $nextMonth) }}"
                                class="btn btn-outline-secondary rounded-circle"
                                style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center;"><i
                                    class="fa-solid fa-chevron-right"></i></a>
                        </div>

                        {{-- Calendar Grid --}}
                        <div class="calendar-scroll-wrapper" style="overflow-x: auto; padding-bottom: 15px;">
                            <div class="calendar-grid" style="min-width: 768px;">
                                @foreach($weekdays as $index => $dayName)
                                    <div class="calendar-day-header {{ $index === 0 || $index === 6 ? 'text-danger' : '' }}">
                                        {{ $dayName }}
                                    </div>
                                @endforeach

                                <!-- Empty cells padding -->
                                @for($i = 0; $i < $startDayOfWeek; $i++)
                                    <div class="calendar-cell empty"></div>
                                @endfor

                                <!-- Actual Days -->
                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $dayOfWeek = ($startDayOfWeek + $day - 1) % 7;
                                        // In BS, Saturday is 6. In AD, Sunday is 0 and Saturday is 6.
                                        $isWeekend = $format === 'bs' ? ($dayOfWeek === 6) : ($dayOfWeek === 0 || $dayOfWeek === 6);
                                        $eventsToday = collect($monthlyEntries->get($day, []));

                                        $isHoliday = $isWeekend || $eventsToday->contains('entry_type', 'holiday');
                                        $isToday = isset($isCurrentMonth) && isset($todayDay) && $isCurrentMonth && $todayDay == $day;
                                    @endphp

                                    <div
                                        class="calendar-cell {{ $isHoliday ? 'holiday' : '' }} {{ $eventsToday->isNotEmpty() ? 'has-event' : '' }} {{ $isToday ? 'today' : '' }}">
                                        @if($isToday)
                                            <div class="today-badge">Today</div>
                                        @endif
                                        <div class="bs-date {{ $isHoliday ? 'text-danger' : '' }}">{{ $daysMapping[$day] ?? $day }}
                                        </div>
                                        <div class="ad-date">{{ $altDaysMapping[$day] ?? '' }}</div>

                                        <div class="cell-events">
                                            @foreach($eventsToday as $ev)
                                                <div class="event-pill"
                                                    style="background-color: {{ $typeColors[$ev->entry_type] ?? '#10b981' }};"
                                                    title="{{ $ev->title }}">{{ $ev->title }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="calendar-sidebar">
                        <h4
                            style="font-family: var(--font-heading); font-weight: 800; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
                            Calendar Guide</h4>
                        <div class="calendar-legend">
                            <div><span style="background:#ef4444;"></span> Holiday</div>
                            <div><span style="background:#8b5cf6;"></span> Exam</div>
                            <div><span style="background:#f97316;"></span> Test</div>
                            <div><span style="background:#06b6d4;"></span> CCA / ECA</div>
                            <div><span style="background:#eab308;"></span> Result</div>
                            <div><span style="background:#10b981;"></span> Other</div>
                        </div>

                        <div class="mt-5">
                            <h4
                                style="font-family: var(--font-heading); font-weight: 800; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
                                Upcoming Events</h4>
                            @forelse($entries->sortBy('start_date')->take(6) as $entry)
                                <div class="calendar-item-card">
                                    <div class="calendar-item-badge"
                                        style="background: {{ $typeColors[$entry->entry_type] ?? '#10b981' }};">
                                        {{ $entry->entry_type_label }}
                                    </div>
                                    <h6 style="font-weight: 700; color: var(--dark); margin: 5px 0;">
                                        @if(isset($entry->is_event) && $entry->is_event)
                                            <a href="{{ route('event.detail', $entry->event_slug) }}"
                                                style="color: var(--dark); text-decoration: none; transition: color 0.2s;"
                                                onmouseover="this.style.color='var(--primary)'"
                                                onmouseout="this.style.color='var(--dark)'">{{ $entry->title }} <i
                                                    class="fa-solid fa-arrow-up-right-from-square"
                                                    style="font-size: 10px; margin-left: 2px;"></i></a>
                                        @else
                                            {{ $entry->title }}
                                        @endif
                                    </h6>
                                    <p class="mb-0" style="font-size: 13px; color: #6b7280; font-weight: 600;">
                                        <i class="fa-regular fa-calendar me-1"></i>
                                        {{ format_system_date($entry->start_date) }}
                                        @if($entry->end_date && $entry->end_date !== $entry->start_date)
                                            - {{ format_system_date($entry->end_date) }}
                                        @endif
                                    </p>
                                </div>
                            @empty
                                <p class="text-muted">No upcoming events.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .calendar-shell,
            .calendar-sidebar {
                background: #fff;
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                border: 1px solid rgba(0, 0, 0, 0.03);
            }

            .calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 10px;
            }

            .calendar-day-header {
                text-align: center;
                font-weight: 800;
                font-size: 14px;
                text-transform: uppercase;
                color: #4b5563;
                padding: 10px 0;
                border-bottom: 2px solid #f3f4f6;
                margin-bottom: 10px;
            }

            .calendar-cell {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                min-height: 100px;
                padding: 10px;
                position: relative;
                display: flex;
                flex-direction: column;
                transition: all 0.2s ease;
                min-width: 0; /* Important: prevents grid column from expanding beyond 1fr due to nowrap text */
            }

            .calendar-cell:hover {
                border-color: var(--primary);
                box-shadow: 0 5px 15px rgba(13, 122, 62, 0.1);
            }

            .calendar-cell.empty {
                background: #f9fafb;
                border: 1px dashed #e5e7eb;
                pointer-events: none;
            }

            .calendar-cell.holiday {
                background-color: #fef2f2;
            }

            .calendar-cell.today {
                background-color: rgba(var(--bs-primary-rgb), 0.05);
                border: 2px solid var(--bs-primary);
                box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.15);
                transform: scale(1.02);
                z-index: 10;
            }

            .today-badge {
                position: absolute;
                top: -10px;
                right: -10px;
                background: var(--bs-primary);
                color: #fff;
                font-size: 10px;
                font-weight: bold;
                padding: 2px 8px;
                border-radius: 12px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                z-index: 11;
            }

            .bs-date {
                font-size: 24px;
                font-weight: 800;
                color: var(--dark);
                line-height: 1;
            }

            .ad-date {
                position: absolute;
                top: 8px;
                right: 8px;
                font-size: 11px;
                font-weight: 600;
                color: #9ca3af;
            }

            .cell-events {
                margin-top: auto;
                display: flex;
                flex-direction: column;
                gap: 4px;
                padding-top: 10px;
            }

            .event-pill {
                font-size: 10px;
                font-weight: 700;
                color: #fff;
                padding: 3px 6px;
                border-radius: 4px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                cursor: pointer;
                width: 100%; /* Ensure it truncates within min-width: 0 */
            }

            .calendar-legend {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .calendar-legend div {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                font-weight: 600;
                color: #4b5563;
            }

            .calendar-legend span {
                width: 14px;
                height: 14px;
                border-radius: 4px;
                display: inline-block;
            }

            .calendar-item-card {
                background: #f9fafb;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 12px;
                border: 1px solid #f3f4f6;
                transition: transform 0.2s ease;
            }

            .calendar-item-card:hover {
                transform: translateX(5px);
                border-color: #e5e7eb;
            }

            .calendar-item-badge {
                color: #fff;
                display: inline-block;
                padding: 4px 10px;
                border-radius: 50px;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
            }

            @media (max-width: 991px) {
                .calendar-cell {
                    min-height: 80px;
                    padding: 8px 5px;
                }

                .bs-date {
                    font-size: 18px;
                }

                .ad-date {
                    font-size: 9px;
                    top: 4px;
                    right: 4px;
                }

                .event-pill {
                    font-size: 9px;
                    padding: 2px 4px;
                }
            }

            /* Mobile adjustments */
            @media (max-width: 768px) {
                .calendar-shell {
                    padding: 15px;
                }

                .calendar-cell {
                    min-height: 75px;
                    padding: 5px;
                }

                .today-badge {
                    top: -5px;
                    right: -5px;
                    font-size: 9px;
                    padding: 1px 6px;
                }

                .bs-date {
                    font-size: 15px;
                }

                .ad-date {
                    position: static;
                    display: block;
                    font-size: 10px;
                    margin-top: 2px;
                }
                
                .cell-events {
                    padding-top: 5px;
                }
            }

            @media (max-width: 576px) {
                .calendar-grid {
                    gap: 5px;
                }

                .calendar-day-header {
                    font-size: 10px;
                    padding: 5px 0;
                }

                .calendar-cell {
                    min-height: 60px;
                }

                .event-pill {
                    display: none;
                }

                /* Hide pills on very small screens, rely on color dots if needed */
                .calendar-cell.has-event::after {
                    content: '';
                    position: absolute;
                    bottom: 5px;
                    left: 5px;
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                    background: var(--primary);
                }
            }
        </style>
    @endpush

    @include('frontend.layout.sections', ['page' => 'calendar'])
@endsection