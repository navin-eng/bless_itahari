{{--
Reusable Image Picker Component (Media Library only)
Props:
$name - form field name (text input with this name carries the path: e.g. 'image_url')
$inputId - unique ID for the text input (e.g. 'noticeImageUrl')
$previewId - unique ID for preview img tag (e.g. 'noticeImagePreview')
$label - label text (default 'Image')
$hint - hint text below the picker
$currentImage - current image path for edit forms (can be null)
$required - whether a value is required (default false)
--}}
@php
  $label = $label ?? 'Image';
  $hint = $hint ?? 'Click "Choose Image" to pick from the Media Library or upload a new one.';
  $currentImage = $currentImage ?? null;
  $required = $required ?? false;
  $currentVal = old(($name ?? 'image') . '_url', $currentImage ?? '');
@endphp

<div class="admin-form-group">
  <label class="admin-label">{{ $label }}{{ $required ? ' *' : '' }}</label>

  {{-- Picker row: path preview + button --}}
  <div class="input-group mb-2">
    <input type="text" name="{{ $name }}_url" id="{{ $inputId }}" class="admin-input"
      placeholder="No image selected — click Choose Image" value="{{ $currentVal }}" readonly
      style="cursor:pointer; flex:1; border-radius:8px 0 0 8px;"
      onclick="window.openMediaLibrary({targetInput:'#{{ $inputId }}', targetPreview:'#{{ $previewId }}'})">
    <button type="button" class="btn btn-outline-primary" data-media-picker data-target-input="#{{ $inputId }}"
      data-target-preview="#{{ $previewId }}"
      style="border-radius:0 8px 8px 0; white-space:nowrap; font-size:13px; font-weight:600;">
      <i class="bi bi-images me-1"></i> Choose Image
    </button>
  </div>

  {{-- Image preview --}}
  <div id="{{ $previewId }}_wrap" style="{{ $currentVal ? '' : 'display:none;' }} margin-top:6px;">
    <img id="{{ $previewId }}" src="{{ $currentVal ? asset($currentVal) : '' }}" alt="Selected image"
      style="max-height:100px; max-width:220px; border-radius:8px; border:1px solid #e2e8f0; object-fit:cover; display:block;">
    <button type="button" onclick="clearImagePicker('{{ $inputId }}', '{{ $previewId }}')"
      style="margin-top:4px; background:none; border:none; color:#e53e3e; font-size:12px; cursor:pointer; padding:0;">
      <i class="bi bi-x-circle me-1"></i> Remove
    </button>
  </div>

  @if($hint)
    <span class="admin-input-hint" style="display:block; margin-top:4px;">{{ $hint }}</span>
  @endif
</div>

@once
  <script>
    function clearImagePicker(inputId, previewId) {
      const inp = document.getElementById(inputId);
      const img = document.getElementById(previewId);
      const wrap = document.getElementById(previewId + '_wrap');
      if (inp) inp.value = '';
      if (img) img.src = '';
      if (wrap) wrap.style.display = 'none';
    }
  </script>

  {{-- Patch openMediaLibrary so it also reveals the preview wrap on selection --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Intercept the global data-media-picker click to also handle wrap visibility
      const origOpen = window.openMediaLibrary;
      // We hook into the "Use Selected Image" button via a MutationObserver-free approach:
      // After the modal closes and the targetInput value changes, show the preview wrap.
      document.getElementById('wpMediaModal')?.addEventListener('hide.bs.modal', function () {
        // For every image picker on page, check if its input now has a value and show wrap
        document.querySelectorAll('[data-media-picker]').forEach(function (btn) {
          const targetInputSel = btn.getAttribute('data-target-input');
          const targetPreviewSel = btn.getAttribute('data-target-preview');
          if (!targetInputSel || !targetPreviewSel) return;
          const inp = document.querySelector(targetInputSel);
          const previewEl = document.querySelector(targetPreviewSel);
          if (inp && previewEl && inp.value) {
            const wrap = document.getElementById(previewEl.id + '_wrap');
            if (wrap) wrap.style.display = 'block';
          }
        });
      });
    });
  </script>
@endonce