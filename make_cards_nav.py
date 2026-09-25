import re

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'r') as f:
    content = f.read()

# I need to add a "Back" button and hide the tab content by default.

# 1. Modify the nav-pills container to have an ID so we can toggle it
if '<div class="settings-wrapper mb-4">' in content:
    content = content.replace('<div class="settings-wrapper mb-4">', '<div class="settings-wrapper mb-4" id="settingsGridContainer">')
else:
    # fallback
    content = content.replace('<div class="card-header bg-transparent', '<div id="settingsGridContainer" class="card-header bg-transparent')

# 2. Add the "Back" button container right above the tab-content, hidden by default
back_button_html = '''
            <div id="settingsContentHeader" class="d-none align-items-center mb-4">
                <button type="button" class="btn btn-light shadow-sm me-3" id="backToGridBtn">
                    <i class="bi bi-arrow-left"></i> Back to Menu
                </button>
                <h4 class="mb-0 text-primary" id="currentSettingTitle">Settings</h4>
            </div>
            
            <div class="card-body p-0 mt-4 d-none" id="settingsFormContainer">
'''

content = content.replace('<div class="card-body p-0 mt-4">', back_button_html)

# 3. Add JavaScript to handle the interaction
js_script = '''
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridContainer = document.getElementById('settingsGridContainer');
        const formContainer = document.getElementById('settingsFormContainer');
        const contentHeader = document.getElementById('settingsContentHeader');
        const backBtn = document.getElementById('backToGridBtn');
        const titleEl = document.getElementById('currentSettingTitle');
        
        // Remove the 'show active' from the first tab so nothing shows if it glitches
        // Actually, Bootstrap handles the active state, but we control visibility of the container.
        
        document.querySelectorAll('.settings-card-nav .nav-link').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Get the title from the clicked card
                const title = this.querySelector('h5').innerText;
                titleEl.innerText = title + ' Settings';
                
                // Hide grid, show form
                gridContainer.classList.add('d-none');
                contentHeader.classList.remove('d-none');
                contentHeader.classList.add('d-flex');
                formContainer.classList.remove('d-none');
            });
        });
        
        backBtn.addEventListener('click', function() {
            // Hide form, show grid
            formContainer.classList.add('d-none');
            contentHeader.classList.add('d-none');
            contentHeader.classList.remove('d-flex');
            gridContainer.classList.remove('d-none');
            
            // Optional: reset tabs (remove active)
            document.querySelectorAll('.settings-card-nav .nav-link.active').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-pane.show.active').forEach(el => {
                el.classList.remove('show');
                el.classList.remove('active');
            });
        });
    });
</script>
'''

# Insert the script before @endpush or at the end
if '@endpush' in content:
    content = content.replace('@endpush', js_script + '\n@endpush')
else:
    content += js_script

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'w') as f:
    f.write(content)

