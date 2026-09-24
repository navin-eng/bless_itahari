@extends('backend.pages.layout.master')
@push('b-title', 'About Us')

@section('backend-content')
    @php
        $condition = !is_null($aboutus);
        $contentAction = $condition ? route('aboutus.update', $aboutus->id) : route('aboutus.store');
        $siteSettings = $siteSettings ?? \App\Models\SiteSetting::current();
        $currentLayout = $siteSettings->about_layout ?? 'classic';
        $existingValues = $siteSettings->about_values ? json_decode($siteSettings->about_values, true) : [];
    @endphp

    {{-- ===== STRUCTURED CONTENT & LAYOUT SELECTOR ===== --}}
    {{-- ===== STRUCTURED CONTENT & LAYOUT SELECTOR ===== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom pt-4 pb-0 px-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">About Us Settings</h4>
                    <p class="text-muted mb-0">Manage all content and layout options for your About Us page here.</p>
                </div>
            </div>
            
            <ul class="nav nav-tabs border-bottom-0" id="aboutUsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="tab-hero" data-bs-toggle="tab" data-bs-target="#pane-hero" type="button" role="tab"><i class="bi bi-image me-1"></i> Identity & Intro</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-mission" data-bs-toggle="tab" data-bs-target="#pane-mission" type="button" role="tab"><i class="bi bi-bullseye me-1"></i> Mission & Values</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-principal" data-bs-toggle="tab" data-bs-target="#pane-principal" type="button" role="tab"><i class="bi bi-person-badge me-1"></i> Principal</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-content" data-bs-toggle="tab" data-bs-target="#pane-content" type="button" role="tab"><i class="bi bi-file-text me-1"></i> Rich Content</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-layout" data-bs-toggle="tab" data-bs-target="#pane-layout" type="button" role="tab"><i class="bi bi-grid-1x2 me-1"></i> Layout</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-faq" data-bs-toggle="tab" data-bs-target="#pane-faq" type="button" role="tab"><i class="bi bi-patch-question me-1"></i> FAQs</button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4 border-top">
            <form action="{{ route('aboutus.structured.update') }}" method="POST" enctype="multipart/form-data" id="aboutContentForm">
                @csrf
                
                <div class="tab-content" id="aboutUsTabsContent">
                    
                    {{-- Tab 1: Hero & Intro --}}
                    <div class="tab-pane fade show active" id="pane-hero" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hero Banner Image</label>
                                @if($siteSettings->about_hero_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($siteSettings->about_hero_image) }}" style="max-height: 120px; border-radius: 10px;" alt="Hero">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_about_hero_image" value="1" id="rmHero">
                                            <label class="form-check-label text-danger small" for="rmHero">Remove image</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="about_hero_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">School / Campus Photo</label>
                                @if($siteSettings->about_school_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($siteSettings->about_school_image) }}" style="max-height: 120px; border-radius: 10px;" alt="School">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_about_school_image" value="1" id="rmSchool">
                                            <label class="form-check-label text-danger small" for="rmSchool">Remove image</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="about_school_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Established Year</label>
                                <input type="text" name="about_established_year" class="form-control" placeholder="e.g. 1993 A.D. (2050 B.S.)" value="{{ $siteSettings->about_established_year }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Affiliation</label>
                                <input type="text" name="about_affiliation" class="form-control" placeholder="e.g. NEB Nepal" value="{{ $siteSettings->about_affiliation }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Introduction Paragraph</label>
                                <textarea name="about_intro" class="form-control" rows="4" placeholder="Write a brief introduction about your school...">{{ $siteSettings->about_intro }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Mission & Values --}}
                    <div class="tab-pane fade" id="pane-mission" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mission Statement</label>
                                <textarea name="about_mission" class="form-control" rows="5" placeholder="Our mission is to...">{{ $siteSettings->about_mission }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vision Statement</label>
                                <textarea name="about_vision" class="form-control" rows="5" placeholder="Our vision is to...">{{ $siteSettings->about_vision }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Core Values</label>
                                <div id="valuesContainer">
                                    @forelse($existingValues as $i => $val)
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" value="{{ $val }}" placeholder="e.g. Discipline">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @empty
                                        <div class="input-group mb-2 value-row">
                                            <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                                            <input type="text" name="about_values[]" class="form-control" placeholder="e.g. Discipline">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm mt-2" id="addValueBtn">
                                    <i class="bi bi-plus-lg me-1"></i> Add Value
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Principal's Message --}}
                    <div class="tab-pane fade" id="pane-principal" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Photo</label>
                                @if($siteSettings->about_principal_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($siteSettings->about_principal_image) }}" style="max-height: 100px; border-radius: 50%;" alt="Principal">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_about_principal_image" value="1" id="rmPrincipal">
                                            <label class="form-check-label text-danger small" for="rmPrincipal">Remove</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="about_principal_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="about_principal_name" class="form-control" placeholder="e.g. Mr. Ram Bahadur" value="{{ $siteSettings->about_principal_name }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Designation</label>
                                <input type="text" name="about_principal_designation" class="form-control" placeholder="e.g. Principal" value="{{ $siteSettings->about_principal_designation }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Message / Quote</label>
                                <textarea name="about_principal_message" class="form-control" rows="5" placeholder="Write the principal's message here...">{{ $siteSettings->about_principal_message }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 4: Rich Content --}}
                    <div class="tab-pane fade" id="pane-content" role="tabpanel">
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
                                <div class="editor-action-switch">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="loadTemplateBtn">
                                        <i class="bi bi-magic"></i> Insert Template
                                    </button>
                                </div>
                            </div>
                            
                            <div class="editor-surface bg-white border rounded-3">
                                <div id="visualEditorWrap">
                                    <textarea id="summernote" name="desc">{{ old('desc', $aboutus->desc ?? '') }}</textarea>
                                </div>

                                <div id="htmlEditorWrap" class="d-none p-3">
                                    <label class="form-label fw-semibold mb-2">Raw HTML Source</label>
                                    <textarea id="htmlSourceEditor" class="form-control editor-code-surface border-0" rows="18" spellcheck="false" style="background: #f8f9fa;">{{ old('desc', $aboutus->desc ?? '') }}</textarea>
                                    <small class="text-muted d-block mt-2">Paste complete HTML, inline styles, embed blocks, or custom sections here.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 5: Layout --}}
                    <div class="tab-pane fade" id="pane-layout" role="tabpanel">
                        <h5 class="fw-bold mb-3"><i class="bi bi-grid-1x2 me-2"></i> Choose Page Layout</h5>
                        <p class="text-muted mb-4">Select how you want all the data you entered to be presented to visitors.</p>
                        
                        <div class="row g-3">
                            @php
                                $currentLayout = old('about_layout', $siteSettings->about_layout ?? 'classic');
                                $layouts = [
                                    'classic' => ['label' => 'Classic', 'icon' => 'bi-layout-text-sidebar-reverse', 'desc' => 'Traditional top-down: hero → intro → mission/vision → principal → content'],
                                    'modern' => ['label' => 'Modern Split', 'icon' => 'bi-layout-split', 'desc' => 'Full-width hero → two-column intro → gradient cards → principal spotlight'],
                                    'timeline' => ['label' => 'Timeline', 'icon' => 'bi-clock-history', 'desc' => 'Vertical timeline with milestone nodes and alternating content blocks'],
                                    'magazine' => ['label' => 'Magazine', 'icon' => 'bi-newspaper', 'desc' => 'Editorial style: bold hero → pull-quote → 3-column values grid'],
                                ];
                            @endphp
                            @foreach($layouts as $key => $layout)
                                <div class="col-lg-3 col-md-6">
                                    <label class="layout-card-selector {{ $currentLayout === $key ? 'active' : '' }}" for="layout_{{ $key }}">
                                        <input type="radio" name="about_layout" id="layout_{{ $key }}" value="{{ $key }}"
                                               {{ $currentLayout === $key ? 'checked' : '' }} class="d-none layout-radio">
                                        <div class="layout-card-inner">
                                            <i class="bi {{ $layout['icon'] }} layout-card-icon"></i>
                                            <strong>{{ $layout['label'] }}</strong>
                                            <small class="text-muted d-block mt-1">{{ $layout['desc'] }}</small>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab 6: Manage FAQs --}}
                    <div class="tab-pane fade" id="pane-faq" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1"><i class="bi bi-patch-question me-2"></i> Manage FAQs</h5>
                                <p class="text-muted mb-0">These FAQs appear on the public About Us page for students. You can add, edit, delete, hide, and show them here.</p>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">+ Add FAQ</button>
                        </div>

                        @forelse($faqs as $faq)
                            @if($loop->first)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 70px;">#</th>
                                                <th>Question</th>
                                                <th>Answer</th>
                                                <th style="width: 90px;">Order</th>
                                                <th style="width: 110px;">Status</th>
                                                <th style="width: 260px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            @endif
                                            <tr>
                                                <td>{{ $faq->id }}</td>
                                                <td class="fw-semibold">{{ $faq->question }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($faq->answer, 120) }}</td>
                                                <td>{{ $faq->sort_order }}</td>
                                                <td>
                                                    <span class="badge {{ $faq->status ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $faq->status ? 'Visible' : 'Hidden' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">Edit</button>
                                                        <a href="{{ route('aboutus.faq.status', $faq->id) }}" class="btn btn-sm {{ $faq->status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                            {{ $faq->status ? 'Hide' : 'Show' }}
                                                        </a>
                                                        <a href="{{ route('aboutus.faq.destroy', $faq->id) }}" class="btn btn-danger btn-sm deleteBtn" data-href="{{ route('aboutus.faq.destroy', $faq->id) }}">Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                            @if($loop->last)
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @empty
                            <p class="text-muted mb-0">No FAQs added yet.</p>
                        @endforelse
                    </div>

                </div>

                <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Save All Changes
                    </button>
                    <button type="button" class="btn btn-light border" id="previewFromCurrentModeBtn">
                        <i class="bi bi-display me-1"></i> Preview Current Content
                    </button>
                </div>
            </form>
        </div>
    </div>



    <div class="modal fade" id="addFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('aboutus.faq.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <input type="text" name="question" class="form-control" placeholder="Example: Is Shiksha Sandesh affiliated with NEB Nepal?" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Answer</label>
                            <textarea name="answer" class="form-control" rows="6" placeholder="Write a clear answer for students..." required></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Display Order</label>
                            <input type="text" name="sort_order" class="form-control no-spinner" inputmode="numeric" pattern="[0-9]*" value="{{ old('sort_order', $faqs->count() + 1) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($faqs as $faq)
        <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('aboutus.faq.update', $faq->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit FAQ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Question</label>
                                <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Answer</label>
                                <textarea name="answer" class="form-control" rows="6" required>{{ $faq->answer }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Display Order</label>
                                <input type="text" name="sort_order" class="form-control no-spinner" inputmode="numeric" pattern="[0-9]*" value="{{ $faq->sort_order }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Preview Modal --}}
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 bg-light">
                    <h5 class="modal-title"><i class="bi bi-display me-2"></i> Live Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="aboutPreviewPanel" class="p-4" style="min-height: 400px; max-height: 70vh; overflow-y: auto;">
                        <p class="text-muted">Start writing to preview your About Us content here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('styles')
    <style>
        /* Layout Selector Cards */
        .layout-card-selector {
            display: block; cursor: pointer;
            border: 2px solid #e5e7eb; border-radius: 16px;
            padding: 20px 16px; text-align: center;
            transition: all 0.3s; height: 100%;
        }
        .layout-card-selector:hover {
            border-color: #93c5fd; background: #f0f7ff;
        }
        .layout-card-selector.active {
            border-color: #2563eb; background: #eff6ff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .layout-card-icon { font-size: 28px; color: #6b7280; display: block; margin-bottom: 8px; }
        .layout-card-selector.active .layout-card-icon { color: #2563eb; }
        .layout-card-inner strong { font-size: 14px; color: #1f2937; }
        .layout-card-inner small { font-size: 12px; line-height: 1.4; }

        .about-editor-shell {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .about-editor-hero {
            background:
                radial-gradient(circle at top right, rgba(13, 122, 62, 0.10), transparent 26%),
                linear-gradient(135deg, #f9fffb 0%, #f5f8ff 100%);
        }

        .about-editor-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            background: #eaf7ef;
            color: #0d7a3e;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .about-editor-title {
            font-size: 2rem;
            font-weight: 800;
            color: #132238;
        }

        .about-editor-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .about-editor-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #e6edf5;
            color: #334a68;
            font-size: 13px;
            font-weight: 600;
        }

        .editor-workspace,
        .editor-panel-card {
            background: #fff;
            border: 1px solid #e8eef5;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(19, 34, 56, 0.06);
        }

        .editor-workspace {
            padding: 22px;
        }

        .editor-toolbar-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .editor-mode-switch,
        .editor-action-switch {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .editor-surface {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e8eef5;
            background: #fff;
        }

        .editor-code-surface {
            border: 0;
            border-radius: 0;
            min-height: 560px;
            font-family: Consolas, "Courier New", monospace;
            font-size: 14px;
            line-height: 1.7;
            color: #17324d;
            background: #fbfdff;
        }

        .editor-footer-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-top: 14px;
            color: #57718f;
            font-size: 14px;
        }

        .editor-footer-note i {
            color: #0d6efd;
            margin-top: 2px;
        }

        .editor-side-panel {
            display: grid;
            gap: 18px;
        }

        .editor-panel-card {
            padding: 22px;
        }

        .editor-panel-card h5 {
            font-weight: 800;
            color: #132238;
            margin-bottom: 10px;
        }

        .about-preview-panel {
            min-height: 260px;
            max-height: 560px;
            overflow: auto;
            padding: 18px;
            border-radius: 18px;
            background: linear-gradient(180deg, #fbfcfe 0%, #f5f9fd 100%);
            border: 1px solid #e6edf5;
            color: #29405b;
            line-height: 1.75;
        }

        .about-preview-panel h1,
        .about-preview-panel h2,
        .about-preview-panel h3,
        .about-preview-panel h4,
        .about-preview-panel h5,
        .about-preview-panel h6 {
            color: #10243e;
            font-weight: 800;
        }

        .editor-checklist {
            margin: 0;
            padding-left: 1.2rem;
            color: #425d7d;
            line-height: 1.85;
        }

        input.no-spinner::-webkit-outer-spin-button,
        input.no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input.no-spinner {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Restore Active Tab
        document.addEventListener("DOMContentLoaded", function() {
            var activeTab = localStorage.getItem('aboutUsActiveTab');
            if (activeTab) {
                var tabEl = document.querySelector('#' + activeTab);
                if (tabEl) {
                    new bootstrap.Tab(tabEl).show();
                }
            }
        });

        // Save Active Tab
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('aboutUsActiveTab', e.target.id);
            });
        });

        // Dynamic Core Values
        document.getElementById('addValueBtn').addEventListener('click', function() {
            const container = document.getElementById('valuesContainer');
            const row = document.createElement('div');
            row.className = 'input-group mb-2 value-row';
            row.innerHTML = `
                <span class="input-group-text"><i class="bi bi-star-fill text-warning"></i></span>
                <input type="text" name="about_values[]" class="form-control" placeholder="e.g. Excellence">
                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.value-row').remove()"><i class="bi bi-trash"></i></button>
            `;
            container.appendChild(row);
        });
    </script>
    <script>
        const initialAboutHtml = @json(old('desc', $aboutus->desc ?? ''));
        const starterTemplate = `<section class="sses-about-block">
  <style>
    .sses-about-block {font-family: var(--font-body, Arial, sans-serif); color: #1f2937; line-height: 1.7;}
    .sses-about-hero {padding: 30px; border-radius: 12px; background: #f3f4f6; margin-bottom: 30px; border-left: 4px solid var(--primary);}
    .sses-about-hero h3 {margin-top: 0; color: #111827;}
    .sses-about-content {font-size: 1.05rem;}
    .sses-about-content ul {margin-top: 15px; padding-left: 20px;}
    .sses-about-content li {margin-bottom: 10px;}
  </style>
  <div class="sses-about-hero">
    <h3>Welcome to Shiksha Sandesh English School</h3>
    <p>Established in 1993 A.D. (2050 B.S.), Shiksha Sandesh English School is a premier educational institution located in Belbari, Morang. We are dedicated to providing value-based, quality education that nurtures the academic, physical, and moral growth of our students.</p>
  </div>
  <div class="sses-about-content">
    <h4>Why Choose Us?</h4>
    <ul>
      <li><strong>Experienced Faculty:</strong> Learn from highly qualified and dedicated teachers.</li>
      <li><strong>Modern Facilities:</strong> Well-equipped science and computer labs, and a resourceful library.</li>
      <li><strong>Holistic Development:</strong> Strong focus on extracurricular activities and sports.</li>
      <li><strong>Affiliation:</strong> Proudly affiliated with the National Examination Board (NEB) Nepal.</li>
    </ul>
    <p>Join us in shaping tomorrow's leaders through excellence in education.</p>
  </div>
</section>`;

        $('#summernote').summernote({
            placeholder: 'Write a strong, informative About Us page for students and parents',
            tabsize: 2,
            height: 620,
            codeviewFilter: false,
            codeviewIframeFilter: false,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['hr']],
                ['misc', ['fullscreen', 'codeview']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']]
            ]
        });

        $('#summernote').summernote('code', initialAboutHtml);

        const summernoteEl = $('#summernote');
        const htmlEditorWrap = document.getElementById('htmlEditorWrap');
        const htmlSourceEditor = document.getElementById('htmlSourceEditor');
        const visualEditorBtn = document.getElementById('visualEditorBtn');
        const htmlEditorBtn = document.getElementById('htmlEditorBtn');
        const loadTemplateBtn = document.getElementById('loadTemplateBtn');
        const previewFromCurrentModeBtn = document.getElementById('previewFromCurrentModeBtn');
        const aboutPreviewPanel = document.getElementById('aboutPreviewPanel');
        const visualEditorWrap = document.getElementById('visualEditorWrap');
        const setEditorMode = (mode) => {
            if (mode === 'html') {
                htmlEditorWrap.classList.remove('d-none');
                visualEditorWrap.classList.add('d-none');
                htmlSourceEditor.value = summernoteEl.summernote('code'); // Auto-sync to HTML
                htmlEditorBtn.classList.remove('btn-outline-dark');
                htmlEditorBtn.classList.add('btn-dark');
                visualEditorBtn.classList.remove('btn-primary');
                visualEditorBtn.classList.add('btn-outline-primary');
            } else {
                htmlEditorWrap.classList.add('d-none');
                visualEditorWrap.classList.remove('d-none');
                summernoteEl.summernote('code', htmlSourceEditor.value); // Auto-sync back to Visual
                visualEditorBtn.classList.remove('btn-outline-primary');
                visualEditorBtn.classList.add('btn-primary');
                htmlEditorBtn.classList.remove('btn-dark');
                htmlEditorBtn.classList.add('btn-outline-dark');
            }
        };

        const renderPreview = (html) => {
            if(aboutPreviewPanel) {
                aboutPreviewPanel.innerHTML = html && html.trim() !== '' ? html : '<p class="text-muted mb-0">Start writing to preview your About Us content here.</p>';
            }
        };

        const getCurrentEditorHtml = () => {
            return htmlEditorWrap.classList.contains('d-none')
                ? summernoteEl.summernote('code')
                : htmlSourceEditor.value;
        };

        visualEditorBtn.addEventListener('click', () => setEditorMode('visual'));
        htmlEditorBtn.addEventListener('click', () => setEditorMode('html'));

        loadTemplateBtn.addEventListener('click', () => {
            if (htmlEditorWrap.classList.contains('d-none')) {
                summernoteEl.summernote('code', starterTemplate);
            } else {
                htmlSourceEditor.value = starterTemplate;
            }
            renderPreview(getCurrentEditorHtml());
        });


        previewFromCurrentModeBtn.addEventListener('click', () => renderPreview(getCurrentEditorHtml()));

        htmlSourceEditor.addEventListener('input', () => {
            if (!htmlEditorWrap.classList.contains('d-none')) {
                renderPreview(htmlSourceEditor.value);
            }
        });

        $('#summernote').on('summernote.change', function(_, contents) {
            if (htmlEditorWrap.classList.contains('d-none')) {
                renderPreview(contents);
            }
        });

        document.getElementById('aboutContentForm').addEventListener('submit', function () {
            if (!htmlEditorWrap.classList.contains('d-none')) {
                summernoteEl.summernote('code', htmlSourceEditor.value);
            }
        });

        // Auto-save layout selection and current form data via AJAX
        document.querySelectorAll('.layout-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const form = document.getElementById('aboutContentForm');
                
                // Sync visual editor to HTML source first so it saves the rich text too
                if (!htmlEditorWrap.classList.contains('d-none')) {
                    summernoteEl.summernote('code', htmlSourceEditor.value);
                } else {
                    htmlSourceEditor.value = summernoteEl.summernote('code');
                }

                const formData = new FormData(form);

                // Update styling instantly
                document.querySelectorAll('.layout-card-selector').forEach(card => {
                    card.classList.remove('active');
                });
                this.closest('.layout-card-selector').classList.add('active');

                // Fire AJAX request with ALL form data
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        console.log('Layout and current data saved automatically!');
                    }
                })
                .catch(error => console.error('Error saving layout:', error));
            });
        });

        setEditorMode('visual');
        renderPreview(initialAboutHtml);
    </script>
@endpush
@endsection
