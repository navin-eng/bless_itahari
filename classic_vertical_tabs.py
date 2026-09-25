import re

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'r') as f:
    content = f.read()

# Replace the outer structure
# Currently it has: <div class="card border-0 shadow-sm mb-4">
# Followed by <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
# And <ul class="nav nav-tabs...

old_header_pattern = r'<div class="card border-0 shadow-sm mb-4">\s*<div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">\s*<ul class="nav nav-tabs border-bottom-0" id="settingsTabs" role="tablist">.*?</ul>\s*</div>\s*<div class="card-body p-4 bg-light border-top">'

new_header_html = '''
<style>
    .settings-layout {
        display: flex;
        gap: 2rem;
    }
    .settings-sidebar {
        flex: 0 0 280px;
    }
    .settings-content {
        flex: 1;
        min-width: 0;
    }
    .nav-pills-custom .nav-link {
        color: #475569;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .nav-pills-custom .nav-link i {
        font-size: 1.25rem;
        color: #94a3b8;
        transition: all 0.2s;
    }
    .nav-pills-custom .nav-link:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
    }
    .nav-pills-custom .nav-link.active {
        background: var(--bs-primary);
        color: #fff;
        border-color: var(--bs-primary);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    .nav-pills-custom .nav-link.active i {
        color: #fff;
    }
    @media (max-width: 991.98px) {
        .settings-layout { flex-direction: column; }
        .settings-sidebar { flex: none; width: 100%; }
        .nav-pills-custom { display: flex; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 10px; gap: 10px; }
        .nav-pills-custom .nav-link { margin-bottom: 0; white-space: nowrap; }
    }
</style>

<div class="settings-layout mb-4">
    <div class="settings-sidebar">
        <div class="nav flex-column nav-pills nav-pills-custom" id="settingsTabs" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab"><i class="bi bi-gear-fill"></i> General Settings</button>
            <button class="nav-link" id="display-tab" data-bs-toggle="pill" data-bs-target="#display" type="button" role="tab"><i class="bi bi-palette-fill"></i> Display & Theme</button>
            <button class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab"><i class="bi bi-envelope-fill"></i> Contact & Social</button>
            <button class="nav-link" id="widgets-tab" data-bs-toggle="pill" data-bs-target="#widgets" type="button" role="tab"><i class="bi bi-grid-fill"></i> Widgets & Buttons</button>
            <button class="nav-link" id="admissions-tab" data-bs-toggle="pill" data-bs-target="#admissions" type="button" role="tab"><i class="bi bi-door-open-fill"></i> Admissions</button>
            <button class="nav-link" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab"><i class="bi bi-graph-up-arrow"></i> Analytics & SEO</button>
            <button class="nav-link" id="logs-tab" data-bs-toggle="pill" data-bs-target="#logs" type="button" role="tab"><i class="bi bi-clock-history"></i> Activity Log</button>
        </div>
    </div>
    
    <div class="settings-content">
'''

content = re.sub(old_header_pattern, new_header_html, content, flags=re.DOTALL)

# Replace the final closing div
end_pattern = r'\s*</div>\s*</div>\s*</form>'
end_replacement = '''
    </div>
</div>
</form>'''
content = re.sub(end_pattern, end_replacement, content)

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'w') as f:
    f.write(content)

