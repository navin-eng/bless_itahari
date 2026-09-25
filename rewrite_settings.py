import re

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'r') as f:
    content = f.read()

# Replace the start (tabs wrapper + first tab)
start_pattern = r'<div class="card border-0 shadow-sm mb-4">.*?\{\{-- General Tab --\}\}\s*<div class="tab-pane fade show active" id="general" role="tabpanel">'
start_replacement = '''        <div class="settings-container">
            {{-- General Section --}}
            <div class="settings-section mb-5">
                <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi bi-gear-fill me-2"></i>General</h4>'''
content = re.sub(start_pattern, start_replacement, content, flags=re.DOTALL)

# Replace other tabs
def replace_tab(match):
    tab_name = match.group(1)
    tab_id = match.group(2)
    
    icons = {
        'Display & Theme': 'bi-palette-fill',
        'Contact & Social': 'bi-envelope-fill',
        'Widgets & Buttons': 'bi-grid-fill',
        'Admissions': 'bi-door-open-fill',
        'Analytics': 'bi-graph-up-arrow',
        'Activity Log': 'bi-clock-history'
    }
    
    icon = icons.get(tab_name, 'bi-gear')
    
    return f'''                    </div>

                    {{-- {tab_name} Section --}}
                    <div class="settings-section mb-5">
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="bi {icon} me-2"></i>{tab_name}</h4>'''

tab_pattern = r'                    </div>\s*\{\{-- (.*?) Tab --\}\}\s*<div class="tab-pane fade" id="(.*?)" role="tabpanel">'
content = re.sub(tab_pattern, replace_tab, content)

# Replace the end
end_pattern = r'                    </div>\s*</div>\s*</div>\s*</div>\s*</form>'
end_replacement = '''                    </div>
        </div>
    </form>'''
content = re.sub(end_pattern, end_replacement, content)

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'w') as f:
    f.write(content)
