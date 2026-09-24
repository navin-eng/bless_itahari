@extends('frontend.layout.master')
@section('frontend-content')
    @php
        $data = App\Models\AboutUs::first();
        $faqs = App\Models\AboutUsFaq::where('status', 1)->orderBy('sort_order')->get();
        $siteSettings = $siteSettings ?? \App\Models\SiteSetting::current();
        $aboutLayout = $siteSettings->about_layout ?? 'classic';
    @endphp

    {{-- ===== LAYOUT DISPATCH ===== --}}
    @switch($aboutLayout)
        @case('modern')
            @include('frontend.pages.about.layout_modern')
            @break
        @case('timeline')
            @include('frontend.pages.about.layout_timeline')
            @break
        @case('magazine')
            @include('frontend.pages.about.layout_magazine')
            @break
        @default
            @include('frontend.pages.about.layout_classic')
    @endswitch

    @php
        $rawContent = $data ? trim($data->desc ?? '') : '';
        // Summernote often puts <p><br></p> when empty
        $hasContent = $rawContent !== '' && $rawContent !== '<p><br></p>' && $rawContent !== '<p></p>';
    @endphp

    {{-- ===== RICH CONTENT (from Summernote — shared across all layouts) ===== --}}
    @if($data && trim($data->desc) !== '' && trim($data->desc) !== '<p><br></p>')
    <section class="section-block" style="padding: 60px 0;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">Detailed Overview</span>
                <h2 class="section-title mt-2">More About Us</h2>
                <div class="section-divider center"></div>
            </div>
            <div class="about-content" data-aos="fade-up">
                <div class="rich">
                    {!! $data->desc !!}
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== FAQs (shared across all layouts) ===== --}}
    @if($faqs->count())
    <section style="padding: 60px 0; background: #f8fafc;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">Student FAQs</span>
                <h2 class="section-title mt-2">Questions Students Commonly Ask</h2>
                <div class="section-divider center"></div>
                <p class="text-muted mt-3 mb-0">Quick answers to help you learn more about our school.</p>
            </div>

            <div class="accordion" id="aboutFaqAccordion" style="max-width: 800px; margin: 0 auto;">
                @foreach($faqs as $faq)
                    <div class="accordion-item mb-3 border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <h2 class="accordion-header" id="faq-heading-{{ $faq->id }}">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="faq-collapse-{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq-collapse-{{ $faq->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            aria-labelledby="faq-heading-{{ $faq->id }}" data-bs-parent="#aboutFaqAccordion">
                            <div class="accordion-body">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('frontend.layout.sections', ['page' => 'aboutus'])
@endsection