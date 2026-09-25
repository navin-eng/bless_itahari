import re

with open('resources/views/backend/pages/site_settings/edit.blade.php', 'r') as f:
    content = f.read()

# We need to completely rewrite the layout. It's better to just write the blade file out.
# But since the forms/inputs are already in the content, maybe I can just git checkout the old version and then modify it?
