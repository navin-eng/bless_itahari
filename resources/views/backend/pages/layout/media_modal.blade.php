{{-- WordPress-Style Media Library Modal --}}
<div class="modal fade" id="wpMediaModal" tabindex="-1" aria-labelledby="wpMediaModalLabel" aria-hidden="true" style="z-index: 1065;">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 90vw;">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="height: 85vh;">
      
      {{-- Modal Header & Tabs --}}
      <div class="modal-header bg-dark text-white px-4 py-3 d-flex flex-wrap align-items-center justify-content-between border-0">
        <div class="d-flex align-items-center gap-3">
          <h5 class="modal-title fw-bold fs-6 mb-0 text-white" id="wpMediaModalLabel">
            <i class="bi bi-images text-warning me-2"></i> Media Library
          </h5>
          <ul class="nav nav-pills nav-sm bg-secondary bg-opacity-20 p-1 rounded-3" id="mediaTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link text-white small px-3 py-1 fw-semibold active" id="tab-library-tab" data-bs-toggle="pill" data-bs-target="#tab-library" type="button" role="tab">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Media Library
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link text-white small px-3 py-1 fw-semibold" id="tab-upload-tab" data-bs-toggle="pill" data-bs-target="#tab-upload" type="button" role="tab">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Files
              </button>
            </li>
          </ul>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      {{-- Modal Body --}}
      <div class="modal-body p-0 bg-light position-relative">
        <div class="tab-content h-100" id="mediaTabsContent">
          
          {{-- TAB 1: Media Library Grid & Details Sidebar --}}
          <div class="tab-pane fade show active h-100" id="tab-library" role="tabpanel">
            <div class="d-flex h-100">
              
              {{-- Left: Grid & Filter --}}
              <div class="flex-grow-1 d-flex flex-column h-100 border-end">
                <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between gap-3">
                  <div class="position-relative flex-grow-1" style="max-width: 320px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="wpMediaSearch" class="form-control form-control-sm ps-5 rounded-pill" placeholder="Search media by filename...">
                  </div>
                  <button type="button" id="wpMediaRefresh" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                  </button>
                </div>

                {{-- Scrollable Images Grid --}}
                <div class="flex-grow-1 p-3 overflow-y-auto" id="wpMediaGridWrapper" style="background: #f8fafc;">
                  <div id="wpMediaLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading media...</span>
                    </div>
                    <p class="text-muted small mt-2">Scanning media library...</p>
                  </div>
                  <div class="row g-2" id="wpMediaGrid" style="display: none;"></div>
                </div>
              </div>

              {{-- Right: Selected Image Details Panel --}}
              <div class="bg-white p-4 h-100 d-flex flex-column" style="width: 320px; flex-shrink: 0;" id="wpMediaSidebar">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Attachment Details</h6>
                
                <div id="wpMediaNoSelection" class="text-center text-muted my-auto py-5">
                  <i class="bi bi-image fs-1 opacity-25"></i>
                  <p class="small mt-2">Click an image from the library to view details and select.</p>
                </div>

                <div id="wpMediaSelectionDetails" style="display: none;" class="flex-grow-1 d-flex flex-column">
                  <div class="text-center bg-light p-2 rounded border mb-3">
                    <img id="wpPreviewImg" src="" alt="Preview" style="max-height: 160px; max-width: 100%; object-fit: contain; border-radius: 6px;">
                  </div>

                  <div class="small text-muted space-y-1 mb-3">
                    <div><strong class="text-dark">File:</strong> <span id="wpPreviewFilename" class="text-break"></span></div>
                    <div><strong class="text-dark">Size:</strong> <span id="wpPreviewSize"></span></div>
                    <div><strong class="text-dark">Uploaded:</strong> <span id="wpPreviewDate"></span></div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">File Path / URL</label>
                    <input type="text" id="wpPreviewUrl" class="form-control form-control-sm text-muted bg-light" readonly>
                  </div>

                  <div class="mt-auto pt-3 border-top">
                    <button type="button" id="wpSelectMediaBtn" class="btn btn-primary w-100 rounded-3 fw-bold">
                      <i class="bi bi-check2-circle me-1"></i> Use Selected Image
                    </button>
                  </div>
                </div>

              </div>

            </div>
          </div>

          {{-- TAB 2: Drag and Drop Upload --}}
          <div class="tab-pane fade h-100" id="tab-upload" role="tabpanel">
            <div class="d-flex align-items-center justify-content-center h-100 p-5">
              <div class="text-center p-5 border-2 border-dashed rounded-4 bg-white shadow-sm w-100" style="max-width: 600px; border-color: #cbd5e1 !important;" id="wpDropzone">
                <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>
                <h5 class="fw-bold text-dark mb-1">Drop files to upload</h5>
                <p class="text-muted small mb-4">or click the button below to browse files from your device</p>
                
                <input type="file" id="wpFileInput" class="d-none" accept="image/*">
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" onclick="document.getElementById('wpFileInput').click()">
                  <i class="bi bi-folder2-open me-2"></i> Select Files
                </button>
                <p class="text-muted fs-8 mt-3 mb-0">Maximum upload file size: 10 MB. Supported: JPG, PNG, WEBP, GIF, SVG</p>

                <div id="wpUploadProgress" class="progress mt-4 d-none" style="height: 8px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%"></div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
(function() {
    let mediaLibraryCache = [];
    let selectedImage = null;
    let currentTargetInput = null;
    let currentTargetPreview = null;
    let currentCallback = null;

    window.openMediaLibrary = function(options = {}) {
        currentTargetInput = options.targetInput ? document.querySelector(options.targetInput) : null;
        currentTargetPreview = options.targetPreview ? document.querySelector(options.targetPreview) : null;
        currentCallback = options.onSelect || null;

        const modalEl = document.getElementById('wpMediaModal');
        if (!modalEl) return;

        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        loadMediaLibrary();
    };

    function loadMediaLibrary(query = '') {
        const grid = document.getElementById('wpMediaGrid');
        const loading = document.getElementById('wpMediaLoading');
        if (!grid || !loading) return;

        grid.style.display = 'none';
        loading.style.display = 'block';

        fetch('{{ route("admin.media.index") }}?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                grid.style.display = 'flex';
                grid.innerHTML = '';

                if (!data.success || !data.images || data.images.length === 0) {
                    grid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><i class="bi bi-images fs-1 opacity-25"></i><p class="mt-2">No images found in library.</p></div>';
                    return;
                }

                mediaLibraryCache = data.images;
                data.images.forEach(img => {
                    const col = document.createElement('div');
                    col.className = 'col-4 col-sm-3 col-md-2 position-relative';
                    col.innerHTML = `
                        <div class="wp-media-thumb border rounded-3 overflow-hidden bg-white cursor-pointer position-relative ${selectedImage && selectedImage.url === img.url ? 'selected' : ''}" style="aspect-ratio: 1; transition: all 0.2s;">
                            <img src="${img.url}" alt="${img.filename}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="wp-check-badge"><i class="bi bi-check-lg"></i></div>
                        </div>
                    `;
                    col.querySelector('.wp-media-thumb').addEventListener('click', function() {
                        document.querySelectorAll('.wp-media-thumb').forEach(el => el.classList.remove('selected'));
                        this.classList.add('selected');
                        selectImageItem(img);
                    });
                    grid.appendChild(col);
                });
            })
            .catch(err => {
                loading.style.display = 'none';
                grid.style.display = 'flex';
                grid.innerHTML = '<div class="col-12 text-center py-4 text-danger"><i class="bi bi-exclamation-triangle fs-3"></i><p class="small mt-1">Failed to load media library.</p></div>';
            });
    }

    function selectImageItem(img) {
        selectedImage = img;
        const noSel = document.getElementById('wpMediaNoSelection');
        const selDet = document.getElementById('wpMediaSelectionDetails');
        
        if (noSel && selDet) {
            noSel.style.display = 'none';
            selDet.style.display = 'flex';

            document.getElementById('wpPreviewImg').src = img.url;
            document.getElementById('wpPreviewFilename').textContent = img.filename;
            document.getElementById('wpPreviewSize').textContent = img.size;
            document.getElementById('wpPreviewDate').textContent = img.date;
            document.getElementById('wpPreviewUrl').value = img.relative_path;
        }
    }

    // Select Button Click Handler
    document.addEventListener('DOMContentLoaded', function() {
        const selectBtn = document.getElementById('wpSelectMediaBtn');
        if (selectBtn) {
            selectBtn.addEventListener('click', function() {
                if (!selectedImage) return;

                if (currentTargetInput) {
                    currentTargetInput.value = selectedImage.relative_path;
                    currentTargetInput.dispatchEvent(new Event('change'));
                }
                if (currentTargetPreview) {
                    currentTargetPreview.src = selectedImage.url;
                    currentTargetPreview.style.display = 'block';
                    // Also reveal the _wrap container if present (used by image_picker partial)
                    const wrapId = currentTargetPreview.id + '_wrap';
                    const wrap = document.getElementById(wrapId);
                    if (wrap) wrap.style.display = 'block';
                }
                if (currentCallback && typeof currentCallback === 'function') {
                    currentCallback(selectedImage);
                }

                const modalEl = document.getElementById('wpMediaModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
        }

        // Search Input Filter
        const searchInput = document.getElementById('wpMediaSearch');
        if (searchInput) {
            let timeout = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => loadMediaLibrary(this.value.trim()), 300);
            });
        }

        // Refresh Button
        const refreshBtn = document.getElementById('wpMediaRefresh');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => loadMediaLibrary());
        }

        // File Upload Handler
        const fileInput = document.getElementById('wpFileInput');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (!this.files || !this.files[0]) return;
                const file = this.files[0];

                const formData = new FormData();
                formData.append('file', file);
                formData.append('_token', '{{ csrf_token() }}');

                const progress = document.getElementById('wpUploadProgress');
                const progressBar = progress ? progress.querySelector('.progress-bar') : null;
                if (progress) progress.classList.remove('d-none');

                fetch('{{ route("admin.media.upload") }}', {
                    method: 'POST',
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (progress) progress.classList.add('d-none');
                    if (data.success && data.image) {
                        selectImageItem(data.image);
                        // Switch to library tab
                        const libTab = document.getElementById('tab-library-tab');
                        if (libTab) libTab.click();
                        loadMediaLibrary();
                    } else {
                        alert(data.message || 'Upload failed');
                    }
                })
                .catch(err => {
                    if (progress) progress.classList.add('d-none');
                    alert('Failed to upload image.');
                });
            });
        }

        // Global delegate handler for any button with data-media-picker
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-media-picker]');
            if (btn) {
                e.preventDefault();
                const targetInput = btn.getAttribute('data-target-input');
                const targetPreview = btn.getAttribute('data-target-preview');
                window.openMediaLibrary({
                    targetInput: targetInput,
                    targetPreview: targetPreview
                });
            }
        });
    });
})();
</script>

<style>
  .wp-media-thumb {
    border: 2px solid transparent !important;
  }
  .wp-media-thumb:hover {
    border-color: #3b82f6 !important;
  }
  .wp-media-thumb.selected {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3) !important;
  }
  .wp-check-badge {
    position: absolute;
    top: 6px; right: 6px;
    width: 22px; height: 22px;
    background: #2563eb; color: #fff;
    border-radius: 50%;
    display: none;
    align-items: center; justify-content: center;
    font-size: 12px;
  }
  .wp-media-thumb.selected .wp-check-badge {
    display: flex;
  }
</style>
