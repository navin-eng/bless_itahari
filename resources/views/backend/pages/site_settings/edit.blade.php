@extends('backend.pages.layout.master')
@push('b-title', 'Site Settings')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Site Settings</h3>
            <p class="text-muted mb-0">Control theme colors, logo text, contact information, and gallery display style.</p>
        </div>
        <div>
            <button type="submit" form="settingsForm" class="btn btn-primary px-4">Save Site Settings</button>
        </div>
    </div>

    <form id="settingsForm" action="{{ route('site.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

                <div class="settings-container">
            {{-- General Section --}}
            <div class="settings-section mb-5">
                <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-gear-fill me-2"></i>General</h4>
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="mb-4"><i class="bi bi-building text-primary me-2"></i>Brand Identity</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Site Name</label>
                                            <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Short Name</label>
                                            <input type="text" name="site_short_name" class="form-control" value="{{ old('site_short_name', $settings->site_short_name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tagline / Logo Subtitle</label>
                                            <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings->site_tagline) }}" required>
                                        </div>

                                        <hr class="my-4 text-muted opacity-25">

                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-3"><i class="bi bi-image text-primary me-2"></i>Site Logo (Primary)</h6>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light p-2 rounded border d-flex align-items-center justify-content-center" style="width: 120px; height: 80px;">
                                                    @if($settings->site_logo)
                                                        <img src="{{ asset($settings->site_logo) }}" alt="Logo" id="logoPreview" class="img-fluid" style="max-height: 60px;">
                                                    @else
                                                        <span class="text-muted small" id="logoPreview"><i class="bi bi-image fs-3"></i></span>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <input type="file" name="site_logo" id="siteLogoInput" class="form-control" accept="image/*">
                                                    <small class="text-muted d-block mt-1">Recommended size: 250x80px (PNG/SVG, Max 2MB)</small>
                                                    @if($settings->site_logo)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogoCheck">
                                                        <label class="form-check-label text-danger" for="removeLogoCheck">Remove current logo</label>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4 text-muted opacity-25">

                                        <div class="mb-0">
                                            <h6 class="fw-bold mb-3"><i class="bi bi-app-indicator text-primary me-2"></i>Site Favicon</h6>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light p-2 rounded border d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    @if($settings->site_favicon)
                                                        <img src="{{ asset($settings->site_favicon) }}" alt="Favicon" id="faviconPreview" class="img-fluid" style="max-height: 32px; width: 32px; object-fit: contain;">
                                                    @else
                                                        <span class="text-muted" id="faviconPreview"><i class="bi bi-image fs-4"></i></span>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <input type="file" name="site_favicon" id="siteFaviconInput" class="form-control" accept=".ico,.png,.jpg,.svg,.webp">
                                                    <small class="text-muted d-block mt-1">Recommended: 32x32px or 64x64px (PNG/ICO)</small>
                                                    @if($settings->site_favicon)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="remove_favicon" value="1" id="removeFaviconCheck">
                                                        <label class="form-check-label text-danger" for="removeFaviconCheck">Remove current favicon</label>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm mb-4 bg-primary bg-opacity-10 border-start border-primary border-4">
                                    <div class="card-body">
                                        <h5 class="mb-3 text-primary"><i class="bi bi-info-circle-fill me-2"></i>About Branding</h5>
                                        <p class="text-muted small mb-3">Your site name and tagline are used across the website, including the header and footer. If you upload a logo, the site name text might be hidden or displayed alongside it depending on the theme.</p>
                                        <div class="alert alert-light border-0 shadow-sm mb-0">
                                            <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb text-warning me-2"></i>Pro Tip:</h6>
                                            <p class="small mb-0 text-muted">Use a transparent PNG for your logo to ensure it looks great on any background color you choose in the Display & Theme tab.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="mb-4"><i class="bi bi-calendar-event text-primary me-2"></i>System Calendar</h5>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold text-primary">System Calendar Format</label>
                                            <select name="calendar_format" class="form-select border-primary bg-primary-subtle">
                                                <option value="ad" {{ old('calendar_format', $settings->calendar_format ?? 'ad') === 'ad' ? 'selected' : '' }}>English (A.D.)</option>
                                                <option value="bs" {{ old('calendar_format', $settings->calendar_format ?? 'ad') === 'bs' ? 'selected' : '' }}>Nepali (B.S.)</option>
                                            </select>
                                            <small class="text-muted d-block mt-1">This will change how dates are displayed across the entire public website.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {-- Display & Theme Section --}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-palette-fill me-2"></i>Display & Theme</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="mb-4"><i class="bi bi-palette text-primary me-2"></i>Theme Colors</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Primary</label>
                                                <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', $settings->primary_color) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Primary Dark</label>
                                                <input type="color" name="primary_dark" class="form-control form-control-color w-100" value="{{ old('primary_dark', $settings->primary_dark) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Primary Light</label>
                                                <input type="color" name="primary_light" class="form-control form-control-color w-100" value="{{ old('primary_light', $settings->primary_light) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Accent</label>
                                                <input type="color" name="accent_color" class="form-control form-control-color w-100" value="{{ old('accent_color', $settings->accent_color) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="mb-4"><i class="bi bi-eye text-primary me-2"></i>Public Display Controls</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Gallery Layout Style</label>
                                            <select name="gallery_layout" id="galleryLayoutSelect" class="form-select">
                                                <option value="masonry" {{ old('gallery_layout', $settings->gallery_layout) === 'masonry' ? 'selected' : '' }}>Masonry Grid</option>
                                                <option value="spotlight" {{ old('gallery_layout', $settings->gallery_layout) === 'spotlight' ? 'selected' : '' }}>Spotlight Cards</option>
                                                <option value="storyboard" {{ old('gallery_layout', $settings->gallery_layout) === 'storyboard' ? 'selected' : '' }}>Storyboard Timeline</option>
                                            </select>
                                            
                                            <div class="mt-3 p-3 bg-light rounded border" style="height: 140px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                                <div id="galleryLayoutPreview" class="layout-preview-{{ old('gallery_layout', $settings->gallery_layout ?? 'masonry') }}" style="width: 100%; max-width: 250px;">
                                                    <div class="item"></div>
                                                    <div class="item"></div>
                                                    <div class="item"></div>
                                                </div>
                                            </div>

                                            <style>
                                                #galleryLayoutPreview { transition: all 0.3s ease; }
                                                
                                                /* Masonry Grid Preview */
                                                .layout-preview-masonry { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; align-items: start; }
                                                .layout-preview-masonry .item { background: #cbd5e1; border-radius: 4px; height: 40px; }
                                                .layout-preview-masonry .item:nth-child(2) { height: 60px; }
                                                .layout-preview-masonry .item:nth-child(3) { height: 50px; }

                                                /* Spotlight Cards Preview */
                                                .layout-preview-spotlight { display: grid; grid-template-columns: 1fr; gap: 8px; }
                                                .layout-preview-spotlight .item { background: #0f172a; border-radius: 8px; height: 35px; position: relative; }
                                                .layout-preview-spotlight .item::after { content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%); width: 30px; height: 3px; background: #3b82f6; border-radius: 2px; }
                                                .layout-preview-spotlight .item:nth-child(3) { display: none; } /* Hide 3rd for space */

                                                /* Storyboard Timeline Preview */
                                                .layout-preview-storyboard { display: flex; flex-direction: column; gap: 8px; }
                                                .layout-preview-storyboard .item { background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; height: 30px; display: flex; overflow: hidden; }
                                                .layout-preview-storyboard .item::before { content: ''; width: 40%; height: 100%; background: #cbd5e1; }
                                                .layout-preview-storyboard .item:nth-child(even) { flex-direction: row-reverse; }
                                            </style>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch" id="showTopbar" name="show_topbar" value="1" {{ old('show_topbar', $settings->show_topbar ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="showTopbar">Show top information bar</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch" id="showWhatsappButton" name="show_whatsapp_button" value="1" {{ old('show_whatsapp_button', $settings->show_whatsapp_button ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="showWhatsappButton">Show WhatsApp floating button</label>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="showBackToTop" name="show_back_to_top" value="1" {{ old('show_back_to_top', $settings->show_back_to_top ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="showBackToTop">Show back-to-top button</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="mb-4"><i class="bi bi-layout-sidebar-inset-reverse text-primary me-2"></i>Navbar Builder Settings</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Navbar Layout</label>
                                            <select name="navbar_layout" class="form-select">
                                                <option value="default" {{ old('navbar_layout', $settings->navbar_layout) === 'default' ? 'selected' : '' }}>Default (Left Aligned)</option>
                                                <option value="centered" {{ old('navbar_layout', $settings->navbar_layout) === 'centered' ? 'selected' : '' }}>Centered</option>
                                                <option value="right" {{ old('navbar_layout', $settings->navbar_layout) === 'right' ? 'selected' : '' }}>Right Aligned</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Navbar Theme</label>
                                            <select name="navbar_theme" class="form-select">
                                                <option value="light" {{ old('navbar_theme', $settings->navbar_theme) === 'light' ? 'selected' : '' }}>Light Theme</option>
                                                <option value="dark" {{ old('navbar_theme', $settings->navbar_theme) === 'dark' ? 'selected' : '' }}>Dark Theme</option>
                                                <option value="primary" {{ old('navbar_theme', $settings->navbar_theme) === 'primary' ? 'selected' : '' }}>Primary Brand Color</option>
                                            </select>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="navbarSticky" name="navbar_sticky" value="1" {{ old('navbar_sticky', $settings->navbar_sticky ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="navbarSticky">Enable Sticky Navbar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {-- Contact & Social Section --}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-envelope-fill me-2"></i>Contact & Social</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="mb-4">Contact Information</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings->contact_phone) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings->contact_email) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="contact_address" class="form-control" value="{{ old('contact_address', $settings->contact_address) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Google Map Iframe Embed</label>
                                            <textarea name="google_map_iframe" class="form-control" rows="3" placeholder='<iframe src="https://www.google.com/maps/embed?..."></iframe>'>{{ old('google_map_iframe', $settings->google_map_iframe) }}</textarea>
                                            <small class="text-muted">Go to Google Maps -> Share -> Embed a map -> Copy HTML</small>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">WhatsApp Number</label>
                                            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="mb-4">Social Media Links</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Facebook URL</label>
                                            <input type="text" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings->facebook_url) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">YouTube URL</label>
                                            <input type="text" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}">
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">Instagram URL</label>
                                            <input type="text" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {-- Widgets & Buttons Section --}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-grid-fill me-2"></i>Widgets & Buttons</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="mb-4">Quick Access Buttons</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Student Portal Text</label>
                                            <input type="text" name="student_portal_text" class="form-control" value="{{ old('student_portal_text', $settings->student_portal_text) }}" placeholder="e.g. Student Portal">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Student Portal Link</label>
                                            <input type="text" name="student_portal_url" class="form-control" value="{{ old('student_portal_url', $settings->student_portal_url) }}" placeholder="https://example.com/login">
                                        </div>
                                        
                                        <h6 class="mb-3">Extra Header Button</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Button Text</label>
                                            <input type="text" name="header_button_text" class="form-control" value="{{ old('header_button_text', $settings->header_button_text) }}" placeholder="e.g. Apply Online">
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">Button Link</label>
                                            <input type="text" name="header_button_url" class="form-control" value="{{ old('header_button_url', $settings->header_button_url) }}" placeholder="https://example.com/apply">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="mb-4">Sticky Notice Widget</h5>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch" id="showStickyNotice" name="show_sticky_notice" value="1" {{ old('show_sticky_notice', $settings->show_sticky_notice) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="showStickyNotice">Show sticky notice on public pages</label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Widget Title</label>
                                            <input type="text" name="sticky_notice_title" class="form-control" value="{{ old('sticky_notice_title', $settings->sticky_notice_title) }}" placeholder="e.g. Latest Notices">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">How Many Notices To Show</label>
                                            <input type="number" min="1" max="10" name="sticky_notice_limit" class="form-control" value="{{ old('sticky_notice_limit', $settings->sticky_notice_limit ?? 5) }}">
                                        </div>

                                        <h6 class="mb-3">Sticky Notice Behavior</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch" id="stickyDesktopCollapsed" name="sticky_notice_desktop_collapsed" value="1" {{ old('sticky_notice_desktop_collapsed', $settings->sticky_notice_desktop_collapsed ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stickyDesktopCollapsed">Start collapsed on desktop</label>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="stickyMobileCollapsed" name="sticky_notice_mobile_collapsed" value="1" {{ old('sticky_notice_mobile_collapsed', $settings->sticky_notice_mobile_collapsed ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stickyMobileCollapsed">Start collapsed on mobile</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {-- Admissions Section --}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-door-open-fill me-2"></i>Admissions</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                        <h5 class="mb-0 fw-bold"><i class="bi bi-sliders text-primary me-2"></i> Admission Settings</h5>
                                        <p class="text-muted small mt-1">Control whether students can apply online.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <div class="form-check form-switch custom-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="admissions_open" name="admissions_open" value="1" {{ old('admissions_open', $settings->admissions_open ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2 fw-semibold" for="admissions_open">Open Admissions</label>
                                            </div>
                                            <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> If enabled, the "Apply Now" button will show the application form. If disabled, it will show the closed message below.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                        <h5 class="mb-0 fw-bold"><i class="bi bi-card-text text-primary me-2"></i> Closed Notice Content</h5>
                                        <p class="text-muted small mt-1">What to show when admissions are turned off.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <label class="form-label fw-medium">Notice Title</label>
                                            <input type="text" class="form-control" name="admission_title" value="{{ old('admission_title', $settings->admission_title ?? 'Admissions are Closed') }}">
                                            <div class="form-text">e.g., "Admissions for 2026 are Closed"</div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-medium">Notice Description</label>
                                            <textarea class="form-control" name="admission_description" rows="4">{{ old('admission_description', $settings->admission_description ?? 'We are not currently accepting new applications. Please check back later for updates.') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {-- Analytics Section --}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-graph-up-arrow me-2"></i>Analytics</h4>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                        <h5 class="mb-0 fw-bold"><i class="bi bi-google text-primary me-2"></i> Google Analytics 4</h5>
                                        <p class="text-muted small mt-1">Configure GA4 tracking and dashboard data.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <div class="form-check form-switch custom-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="enable_analytics" name="enable_analytics" value="1" {{ old('enable_analytics', $settings->enable_analytics ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2 fw-semibold" for="enable_analytics">Enable Analytics</label>
                                            </div>
                                            <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> If disabled, tracking scripts will be removed and the dashboard widget will be hidden.</div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-medium">Measurement ID</label>
                                            <input type="text" class="form-control" name="google_analytics_id" value="{{ old('google_analytics_id', $settings->google_analytics_id) }}" placeholder="G-XXXXXXXXXX">
                                            <div class="form-text">Used to inject the tracking script into the website.</div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-medium">Property ID (For Dashboard)</label>
                                            <input type="text" class="form-control" name="analytics_property_id" value="{{ old('analytics_property_id', $settings->analytics_property_id) }}" placeholder="123456789">
                                            <div class="form-text">Used to pull data into the Admin Dashboard. See Walkthrough for setup details.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                        <h5 class="mb-0 fw-bold"><i class="bi bi-microsoft text-primary me-2"></i> Microsoft Clarity</h5>
                                        <p class="text-muted small mt-1">Configure heatmaps and session recordings.</p>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <label class="form-label fw-medium">Clarity Project ID</label>
                                            <input type="text" class="form-control" name="microsoft_clarity_id" value="{{ old('microsoft_clarity_id', $settings->microsoft_clarity_id) }}" placeholder="e.g. 5xkzj3u7w2">
                                            <div class="form-text">Used to inject the Clarity tracking script.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {-- Activity Log Section --}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-clock-history me-2"></i>Activity Log</h4>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-4">Recent Changes</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>When</th>
                                                <th>User</th>
                                                <th>Action</th>
                                                <th>Summary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($logs as $log)
                                                <tr>
                                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>{{ $log->user_name }}</td>
                                                    <td><span class="badge bg-primary">{{ ucfirst($log->action) }}</span></td>
                                                    <td>{{ $log->summary }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">No settings activity logged yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoInput = document.getElementById('siteLogoInput');
        const logoPreview = document.getElementById('logoPreview');
        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        logoPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        const favInput = document.getElementById('siteFaviconInput');
        const favPreview = document.getElementById('faviconPreview');
        if (favInput && favPreview) {
            favInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        favPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        const layoutSelect = document.getElementById('galleryLayoutSelect');
        const layoutPreview = document.getElementById('galleryLayoutPreview');
        if (layoutSelect && layoutPreview) {
            layoutSelect.addEventListener('change', function () {
                layoutPreview.className = 'layout-preview-' + this.value;
            });
        }
    });
</script>
@endpush
