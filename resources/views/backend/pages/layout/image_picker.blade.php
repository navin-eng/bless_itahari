{{--
  Reusable Image Picker Component
  Props:
    $name       - input name attribute (e.g. 'image')
    $inputId    - unique ID for hidden URL input (e.g. 'noticeImageUrl')
    $previewId  - unique ID for preview img tag   (e.g. 'noticeImagePreview')
    $label      - label text (default 'Image')
    $hint       - hint text (default '')
    $currentImage - current image path (for edit forms, can be null)
    $required   - whether file is required (default false)
--}}
@php
  $label        = $label        ?? 'Image';
  $hint         = $hint         ?? 'Choose from Media Library or upload a new file.';
  $currentImage = $currentImage ?? null;
  $required     = $required     ?? false;
@endphp

<div class="admin-form-group">
  <label class="admin-label">{{ $label }}{{ $required ? ' *' : '' }}</label>

  {{-- Media Library URL hidden input + picker button --}}
  <div class="d-flex gap-2 mb-2">
    <input type="text"
           name="{{ $name }}_url"
           id="{{ $inputId }}"
           class="admin-input"
           placeholder="Select from Media Library or upload below…"
           value="{{ old($name.'_url', $currentImage ?? '') }}"
           readonly
           style="flex:1; cursor:pointer;"
           onclick="document.getElementById('{{ $inputId }}PickBtn').click()">
    <button type="button"
            id="{{ $inputId }}PickBtn"
            class="btn-admin btn-admin-outline"
            data-media-picker
            data-target-input="#{{ $inputId }}"
            data-target-preview="#{{ $previewId }}"
            style="white-space:nowrap;">
      <i class="bi bi-images me-1"></i> Media Library
    </button>
  </div>

  {{-- Current image preview (edit mode) --}}
  @if($currentImage)
  <div class="mb-2">
    <small class="text-muted d-block mb-1">Current image:</small>
    <img id="{{ $previewId }}"
         src="{{ asset(old($name.'_url', $currentImage)) }}"
         alt="Current"
         style="max-height:80px; border-radius:6px; border:1px solid var(--admin-border); object-fit:cover;">
  </div>
  @else
  <img id="{{ $previewId }}"
       src=""
       alt="Preview"
       style="max-height:80px; border-radius:6px; border:1px solid var(--admin-border); display:none; margin-bottom:8px; object-fit:cover;">
  @endif

  {{-- Or upload new file --}}
  <label class="admin-label text-muted" style="font-size:11.5px; margin-top:4px;">— or upload a new file —</label>
  <input type="file"
         name="{{ $name }}"
         class="admin-input"
         accept="image/*"
         {{ $required && !$currentImage ? 'required' : '' }}>

  @if($hint)
  <span class="admin-input-hint">{{ $hint }}</span>
  @endif
</div>
