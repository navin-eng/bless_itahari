@if($counter)
    @php
        $counterItems = [
            ['title' => $counter->title1, 'value' => $counter->counter1, 'suffix' => $counter->suffix1, 'icon' => $counter->icon1],
            ['title' => $counter->title2, 'value' => $counter->counter2, 'suffix' => $counter->suffix2, 'icon' => $counter->icon2],
            ['title' => $counter->title3, 'value' => $counter->counter3, 'suffix' => $counter->suffix3, 'icon' => $counter->icon3],
            ['title' => $counter->title4, 'value' => $counter->counter4, 'suffix' => $counter->suffix4, 'icon' => $counter->icon4],
        ];
    @endphp
    <section class="gplc-stats sectionWorkdata">
        <div class="container content-relative">
            <div class="text-center mb-5 pb-3" data-aos="fade-up">
                <span class="section-tag" style="background: rgba(255, 255, 255, 0.15); color: #fff; border: 1px solid rgba(255,255,255,0.2);">{{ $counter->section_tag ?? 'Our Impact' }}</span>
                @if($counter->section_title)
                    <h2 class="section-title mt-2 text-white">{{ $counter->section_title }}</h2>
                    <div class="section-divider center"></div>
                @endif
            </div>
            
            <div class="row align-items-stretch justify-content-center g-4">
                @foreach($counterItems as $item)
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="stat-box glass-panel-dark h-100 p-4" style="border-radius: 20px; transition: transform 0.4s ease, box-shadow 0.4s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px 0 rgba(0, 0, 0, 0.3)';">
                            <div class="stat-icon" style="background: rgba(255,255,255,0.05); width: 85px; height: 85px; font-size: 2.8rem; margin: 0 auto 25px; border-radius: 50%; box-shadow: inset 0 0 20px rgba(255,255,255,0.05);">
                                <i class="{{ $item['icon'] }} text-warning" style="text-shadow: 0 4px 10px rgba(0,0,0,0.5);"></i>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <span class="counter-number text-white" style="font-family: var(--font-heading); font-size: 3.5rem; letter-spacing: -1px; text-shadow: 0 4px 10px rgba(0,0,0,0.5);" data-number="{{ (int) $item['value'] }}">0</span>
                                <span class="counter-suffix text-warning ms-1" style="font-size: 2.2rem; font-weight: 800; line-height: 1; transform: translateY(-8px);">{{ $item['suffix'] }}</span>
                            </div>
                            <p class="text-white fw-bold mb-0" style="font-size: 0.95rem; letter-spacing: 1.5px; opacity: 0.7;">{{ $item['title'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
