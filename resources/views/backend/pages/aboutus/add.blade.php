@extends('backend.pages.layout.master')
@push('b-title', 'About Us Management')

@section('backend-content')
    @php
        $condition = !is_null($aboutus);
        $siteSettings = $siteSettings ?? \App\Models\SiteSetting::current();
        $existingValues = $siteSettings->about_values ? json_decode($siteSettings->about_values, true) : [];
        
        $features = !empty($siteSettings->about_features) ? json_decode($siteSettings->about_features, true) : [
            ['title' => 'Child-Centered Care', 'subtitle' => 'Personalized pacing & emotional safety', 'icon' => 'bi-person-hearts', 'color' => '#0d6efd'],
            ['title' => 'STEM & Practical Labs', 'subtitle' => 'Digital literacy & hands-on science', 'icon' => 'bi-cpu-fill', 'color' => '#10b981'],
            ['title' => 'Holistic Co-Curriculars', 'subtitle' => 'Sports, public speaking & creative arts', 'icon' => 'bi-trophy-fill', 'color' => '#f59e0b'],
            ['title' => 'Safe & Caring Campus', 'subtitle' => 'CCTV secured & caring pastoral guidance', 'icon' => 'bi-shield-check', 'color' => '#6366f1'],
        ];

        $amenities = !empty($siteSettings->about_amenities) ? json_decode($siteSettings->about_amenities, true) : [
            ['title' => 'Computer & Coding Labs', 'desc' => 'High-speed internet workstations, coding curricula, and digital projection systems.', 'icon' => 'bi-laptop', 'color' => '#0d6efd'],
            ['title' => 'Science Laboratories', 'desc' => 'Well-ventilated, safely equipped Physics, Chemistry, and Biology practical stations.', 'icon' => 'bi-flask', 'color' => '#059669'],
            ['title' => 'Resource-Rich Library', 'desc' => 'Curated collection of academic books, periodicals, encyclopedias, and quiet reading nooks.', 'icon' => 'bi-book-half', 'color' => '#d97706'],
            ['title' => 'Sports & Play Arena', 'desc' => 'Spacious grounds for football, basketball, cricket, badminton, and early-childhood play.', 'icon' => 'bi-dribbble', 'color' => '#dc2626'],
            ['title' => 'Safe School Transport', 'desc' => 'Punctual, attendant-monitored bus routes serving Itahari, Belbari, and adjoining areas.', 'icon' => 'bi-bus-front', 'color' => '#7c3aed'],
            ['title' => 'Hygienic Canteen & Pure Water', 'desc' => 'Freshly prepared nutritious meals and multi-stage RO purified drinking water facilities.', 'icon' => 'bi-cup-hot', 'color' => '#0284c7'],
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold">About Us Management</h3>
            <p class="text-muted mb-0">Control every section of the public About Us page — headings, stories, stats, pillars, leadership, facilities & FAQs.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('about/us') }}" target="_blank" class="btn btn-outline-secondary px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Live Page
            </a>
            <button type="submit" form="aboutContentForm" class="btn btn-primary px-4 fw-semibold shadow-sm">
                <i class="bi bi-check2-circle me-1"></i> Save All Changes
            </button>
        </div>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following issues:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ===== ABOUT US COMPREHENSIVE CONTROL PANEL ===== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom pt-3 pb-0 px-3 px-md-4">
            <ul class="nav nav-tabs border-bottom-0 flex-nowrap overflow-auto" id="aboutUsTabs" role="tablist" style="gap: 5px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold text-nowrap" id="tab-hero" data-bs-toggle="tab" data-bs-target="#pane-hero" type="button" role="tab">
                        <i class="bi bi-image me-1 text-primary"></i> 1. Hero & Header
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-story" data-bs-toggle="tab" data-bs-target="#pane-story" type="button" role="tab">
                        <i class="bi bi-building me-1 text-info"></i> 2. Story & Heritage
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-mission" data-bs-toggle="tab" data-bs-target="#pane-mission" type="button" role="tab">
                        <i class="bi bi-bullseye me-1 text-danger"></i> 3. Mission & Values
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-pillars" data-bs-toggle="tab" data-bs-target="#pane-pillars" type="button" role="tab">
                        <i class="bi bi-grid-3x3-gap-fill me-1 text-success"></i> 4. Core Pillars
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-stats" data-bs-toggle="tab" data-bs-target="#pane-stats" type="button" role="tab">
                        <i class="bi bi-123 me-1 text-warning"></i> 5. Stats Counters
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-amenities" data-bs-toggle="tab" data-bs-target="#pane-amenities" type="button" role="tab">
                        <i class="bi bi-shield-check me-1 text-primary"></i> 6. Facilities
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-principal" data-bs-toggle="tab" data-bs-target="#pane-principal" type="button" role="tab">
                        <i class="bi bi-person-badge me-1 text-secondary"></i> 7. Leadership
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-cta" data-bs-toggle="tab" data-bs-target="#pane-cta" type="button" role="tab">
                        <i class="bi bi-megaphone me-1 text-danger"></i> 8. CTA Banner
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-content" data-bs-toggle="tab" data-bs-target="#pane-content" type="button" role="tab">
                        <i class="bi bi-file-text me-1 text-dark"></i> 9. Rich Article
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" id="tab-faq" data-bs-toggle="tab" data-bs-target="#pane-faq" type="button" role="tab">
                        <i class="bi bi-patch-question me-1 text-success"></i> 10. FAQs
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4 border-top">
            <form action="{{ route('aboutus.structured.update') }}" method="POST" enctype="multipart/form-data" id="aboutContentForm">
                @csrf
                
                <div class="tab-content" id="aboutUsTabsContent">
                    
                    {{-- ─── TAB 1: HERO & HEADER ─────────────────────────── --}}
                    <div class="tab-pane fade show active" id="pane-hero" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-primary border-4">
                            <h6 class="fw-bold mb-1 text-primary"><i class="bi bi-info-circle me-1"></i> Hero Section Settings</h6>
                            <small class="text-muted">This is the top banner visitors see when they open the About Us page.</small>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hero Top Badge Text</label>
                                <input type="text" name="about_badge_text" class="form-control" placeholder="e.g. Est. 2061 • Quality Education You Can Trust" value="{{ old('about_badge_text', $siteSettings->about_badge_text) }}">
                                <small class="text-muted">Displays in the small rounded badge right above the main title.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hero Background Image (Optional)</label>
                                @if($siteSettings->about_hero_image && file_exists(public_path($siteSettings->about_hero_image)))
                                    <div class="mb-2 d-flex align-items-center gap-3">
                                        <img src="{{ asset($siteSettings->about_hero_image) }}" style="max-height: 80px; border-radius: 8px; border: 1px solid #ddd;" alt="Hero">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_about_hero_image" value="1" id="rmHero">
                                            <label class="form-check-label text-danger small fw-semibold" for="rmHero">Remove image</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="about_hero_image" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended: High-resolution landscape photo (1920x800px).</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Hero Main Heading</label>
                                <textarea name="about_hero_title" class="form-control" rows="2" placeholder="Fostering Curiosity, Character & Academic Excellence">{{ old('about_hero_title', $siteSettings->about_hero_title) }}</textarea>
                                <small class="text-muted">Leave empty to use the default heading.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Hero Subheading</label>
                                <textarea name="about_hero_subtitle" class="form-control" rows="3" placeholder="At Bless Itahari, we empower students from Playgroup to Grade 12...">{{ old('about_hero_subtitle', $siteSettings->about_hero_subtitle) }}</textarea>
                                <small class="text-muted">Descriptive subtitle shown beneath the hero heading.</small>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 2: STORY & HERITAGE ───────────────────────── --}}
                    <div class="tab-pane fade" id="pane-story" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-info border-4">
                            <h6 class="fw-bold mb-1 text-info"><i class="bi bi-buildings me-1"></i> School Story & Heritage</h6>
                            <small class="text-muted">Manage the narrative story, campus image, and foundational history.</small>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Section Story Headline</label>
                                <input type="text" name="about_story_title" class="form-control" placeholder="A Premier Educational Sanctuary in Itahari, Sunsari" value="{{ old('about_story_title', $siteSettings->about_story_title) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Established Year</label>
                                <input type="text" name="about_established_year" class="form-control" placeholder="e.g. 2061 B.S. (2004 A.D.)" value="{{ old('about_established_year', $siteSettings->about_established_year) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Affiliation Board</label>
                                <input type="text" name="about_affiliation" class="form-control" placeholder="e.g. CDC Nepal & NEB" value="{{ old('about_affiliation', $siteSettings->about_affiliation) }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Campus / School Photo</label>
                                @if($siteSettings->about_school_image && file_exists(public_path($siteSettings->about_school_image)))
                                    <div class="mb-2 d-flex align-items-center gap-3">
                                        <img src="{{ asset($siteSettings->about_school_image) }}" style="max-height: 100px; border-radius: 8px; border: 1px solid #ddd;" alt="School">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_about_school_image" value="1" id="rmSchool">
                                            <label class="form-check-label text-danger small fw-semibold" for="rmSchool">Remove image</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="about_school_image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Introduction Lead Paragraph</label>
                                <textarea name="about_intro" class="form-control" rows="3" placeholder="Founded with a profound commitment to academic distinction, moral development...">{{ old('about_intro', $siteSettings->about_intro) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Extended Narrative Paragraph</label>
                                <textarea name="about_story_body" class="form-control" rows="4" placeholder="Bless Itahari combines experiential learning pedagogy, dedicated teacher mentorship...">{{ old('about_story_body', $siteSettings->about_story_body) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 3: MISSION, VISION & VALUES ───────────────── --}}
                    <div class="tab-pane fade" id="pane-mission" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-danger border-4">
                            <h6 class="fw-bold mb-1 text-danger"><i class="bi bi-compass me-1"></i> Vision, Mission & Values</h6>
                            <small class="text-muted">Set your institution's guiding principles and core moral anchors.</small>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Our Vision Statement</label>
                                <textarea name="about_vision" class="form-control" rows="5" placeholder="To stand as an exemplary center of academic and ethical excellence...">{{ old('about_vision', $siteSettings->about_vision) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Our Mission Statement</label>
                                <textarea name="about_mission" class="form-control" rows="5" placeholder="To impart comprehensive, inclusive, and learner-centered education...">{{ old('about_mission', $siteSettings->about_mission) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Core Values (संस्कार र मर्यादा)</label>
                                <p class="text-muted small mb-2">Add each core value below with title and translation or explanation.</p>
                                <div id="valuesContainer">
                                    @forelse($existingValues as $i => $val)
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" value="{{ $val }}" placeholder="e.g. Integrity (सत्यता): Honesty in thought, speech, and pursuit.">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @empty
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" value="Integrity (सत्यता): Honesty in thought, speech, and academic pursuit." placeholder="e.g. Integrity">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" value="Discipline (अनुशासन): Punctuality, self-regulation, and mutual respect." placeholder="e.g. Discipline">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" value="Innovation (सिर्जनशीलता): Creative questioning and modern practical solutions." placeholder="e.g. Innovation">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" value="Empathy (सद्भाव): Kindness, community responsibility, and mutual aid." placeholder="e.g. Empathy">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm mt-2" id="addValueBtn">
                                    <i class="bi bi-plus-lg me-1"></i> Add Another Core Value
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 4: 4 CORE PILLARS ─────────────────────────── --}}
                    <div class="tab-pane fade" id="pane-pillars" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-success border-4">
                            <h6 class="fw-bold mb-1 text-success"><i class="bi bi-grid-3x3-gap-fill me-1"></i> 4 Core Pillars of Excellence</h6>
                            <small class="text-muted">These 4 pillars appear directly next to the school image on the About Us page.</small>
                        </div>

                        <div class="row g-4">
                            @foreach($features as $idx => $feat)
                                <div class="col-md-6">
                                    <div class="card border rounded-3 p-3 h-100 bg-white shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill">Pillar #{{ $idx + 1 }}</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <small class="text-muted">Color:</small>
                                                <input type="color" name="about_features[{{ $idx }}][color]" value="{{ $feat['color'] ?? '#0d6efd' }}" class="form-control form-control-color p-0 border-0" style="width: 32px; height: 32px;">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Bootstrap Icon Class</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi {{ $feat['icon'] ?? 'bi-star-fill' }}"></i></span>
                                                <input type="text" name="about_features[{{ $idx }}][icon]" class="form-control" value="{{ $feat['icon'] ?? 'bi-star-fill' }}" placeholder="e.g. bi-person-hearts">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Pillar Title</label>
                                            <input type="text" name="about_features[{{ $idx }}][title]" class="form-control" value="{{ $feat['title'] ?? '' }}" placeholder="e.g. Child-Centered Care">
                                        </div>
                                        <div>
                                            <label class="form-label fw-semibold small">Pillar Subtitle / Description</label>
                                            <input type="text" name="about_features[{{ $idx }}][subtitle]" class="form-control" value="{{ $feat['subtitle'] ?? '' }}" placeholder="e.g. Personalized pacing & emotional safety">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── TAB 5: STATISTICAL MILESTONES ─────────────────── --}}
                    <div class="tab-pane fade" id="pane-stats" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-warning border-4">
                            <h6 class="fw-bold mb-1 text-warning"><i class="bi bi-123 me-1"></i> Key Statistics & Counters</h6>
                            <small class="text-muted">These prominent counter badges showcase your institution's heritage, size, and pass rates.</small>
                        </div>

                        <div class="row g-4">
                            {{-- Stat 1 --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="card border rounded-3 p-3 bg-white shadow-sm h-100">
                                    <span class="badge bg-warning bg-opacity-15 text-warning fw-bold mb-2 align-self-start">Counter 1</span>
                                    <label class="form-label fw-semibold small">Number / Stat</label>
                                    <input type="text" name="about_stat_1_number" class="form-control mb-2" value="{{ old('about_stat_1_number', $siteSettings->about_stat_1_number ?: '30+') }}" placeholder="e.g. 30+">
                                    <label class="form-label fw-semibold small">Label</label>
                                    <input type="text" name="about_stat_1_label" class="form-control" value="{{ old('about_stat_1_label', $siteSettings->about_stat_1_label ?: 'Years of Heritage') }}" placeholder="e.g. Years of Heritage">
                                </div>
                            </div>

                            {{-- Stat 2 --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="card border rounded-3 p-3 bg-white shadow-sm h-100">
                                    <span class="badge bg-primary bg-opacity-15 text-primary fw-bold mb-2 align-self-start">Counter 2</span>
                                    <label class="form-label fw-semibold small">Number / Stat</label>
                                    <input type="text" name="about_stat_2_number" class="form-control mb-2" value="{{ old('about_stat_2_number', $siteSettings->about_stat_2_number ?: '1,200+') }}" placeholder="e.g. 1,200+">
                                    <label class="form-label fw-semibold small">Label</label>
                                    <input type="text" name="about_stat_2_label" class="form-control" value="{{ old('about_stat_2_label', $siteSettings->about_stat_2_label ?: 'Enrolled Students') }}" placeholder="e.g. Enrolled Students">
                                </div>
                            </div>

                            {{-- Stat 3 --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="card border rounded-3 p-3 bg-white shadow-sm h-100">
                                    <span class="badge bg-success bg-opacity-15 text-success fw-bold mb-2 align-self-start">Counter 3</span>
                                    <label class="form-label fw-semibold small">Number / Stat</label>
                                    <input type="text" name="about_stat_3_number" class="form-control mb-2" value="{{ old('about_stat_3_number', $siteSettings->about_stat_3_number ?: '100%') }}" placeholder="e.g. 100%">
                                    <label class="form-label fw-semibold small">Label</label>
                                    <input type="text" name="about_stat_3_label" class="form-control" value="{{ old('about_stat_3_label', $siteSettings->about_stat_3_label ?: 'SEE & NEB Success') }}" placeholder="e.g. SEE & NEB Success">
                                </div>
                            </div>

                            {{-- Stat 4 --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="card border rounded-3 p-3 bg-white shadow-sm h-100">
                                    <span class="badge bg-info bg-opacity-15 text-info fw-bold mb-2 align-self-start">Counter 4</span>
                                    <label class="form-label fw-semibold small">Number / Stat</label>
                                    <input type="text" name="about_stat_4_number" class="form-control mb-2" value="{{ old('about_stat_4_number', $siteSettings->about_stat_4_number ?: '45+') }}" placeholder="e.g. 45+">
                                    <label class="form-label fw-semibold small">Label</label>
                                    <input type="text" name="about_stat_4_label" class="form-control" value="{{ old('about_stat_4_label', $siteSettings->about_stat_4_label ?: 'Qualified Teachers') }}" placeholder="e.g. Qualified Teachers">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 6: CAMPUS AMENITIES & FACILITIES ──────────── --}}
                    <div class="tab-pane fade" id="pane-amenities" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-primary border-4">
                            <h6 class="fw-bold mb-1 text-primary"><i class="bi bi-shield-check me-1"></i> Campus Amenities & Infrastructure</h6>
                            <small class="text-muted">Customize the 6 facility highlights on the About Us page.</small>
                        </div>

                        <div class="row g-4">
                            @foreach($amenities as $idx => $amenity)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border rounded-3 p-3 h-100 bg-white shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-light text-dark border px-2 py-1">Facility #{{ $idx + 1 }}</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <small class="text-muted">Color:</small>
                                                <input type="color" name="about_amenities[{{ $idx }}][color]" value="{{ $amenity['color'] ?? '#0d6efd' }}" class="form-control form-control-color p-0 border-0" style="width: 28px; height: 28px;">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold small">Icon Class</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi {{ $amenity['icon'] ?? 'bi-check-circle' }}"></i></span>
                                                <input type="text" name="about_amenities[{{ $idx }}][icon]" class="form-control" value="{{ $amenity['icon'] ?? 'bi-check-circle' }}">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold small">Facility Title</label>
                                            <input type="text" name="about_amenities[{{ $idx }}][title]" class="form-control form-control-sm" value="{{ $amenity['title'] ?? '' }}">
                                        </div>
                                        <div>
                                            <label class="form-label fw-semibold small">Description</label>
                                            <textarea name="about_amenities[{{ $idx }}][desc]" class="form-control form-control-sm" rows="2">{{ $amenity['desc'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── TAB 7: LEADERSHIP & PRINCIPAL ─────────────────── --}}
                    <div class="tab-pane fade" id="pane-principal" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-secondary border-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-person-badge me-1"></i> Leadership Section</h6>
                                <small class="text-muted">Manage the default principal quote and view multi-leader messages.</small>
                            </div>
                            <a href="{{ route('college_message.table') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-people me-1"></i> Open All Leadership Messages
                            </a>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Principal Photo</label>
                                @if($siteSettings->about_principal_image && file_exists(public_path($siteSettings->about_principal_image)))
                                    <div class="mb-2 d-flex align-items-center gap-3">
                                        <img src="{{ asset($siteSettings->about_principal_image) }}" style="max-height: 80px; width: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;" alt="Principal">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_about_principal_image" value="1" id="rmPrincipal">
                                            <label class="form-check-label text-danger small fw-semibold" for="rmPrincipal">Remove</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="about_principal_image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Principal Full Name</label>
                                <input type="text" name="about_principal_name" class="form-control" placeholder="e.g. Mr. Ramesh Koirala" value="{{ old('about_principal_name', $siteSettings->about_principal_name) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Designation</label>
                                <input type="text" name="about_principal_designation" class="form-control" placeholder="e.g. Principal / Campus Chief" value="{{ old('about_principal_designation', $siteSettings->about_principal_designation) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Principal Message / Quote</label>
                                <textarea name="about_principal_message" class="form-control" rows="5" placeholder="Write the principal's message here...">{{ old('about_principal_message', $siteSettings->about_principal_message) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 8: CALL TO ACTION BANNER ──────────────────── --}}
                    <div class="tab-pane fade" id="pane-cta" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-danger border-4">
                            <h6 class="fw-bold mb-1 text-danger"><i class="bi bi-megaphone me-1"></i> Bottom Call-to-Action (CTA) Banner</h6>
                            <small class="text-muted">Customize the closing invitation banner at the bottom of the page.</small>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">CTA Banner Heading</label>
                                <input type="text" name="about_cta_title" class="form-control" placeholder="Join Our Academic Family Today" value="{{ old('about_cta_title', $siteSettings->about_cta_title ?: 'Join Our Academic Family Today') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Primary Button Text</label>
                                <input type="text" name="about_cta_button_text" class="form-control" placeholder="Apply for Admission" value="{{ old('about_cta_button_text', $siteSettings->about_cta_button_text ?: 'Apply for Admission') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Primary Button URL</label>
                                <input type="text" name="about_cta_button_url" class="form-control" placeholder="/apply" value="{{ old('about_cta_button_url', $siteSettings->about_cta_button_url ?: '/apply') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">CTA Subtitle / Paragraph</label>
                                <textarea name="about_cta_subtitle" class="form-control" rows="3" placeholder="Give your child the foundation of academic brilliance...">{{ old('about_cta_subtitle', $siteSettings->about_cta_subtitle ?: 'Give your child the foundation of academic brilliance, moral integrity, and modern capabilities. Admissions are currently welcoming inquiries across all levels.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 9: SUMMERNOTE RICH ARTICLE ─────────────────── --}}
                    <div class="tab-pane fade" id="pane-content" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-dark border-4">
                            <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-file-text me-1"></i> Additional Custom Rich Article</h6>
                            <small class="text-muted">Optional detailed article or custom institutional history rendered cleanly under the amenities section.</small>
                        </div>

                        <div class="editor-workspace border rounded-3 p-3 bg-light">
                            <div class="editor-toolbar-row d-flex justify-content-between align-items-center mb-3">
                                <div class="editor-mode-switch d-flex gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="visualEditorBtn">
                                        <i class="bi bi-stars"></i> Visual Editor
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" id="htmlEditorBtn">
                                        <i class="bi bi-code-square"></i> HTML Source
                                    </button>
                                </div>
                            </div>
                            
                            <div class="editor-surface bg-white border rounded-3">
                                <div id="visualEditorWrap">
                                    <textarea id="summernote" name="desc">{{ old('desc', $aboutus->desc ?? '') }}</textarea>
                                </div>

                                <div id="htmlEditorWrap" class="d-none p-3">
                                    <label class="form-label fw-semibold mb-2">Raw HTML Source</label>
                                    <textarea id="htmlSourceEditor" class="form-control editor-code-surface border-0" rows="16" spellcheck="false" style="background: #f8f9fa;">{{ old('desc', $aboutus->desc ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ─── TAB 10: FAQS MANAGER ──────────────────────────── --}}
                    <div class="tab-pane fade" id="pane-faq" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1"><i class="bi bi-patch-question me-2 text-success"></i> Manage About Us FAQs</h5>
                                <p class="text-muted mb-0">These FAQs appear inside the modern accordion on the About Us page.</p>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                                <i class="bi bi-plus-lg me-1"></i> Add FAQ
                            </button>
                        </div>

                        @forelse($faqs as $faq)
                            @if($loop->first)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Question</th>
                                                <th>Answer</th>
                                                <th style="width: 80px;">Order</th>
                                                <th style="width: 100px;">Status</th>
                                                <th style="width: 220px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            @endif
                                            <tr>
                                                <td>{{ $faq->id }}</td>
                                                <td class="fw-semibold">{{ $faq->question }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($faq->answer, 110) }}</td>
                                                <td>{{ $faq->sort_order }}</td>
                                                <td>
                                                    <span class="badge {{ $faq->status ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $faq->status ? 'Visible' : 'Hidden' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <a href="{{ route('aboutus.faq.status', $faq->id) }}" class="btn btn-sm {{ $faq->status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                            {{ $faq->status ? 'Hide' : 'Show' }}
                                                        </a>
                                                        <a href="{{ route('aboutus.faq.destroy', $faq->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete this FAQ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                            @if($loop->last)
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @empty
                            <div class="text-center p-4 bg-light rounded-3">
                                <i class="bi bi-question-circle text-muted" style="font-size: 2.5rem;"></i>
                                <p class="text-muted mt-2 mb-0">No custom FAQs created yet. The website will display standard school FAQs.</p>
                            </div>
                        @endforelse
                    </div>

                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save All About Us Changes
                    </button>
                    <a href="{{ url('about/us') }}" target="_blank" class="btn btn-outline-secondary px-3">
                        <i class="bi bi-eye me-1"></i> Preview Live Page
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ADD FAQ MODAL --}}
    <div class="modal fade" id="addFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('aboutus.faq.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add New FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question</label>
                            <input type="text" name="question" class="form-control" placeholder="e.g. What are the school operating hours?" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Answer</label>
                            <textarea name="answer" class="form-control" rows="5" placeholder="Write a clear answer..." required></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faqs->count() + 1) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-bold">Save FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT FAQ MODALS --}}
    @foreach($faqs as $faq)
        <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('aboutus.faq.update', $faq->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit FAQ #{{ $faq->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Question</label>
                                <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Answer</label>
                                <textarea name="answer" class="form-control" rows="5" required>{{ $faq->answer }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">Display Order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $faq->sort_order }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary fw-bold">Update FAQ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Values repeater
        const addValueBtn = document.getElementById('addValueBtn');
        const valuesContainer = document.getElementById('valuesContainer');
        if (addValueBtn && valuesContainer) {
            addValueBtn.addEventListener('click', function () {
                const row = document.createElement('div');
                row.className = 'input-group mb-2 value-row';
                row.innerHTML = `
                    <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                    <input type="text" name="about_values[]" class="form-control" placeholder="e.g. Mutual Respect (सद्भाव)">
                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                `;
                valuesContainer.appendChild(row);
            });
        }

        // Summernote init
        if ($('#summernote').length) {
            $('#summernote').summernote({
                height: 320,
                placeholder: 'Write any custom institutional background or article content here...'
            });
        }

        // Visual / HTML mode switcher
        const visualBtn = document.getElementById('visualEditorBtn');
        const htmlBtn = document.getElementById('htmlEditorBtn');
        const visualWrap = document.getElementById('visualEditorWrap');
        const htmlWrap = document.getElementById('htmlEditorWrap');
        const htmlSource = document.getElementById('htmlSourceEditor');

        if (visualBtn && htmlBtn && visualWrap && htmlWrap && htmlSource) {
            visualBtn.addEventListener('click', function () {
                $('#summernote').summernote('code', htmlSource.value);
                visualWrap.classList.remove('d-none');
                htmlWrap.classList.add('d-none');
                visualBtn.className = 'btn btn-primary btn-sm';
                htmlBtn.className = 'btn btn-outline-dark btn-sm';
            });

            htmlBtn.addEventListener('click', function () {
                htmlSource.value = $('#summernote').summernote('code');
                htmlWrap.classList.remove('d-none');
                visualWrap.classList.add('d-none');
                htmlBtn.className = 'btn btn-primary btn-sm';
                visualBtn.className = 'btn btn-outline-primary btn-sm';
            });

            document.getElementById('aboutContentForm').addEventListener('submit', function () {
                if (!htmlWrap.classList.contains('d-none')) {
                    $('#summernote').summernote('code', htmlSource.value);
                }
            });
        }
    });
</script>
@endpush
@endsection
