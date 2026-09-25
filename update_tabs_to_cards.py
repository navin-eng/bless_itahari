import re

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'r') as f:
    content = f.read()

# The original nav-tabs section
old_tabs_pattern = r'<div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">\s*<ul class="nav nav-tabs border-bottom-0" id="settingsTabs" role="tablist">.*?</ul>\s*</div>'

new_tabs_html = '''
<style>
    .settings-card-nav .nav-link {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: left;
        padding: 1.5rem;
        height: 100%;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .settings-card-nav .nav-link:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .settings-card-nav .nav-link.active {
        background: var(--bs-primary);
        color: #fff !important;
        border-color: var(--bs-primary);
        box-shadow: 0 12px 20px rgba(13, 110, 253, 0.15);
    }
    .settings-card-nav .nav-link .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.1);
        color: var(--bs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    .settings-card-nav .nav-link.active .icon-box {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }
    .settings-card-nav .nav-link h5 {
        margin-bottom: 0.25rem;
        font-weight: 700;
        color: #1e293b;
        transition: color 0.3s;
    }
    .settings-card-nav .nav-link.active h5 {
        color: #fff;
    }
    .settings-card-nav .nav-link p {
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #64748b;
        transition: color 0.3s;
    }
    .settings-card-nav .nav-link.active p {
        color: rgba(255,255,255,0.8);
    }
</style>

<div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4">
    <ul class="nav nav-pills row g-3 settings-card-nav mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link active w-100" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-gear-fill"></i></div>
                <div>
                    <h5>General</h5>
                    <p>Brand identity & logos</p>
                </div>
            </button>
        </li>
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link w-100" id="display-tab" data-bs-toggle="tab" data-bs-target="#display" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-palette-fill"></i></div>
                <div>
                    <h5>Display</h5>
                    <p>Colors & layouts</p>
                </div>
            </button>
        </li>
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <h5>Contact</h5>
                    <p>Phone, email & socials</p>
                </div>
            </button>
        </li>
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link w-100" id="widgets-tab" data-bs-toggle="tab" data-bs-target="#widgets" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-grid-fill"></i></div>
                <div>
                    <h5>Widgets</h5>
                    <p>Notices & portals</p>
                </div>
            </button>
        </li>
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link w-100" id="admissions-tab" data-bs-toggle="tab" data-bs-target="#admissions" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-door-open-fill"></i></div>
                <div>
                    <h5>Admissions</h5>
                    <p>Open/close forms</p>
                </div>
            </button>
        </li>
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link w-100" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <h5>Analytics</h5>
                    <p>Google & Clarity</p>
                </div>
            </button>
        </li>
        <li class="nav-item col-xl-3 col-lg-4 col-md-6" role="presentation">
            <button class="nav-link w-100" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">
                <div class="icon-box"><i class="bi bi-clock-history"></i></div>
                <div>
                    <h5>Activity Log</h5>
                    <p>Recent changes</p>
                </div>
            </button>
        </li>
    </ul>
    
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-0 mt-3 rounded-3" style="background-color: #f0f7ff; color: #0056b3;">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>Select a settings category above to configure its options. Don't forget to save changes when you're done!</div>
    </div>
</div>
'''

content = re.sub(old_tabs_pattern, new_tabs_html, content, flags=re.DOTALL)

# Make the outer card transparent or remove its background
content = content.replace('<div class="card border-0 shadow-sm mb-4">', '<div class="settings-wrapper mb-4">')
content = content.replace('<div class="card-body p-4 bg-light border-top">', '<div class="card-body p-0 mt-4">')

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'w') as f:
    f.write(content)

