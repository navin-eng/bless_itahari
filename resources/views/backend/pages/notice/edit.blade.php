@extends('backend.pages.layout.master')
@push('b-title', 'Edit Notice')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 text-dark fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Notice</h3>
        <p class="text-muted mb-0">Update notice details, manage PDF attachments, or adjust the expiration date.</p>
    </div>
    <div>
        <a href="{{ route('notice.table') }}" class="btn btn-outline-secondary px-4 rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Notices
        </a>
    </div>
</div>

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('notice.update', $notice->id) }}" enctype="multipart/form-data" method="POST" id="noticeEditForm">
    @csrf

    <div class="row g-4">
        {{-- Left Column: Main Notice Content --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Notice Details</h5>
                    <span class="badge {{ $notice->isExpired() ? 'bg-danger' : 'bg-success' }} px-2.5 py-1.5">
                        {{ $notice->isExpired() ? 'Expired Notice' : 'Active Notice' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Notice Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $notice->title) }}" class="form-control form-control-lg" placeholder="Notice Title" required>
                    </div>

                    {{-- Category / Department --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-bold text-dark mb-0">Category / Department</label>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-primary" id="toggleNewCatBtn" onclick="toggleCustomCategory()">
                                <i class="bi bi-plus-circle me-1"></i> + Add New Category
                            </button>
                        </div>
                        <select name="category" id="noticeCategorySelect" class="form-select">
                            <option value="">-- Select Category / Department --</option>
                            @if(isset($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ old('category', $notice->category) == $cat->name ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            @endif
                            @if(!empty($notice->category) && (!isset($categories) || !$categories->contains('name', $notice->category)))
                                <option value="{{ $notice->category }}" selected>{{ $notice->category }}</option>
                            @endif
                        </select>
                        
                        <div id="newCategoryWrap" class="mt-2 d-none">
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><i class="bi bi-tag-fill"></i></span>
                                <input type="text" name="new_category" id="newCategoryInput" class="form-control" placeholder="Type new department or category name...">
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleCustomCategory(false)">Cancel</button>
                            </div>
                            <small class="text-muted">This new category will automatically be saved and available for future notices.</small>
                        </div>
                    </div>

                    {{-- Description with Summernote --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Notice Content / Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote-notice" name="description" id="noticeDescription" rows="8">{{ old('description', $notice->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Document / PDF Upload Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Document / PDF Attachment
                    </h5>
                    @if($notice->hasFile())
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Has Attachment</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($notice->hasFile())
                        <div class="p-3 mb-3 bg-light rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(220, 53, 69, 0.1);">
                                    @if($notice->isPdf())
                                        <i class="fa-solid fa-file-pdf fs-4 text-danger"></i>
                                    @else
                                        <i class="fa-solid fa-file-lines fs-4 text-primary"></i>
                                    @endif
                                </div>
                                <div>
                                    <strong class="d-block text-dark">{{ $notice->file_name ?? basename($notice->file) }}</strong>
                                    <span class="text-muted small">
                                        {{ strtoupper($notice->getFileExtension()) }}
                                        @if($notice->file_size) &bull; {{ $notice->file_size }} @endif
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ asset($notice->file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Preview
                                </a>
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" name="remove_file" value="1" id="removeFileCheck">
                                    <label class="form-check-label text-danger small fw-bold" for="removeFileCheck">Remove file</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light position-relative file-drop-zone" id="fileDropZone">
                        <i class="fa-solid fa-cloud-arrow-up text-primary fs-1 mb-2"></i>
                        <h6 class="fw-bold mb-1">{{ $notice->hasFile() ? 'Replace Attached File' : 'Choose a PDF or Document File' }}</h6>
                        <p class="text-muted small mb-2">Supported formats: <strong>.PDF, .DOC, .DOCX, .XLSX, .ZIP</strong> (Max: 25MB)</p>
                        
                        <input type="file" name="file" id="noticeFileInput" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip" style="max-width: 320px; margin: 0 auto;">
                        
                        <div id="fileSelectedInfo" class="mt-3 p-2 bg-white rounded border d-none">
                            <span class="badge bg-success me-2"><i class="fa-solid fa-check"></i> New File Selected</span>
                            <strong id="selectedFileName" class="text-dark"></strong>
                            <span id="selectedFileSize" class="text-muted small ms-2"></span>
                            <button type="button" class="btn btn-sm text-danger ms-2" onclick="clearNoticeFile()"><i class="bi bi-x-circle"></i> Clear</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured Image Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-image text-primary me-2"></i>Featured Banner Image (Optional)</h5>
                </div>
                <div class="card-body p-4">
                    @include('backend.pages.layout.image_picker', [
                        'name'         => 'image',
                        'inputId'      => 'noticeImageUrl',
                        'previewId'    => 'noticeImagePreview',
                        'label'        => 'Notice Banner Image',
                        'currentImage' => $notice->image,
                        'hint'         => 'Select from Media Library or leave blank to keep current image.',
                    ])
                    @if($notice->image)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImageCheck">
                            <label class="form-check-label text-danger small fw-bold" for="removeImageCheck">Remove current image</label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Expiry & Publishing Controls --}}
        <div class="col-lg-4">
            {{-- Expiry System Card --}}
            <div class="card border-0 shadow-sm mb-4 border-start border-4 border-warning">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-hourglass-split text-warning me-2"></i>Notice Expiry System</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Expiry Date</label>
                        @php
                            $expiryVal = old('expires_at', $notice->expires_at ? $notice->expires_at->format('Y-m-d') : '');
                        @endphp
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-calendar-x text-warning"></i></span>
                            <input type="date" name="expires_at" id="expiresAtInput" value="{{ $expiryVal }}" class="form-control">
                        </div>
                        <small class="text-muted d-block mt-1">Leave empty to allow this notice to stay active indefinitely.</small>
                    </div>

                    {{-- Quick Preset Buttons --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Quick Expiry Presets:</label>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1" onclick="setNoticeExpiry(7)">+7 Days</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1" onclick="setNoticeExpiry(15)">+15 Days</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1" onclick="setNoticeExpiry(30)">+30 Days</button>
                            <button type="button" class="btn btn-xs btn-outline-danger rounded-pill px-2.5 py-1" onclick="clearNoticeExpiry()">Clear (Never)</button>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small text-muted">Current Expiry Status:</span>
                            @if($notice->isExpired())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($notice->expires_at)
                                <span class="badge bg-success">Active (Expires {{ $notice->expires_at->format('M d, Y') }})</span>
                            @else
                                <span class="badge bg-info">No Expiry (Always Active)</span>
                            @endif
                        </div>
                        <small class="text-muted d-block" style="font-size: 11px;">
                            Expired notices are automatically suppressed from marquee tickers, homepage popups, and active notice listings.
                        </small>
                    </div>
                </div>
            </div>

            {{-- Placement / Display Mode Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-broadcast text-primary me-2"></i>Placement & Visibility</h5>
                </div>
                <div class="card-body p-4">
                    <label class="form-label fw-bold text-dark mb-2">Notice Type / Display Mode</label>
                    @php
                        $showIn = old('show_in', $notice->show_in ?? 'm');
                    @endphp
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="show_in" id="showInMarquee" value="m" {{ $showIn === 'm' ? 'checked' : '' }}>
                        <label class="form-check-label" for="showInMarquee">
                            <strong class="d-block text-dark"><i class="fa-solid fa-scroll text-success me-1"></i> Marquee Ticker</strong>
                            <small class="text-muted">Displays in the scrolling ticker and notice board.</small>
                        </label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="show_in" id="showInPopup" value="p" {{ $showIn === 'p' ? 'checked' : '' }}>
                        <label class="form-check-label" for="showInPopup">
                            <strong class="d-block text-dark"><i class="fa-solid fa-window-maximize text-warning me-1"></i> Popup Alert Modal</strong>
                            <small class="text-muted">Pops up immediately for visitors on the homepage.</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="show_in" id="showInBoard" value="b" {{ $showIn === 'b' ? 'checked' : '' }}>
                        <label class="form-check-label" for="showInBoard">
                            <strong class="d-block text-dark"><i class="fa-solid fa-chalkboard text-secondary me-1"></i> Notice Board Only</strong>
                            <small class="text-muted">Listed on the Notices page without popups or ticker scrolls.</small>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Actions Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold mb-2">
                        <i class="bi bi-check-circle me-1"></i> Update Notice
                    </button>
                    <a href="{{ route('notice.table') }}" class="btn btn-light w-100 py-2 text-muted">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Summernote
        if ($('#noticeDescription').length) {
            $('#noticeDescription').summernote({
                height: 250,
                placeholder: 'Write the complete announcement text...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        }

        // File selection indicator
        const fileInput = document.getElementById('noticeFileInput');
        const selectedInfo = document.getElementById('fileSelectedInfo');
        const fileNameSpan = document.getElementById('selectedFileName');
        const fileSizeSpan = document.getElementById('selectedFileSize');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    fileNameSpan.textContent = file.name;
                    fileSizeSpan.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                    selectedInfo.classList.remove('d-none');
                } else {
                    selectedInfo.classList.add('d-none');
                }
            });
        }
    });

    function toggleCustomCategory(show = true) {
        const wrap = document.getElementById('newCategoryWrap');
        const input = document.getElementById('newCategoryInput');
        const toggleBtn = document.getElementById('toggleNewCatBtn');

        if (!wrap) return;

        if (show) {
            wrap.classList.remove('d-none');
            if (input) input.focus();
            if (toggleBtn) toggleBtn.classList.add('d-none');
        } else {
            wrap.classList.add('d-none');
            if (input) input.value = '';
            if (toggleBtn) toggleBtn.classList.remove('d-none');
        }
    }

    function clearNoticeFile() {
        const fileInput = document.getElementById('noticeFileInput');
        const selectedInfo = document.getElementById('fileSelectedInfo');
        if (fileInput) fileInput.value = '';
        if (selectedInfo) selectedInfo.classList.add('d-none');
    }

    function setNoticeExpiry(days) {
        const dateInput = document.getElementById('expiresAtInput');
        if (!dateInput) return;
        const now = new Date();
        now.setDate(now.getDate() + days);
        const yyyy = now.getFullYear();
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const dd = String(now.getDate()).padStart(2, '0');
        dateInput.value = `${yyyy}-${mm}-${dd}`;
    }

    function clearNoticeExpiry() {
        const dateInput = document.getElementById('expiresAtInput');
        if (dateInput) dateInput.value = '';
    }
</script>
@endpush
