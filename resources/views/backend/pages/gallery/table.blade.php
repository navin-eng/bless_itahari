@extends('backend.pages.layout.master')
@push('b-title', 'Gallery')
@section('backend-content')

<!-- Cropper.js for Image Touchup -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    .cropper-container { max-height: 500px; width: 100%; }
    .cropper-img-wrapper { text-align: center; background: #f1f5f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;}
    .cropper-img-wrapper img { max-width: 100%; display: block; }

    /* Gallery item card */
    .gallery-item-card { position:relative; border-radius:8px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1); background:#fff; display:flex; flex-direction:column; transition:box-shadow 0.2s, transform 0.2s; }
    .gallery-item-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.16); }
    .gallery-item-card.selected { outline:3px solid var(--brand-color); box-shadow:0 0 0 4px rgba(30,130,100,0.15); }

    /* Action buttons (top-right on hover) */
    .gallery-item-actions { position:absolute; top:8px; right:8px; display:flex; gap:5px; z-index:3; opacity:0; transition:0.25s; }
    .gallery-item-card:hover .gallery-item-actions { opacity:1; }
    .btn-g-action { background:rgba(255,255,255,0.92); border:none; padding:5px 8px; border-radius:5px; color:#334155; cursor:pointer; box-shadow:0 2px 5px rgba(0,0,0,0.2); font-size:13px; }
    .btn-g-action:hover { background:#fff; color:var(--brand-color); }
    .btn-g-danger:hover { color:#ef4444; }

    /* Checkbox overlay (top-left) */
    .gallery-item-checkbox-wrap { position:absolute; top:8px; left:8px; z-index:4; }
    .gallery-item-checkbox-wrap input[type=checkbox] {
        width:20px; height:20px; cursor:pointer;
        accent-color: var(--brand-color);
        border-radius:4px;
        opacity:0; transition:opacity 0.2s;
    }
    .gallery-item-card:hover .gallery-item-checkbox-wrap input,
    .gallery-item-card.selected .gallery-item-checkbox-wrap input { opacity:1; }

    /* Gallery admin grid */
    .gallery-admin-row { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:14px; }

    /* Floating bulk-action bar */
    #bulk-action-bar {
        position:fixed; bottom:30px; left:50%; transform:translateX(-50%) translateY(100px);
        background:#1e293b; color:#fff; border-radius:16px; padding:12px 20px;
        display:flex; align-items:center; gap:14px; box-shadow:0 8px 32px rgba(0,0,0,0.4);
        z-index:9999; transition:transform 0.35s cubic-bezier(.34,1.56,.64,1), opacity 0.3s;
        opacity:0; pointer-events:none; white-space:nowrap;
    }
    #bulk-action-bar.show { transform:translateX(-50%) translateY(0); opacity:1; pointer-events:all; }
    #bulk-action-bar .bar-count { font-weight:700; font-size:15px; }
    #bulk-action-bar .bar-count span { color:#f59e0b; }

    /* Selection toolbar above All Items grid */
    #selection-toolbar { display:none; align-items:center; gap:10px; margin-bottom:12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:8px 14px; }
    #selection-toolbar.show { display:flex; }
</style>

<div class="admin-page-header">
  <div><h1 class="aph-title">Gallery Management</h1><p class="aph-sub">Manage your albums, upload photos, embed videos, and touch up images.</p></div>
  <div>
      <button type="button" class="btn-admin btn-admin-light me-2" data-bs-toggle="modal" data-bs-target="#albumModal">
        <i class="bi bi-folder-plus"></i> Create Album
      </button>
      <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-plus-circle"></i> Add Items
      </button>
  </div>
</div>

{{-- Create Album Modal --}}
<div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-folder-plus text-green me-2"></i>Create Album</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('gallery.album.store') }}" method="POST" enctype="multipart/form-data" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Saving...';">
        @csrf
        <div class="modal-body">
          <label class="admin-label">Album Name</label>
          <input type="text" name="name" class="admin-input mb-3" placeholder="e.g. Sports Week 2081" required>

          <label class="admin-label mt-3">Cover Image (Optional)</label>
          <input type="file" name="cover_image" class="admin-input" accept="image/*">
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save Album</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Album Modal --}}
<div class="modal fade" id="editAlbumModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-square text-green me-2"></i>Edit Album</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editAlbumForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <label class="admin-label">Album Name</label>
          <input type="text" name="name" id="edit_album_name" class="admin-input" required>
          
          <label class="admin-label mt-3">Cover Image (Optional)</label>
          <input type="file" name="cover_image" class="admin-input" accept="image/*">
          <p class="admin-input-hint">Leave blank to keep the current cover image.</p>
          
          <label class="admin-label mt-2">Status</label>
          <select name="status" id="edit_album_status" class="admin-input">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Update Album</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Item Modal (Caption & Album Assignment) --}}
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-square text-blue me-2"></i>Edit Media Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editItemForm" action="" method="POST">
        @csrf
        <div class="modal-body">
            
            <div id="touchupSection" style="display:none; text-align:center; margin-bottom: 20px;">
                <button type="button" class="btn-admin btn-admin-light w-100" onclick="openCropModal()">
                    <i class="bi bi-crop"></i> Open Image Touchup Tool
                </button>
            </div>

            <label class="admin-label">Assign to Album</label>
            <select name="album_id" id="edit_item_album" class="admin-input mb-3">
                <option value="">-- No Album (Unassigned) --</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                @endforeach
            </select>
            
            <label class="admin-label">Caption (Optional)</label>
            <input type="text" name="caption" id="edit_item_caption" class="admin-input mb-3" placeholder="Enter a caption for this item">

        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Crop/Touchup Modal --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-crop text-green me-2"></i>Image Touchup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <div class="cropper-img-wrapper">
              <img id="imageToCrop" src="" alt="Picture">
          </div>
          <div class="d-flex justify-content-center gap-2 mt-3">
              <button class="btn-admin btn-admin-light" onclick="cropper.rotate(-90)" title="Rotate Left"><i class="bi bi-arrow-counterclockwise"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.rotate(90)" title="Rotate Right"><i class="bi bi-arrow-clockwise"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.scaleX(cropper.getData().scaleX === -1 ? 1 : -1)" title="Flip Horizontal"><i class="bi bi-symmetry-horizontal"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.scaleY(cropper.getData().scaleY === -1 ? 1 : -1)" title="Flip Vertical"><i class="bi bi-symmetry-vertical"></i></button>
              <button class="btn-admin btn-admin-light" onclick="cropper.reset()" title="Reset"><i class="bi bi-arrow-repeat"></i> Reset</button>
          </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <form id="cropForm" method="POST" action="">
              @csrf
              <input type="hidden" name="cropped_image" id="croppedImageData">
              <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn-admin btn-admin-primary" onclick="submitCrop()"><i class="bi bi-save"></i> Save Touchup</button>
          </form>
      </div>
    </div>
  </div>
</div>

{{-- Add Items Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-plus-circle text-green me-2"></i>Add to Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      {{-- Tabs --}}
      <ul class="nav nav-tabs admin-tabs px-3 pt-3" style="border-bottom:none;">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-image"><i class="bi bi-image"></i> Image</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-video"><i class="bi bi-camera-video"></i> Video (URL)</a></li>
      </ul>

      <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Saving...';">
        @csrf
        <div class="modal-body">
            
            <label class="admin-label">Assign to Album (Optional)</label>
            <select name="album_id" class="admin-input mb-3">
                <option value="">-- No Album --</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                @endforeach
            </select>
            
            <label class="admin-label">Caption (Optional)</label>
            <input type="text" name="caption" class="admin-input mb-3" placeholder="Enter a short description">

            <div class="tab-content">
                {{-- Image Tab --}}
                <div class="tab-pane fade show active" id="tab-image">
                    <label class="admin-label">Image Source</label>
                    <select class="admin-input mb-3" onchange="toggleImageInput(this.value)">
                        <option value="upload">Upload from Computer</option>
                        <option value="image_url">Image URL</option>
                    </select>

                    <div id="image-upload-wrapper">
                        <input type="hidden" name="type" value="image" id="typeInput">
                        <label class="admin-label">Select Images (multiple allowed)</label>
                        <input type="file" name="gallery[]" id="fileInput" multiple class="admin-input" accept="image/*">
                        <p class="admin-input-hint">Supported: JPG, PNG, WebP. Max 5MB each. Images will be automatically compressed.</p>
                    </div>

                    <div id="image-url-wrapper" style="display:none;">
                        <label class="admin-label">Image URL</label>
                        <input type="url" name="url" id="urlInput" class="admin-input" placeholder="https://example.com/image.jpg" disabled>
                    </div>
                </div>
                
                {{-- Video Tab --}}
                <div class="tab-pane fade" id="tab-video">
                    <label class="admin-label">Video URL (YouTube/Vimeo)</label>
                    <input type="url" name="video_url" id="videoUrlInput" class="admin-input" placeholder="https://youtube.com/watch?v=..." disabled>
                </div>
            </div>

        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-save"></i> Save Items</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Main Page Content with Tabs for Albums and Items --}}
<div class="admin-card">
  <div class="admin-card-header p-0" style="border-bottom:1px solid var(--admin-border);">
      <ul class="nav nav-tabs admin-tabs px-3 pt-2" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#content-items">
                <i class="bi bi-grid-3x3-gap-fill"></i> All Items <span class="badge bg-secondary ms-1">{{ $gallery->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#content-albums">
                <i class="bi bi-folder-fill"></i> Manage Albums <span class="badge bg-secondary ms-1">{{ $albums->count() }}</span>
            </a>
        </li>
      </ul>
  </div>
  <div class="admin-card-body p-4 tab-content">
      
      {{-- Floating Bulk Delete Bar --}}
      <form id="bulk-delete-form" action="{{ route('gallery.bulkDelete') }}" method="POST" style="display:none;">
          @csrf
      </form>
      <div id="bulk-action-bar">
          <span class="bar-count"><span id="selected-count">0</span> selected</span>
          <button type="button" class="btn-admin" style="background:#ef4444;color:#fff;border-radius:8px;padding:6px 16px;" onclick="submitBulkDelete()">
              <i class="bi bi-trash-fill me-1"></i> Delete Selected
          </button>
          <button type="button" class="btn-admin btn-admin-light" style="border-radius:8px;padding:6px 12px;" onclick="clearSelection()">
              <i class="bi bi-x-lg"></i> Cancel
          </button>
      </div>

      {{-- Gallery Items Tab --}}
      <div class="tab-pane fade show active" id="content-items">

        {{-- Selection Toolbar --}}
        <div id="selection-toolbar">
            <input type="checkbox" id="select-all-cb" style="width:18px;height:18px;accent-color:var(--brand-color);cursor:pointer;" onchange="toggleSelectAll(this.checked)">
            <label for="select-all-cb" style="margin:0;cursor:pointer;font-size:14px;font-weight:600;color:#334155;">Select All</label>
            <span style="color:#94a3b8;font-size:13px;" id="selection-count-label"></span>
            <button type="button" class="btn-admin btn-admin-danger" style="margin-left:auto;padding:4px 14px;" onclick="submitBulkDelete()">
                <i class="bi bi-trash-fill me-1"></i> Delete Selected
            </button>
        </div>

        <div class="gallery-admin-row" id="gallery-items-grid">
          @forelse($gallery as $item)
            <div class="gallery-item-card" id="card-{{ $item->id }}" data-id="{{ $item->id }}">

              {{-- Checkbox (top-left) --}}
              <div class="gallery-item-checkbox-wrap">
                  <input type="checkbox" class="gallery-item-cb" value="{{ $item->id }}" onchange="onCheckboxChange()">
              </div>

              {{-- Action buttons (top-right, on hover) --}}
              <div class="gallery-item-actions">
                  @if(in_array($item->type, ['image', 'image_url']))
                    @if($item->album_id)
                      <a href="{{ route('gallery.album.setCover', ['album_id' => $item->album_id, 'gallery_id' => $item->id]) }}"
                         class="btn-g-action" title="Set as Album Cover" style="background:rgba(251,191,36,0.9);color:#1a1a1a;">
                          <i class="bi bi-star-fill"></i>
                      </a>
                    @else
                      {{-- Not in album — edit to assign first --}}
                      <button type="button" class="btn-g-action" title="Assign to album to set cover" style="background:rgba(251,191,36,0.6);color:#1a1a1a;cursor:help;">
                          <i class="bi bi-star"></i>
                      </button>
                    @endif
                  @endif
                  <button type="button" class="btn-g-action" title="Edit/Assign"
                      onclick="editItem({{ $item->id }}, '{{ addslashes($item->caption ?? '') }}', '{{ $item->album_id ?? '' }}', '{{ $item->type }}', '{{ $item->type === 'image' ? asset('backend/images/gallery/'.$item->file_path).'?v='.$item->updated_at->timestamp : '' }}')">
                      <i class="bi bi-pencil-fill"></i>
                  </button>
                  <a class="btn-g-action btn-g-danger" href="{{ route('gallery.delete', $item->id) }}" onclick="return confirm('Delete this item?')" title="Delete">
                      <i class="bi bi-trash-fill"></i>
                  </a>
              </div>

              {{-- Thumbnail --}}
              <div style="flex-grow:1; display:flex; flex-direction:column; position:relative; cursor:pointer;" onclick="toggleCard({{ $item->id }})">
                  @if($item->type === 'video_url')
                    <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:11px;z-index:2;"><i class="bi bi-play-fill text-danger"></i> Video</div>
                    <div style="width:100%;height:150px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;color:#fff;">
                        <i class="bi bi-play-circle" style="font-size:40px;opacity:0.5;"></i>
                    </div>
                  @elseif($item->type === 'image_url')
                    <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 8px;border-radius:4px;font-size:11px;z-index:2;"><i class="bi bi-link"></i> URL</div>
                    <img src="{{ $item->url }}" alt="Gallery URL" style="width:100%;height:150px;object-fit:cover;">
                  @else
                    <img src="{{ asset('backend/images/gallery/'.$item->file_path) }}?v={{ $item->updated_at->timestamp }}" alt="Gallery Photo" style="width:100%;height:150px;object-fit:cover;">
                  @endif
              </div>

              {{-- Footer info --}}
              <div style="padding:8px 10px; background:#f8fafc; font-size:12px;">
                  @if($item->album_id)
                    <div style="color:var(--brand-color); font-weight:600; margin-bottom:2px;"><i class="bi bi-folder-fill"></i> {{ $item->album->name ?? 'Album' }}</div>
                  @else
                    <div style="color:#94a3b8; font-style:italic; margin-bottom:2px;">Unassigned</div>
                  @endif
                  @if($item->caption)
                    <div style="color:#475569; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->caption }}">{{ $item->caption }}</div>
                  @endif
              </div>
            </div>
          @empty
            <div style="width:100%;text-align:center;padding:60px;color:#718096;grid-column:1/-1;">
              <i class="bi bi-images" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
              No items added yet. Click "Add Items" to start.
            </div>
          @endforelse
        </div>
      </div>


      {{-- Albums Tab --}}
      <div class="tab-pane fade" id="content-albums">
        <style>
          .album-row-header { cursor:pointer; transition:background 0.2s; user-select:none; }
          .album-row-header:hover { background:rgba(var(--brand-rgb,30,130,100), 0.06) !important; }
          .album-row-header .expand-arrow { transition:transform 0.3s; display:inline-block; color:#94a3b8; }
          .album-row-header.expanded .expand-arrow { transform:rotate(90deg); color:var(--brand-color); }
          .album-photos-row { display:none; background:#f8fafc; }
          .album-photos-row.open { display:table-row; }
          .album-photos-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:12px; padding:16px; }
          .album-photo-card { position:relative; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); background:#fff; aspect-ratio:1; }
          .album-photo-card img { width:100%; height:100%; object-fit:cover; display:block; }
          .album-photo-card .apc-actions { position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; gap:6px; opacity:0; transition:0.25s; }
          .album-photo-card:hover .apc-actions { opacity:1; }
          .apc-btn { background:rgba(255,255,255,0.95); border:none; border-radius:6px; padding:5px 8px; font-size:13px; cursor:pointer; box-shadow:0 2px 5px rgba(0,0,0,0.2); }
          .apc-btn-cover { color:#f59e0b; }
          .apc-btn-delete { color:#ef4444; }
          .apc-btn:hover { transform:scale(1.08); }
          .album-photo-cover-badge { position:absolute; top:5px; left:5px; background:#f59e0b; border-radius:4px; padding:2px 5px; font-size:10px; font-weight:700; color:#fff; z-index:2; }
          .album-empty-state { padding:30px; text-align:center; color:#94a3b8; grid-column:1/-1; }
        </style>

        <table class="table admin-table mb-0 mt-2" style="border-collapse:separate; border-spacing:0;">
            <thead>
                <tr>
                    <th width="30"></th>
                    <th>Cover</th>
                    <th>Name</th>
                    <th>Photos</th>
                    <th>Status</th>
                    <th width="130">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($albums as $album)
                @php
                    $coverFile = $album->cover_image;
                    $albumPhotos = \App\Models\Gallery::where('album_id', $album->id)->latest()->get();
                    $photoCount  = $albumPhotos->count();
                    if (!$coverFile) {
                        $first = $albumPhotos->where('type','image')->whereNotNull('file_path')->first();
                        $coverFile = $first->file_path ?? null;
                    }
                @endphp
                {{-- Album Header Row --}}
                <tr class="album-row-header" id="album-header-{{ $album->id }}" onclick="toggleAlbum({{ $album->id }})">
                    <td style="text-align:center; vertical-align:middle; color:#64748b; font-size:16px;">
                        <i class="bi bi-chevron-right expand-arrow"></i>
                    </td>
                    <td style="vertical-align:middle;">
                        @if($coverFile)
                            <div style="position:relative;display:inline-block;">
                                @if(str_contains($coverFile, 'http'))
                                    <img src="{{ $coverFile }}" style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:2px solid var(--brand-color);">
                                @else
                                    <img src="{{ asset('backend/images/gallery/'.$coverFile) }}" style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:2px solid var(--brand-color);">
                                @endif
                                <span style="position:absolute;top:-5px;right:-5px;background:#f59e0b;border-radius:50%;width:16px;height:16px;display:flex;align-items:center;justify-content:center;" title="Cover photo">
                                    <i class="bi bi-star-fill" style="font-size:8px;color:#fff;"></i>
                                </span>
                            </div>
                        @else
                            <div style="width:52px;height:52px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#94a3b8;border:2px dashed #cbd5e1;">
                                <i class="bi bi-folder" style="font-size:18px;"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:700; vertical-align:middle; font-size:15px;">
                        {{ $album->name }}
                        <div style="font-size:12px;color:#94a3b8;font-weight:400;">Click to view photos</div>
                    </td>
                    <td style="vertical-align:middle;"><span class="badge-admin badge-blue">{{ $photoCount }} items</span></td>
                    <td style="vertical-align:middle;">
                        @if($album->status === 'active')
                            <span class="badge-admin badge-green">Active</span>
                        @else
                            <span class="badge-admin badge-red">Inactive</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle;" onclick="event.stopPropagation()">
                        <button class="btn-admin btn-admin-light" style="padding:4px 8px;" onclick="editAlbum({{ $album->id }}, '{{ addslashes($album->name) }}', '{{ $album->status }}')"><i class="bi bi-pencil"></i></button>
                        <a href="{{ route('gallery.album.delete', $album->id) }}" class="btn-admin btn-admin-danger" style="padding:4px 8px;" onclick="return confirm('Delete this album and all its photos?');"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>

                {{-- Expanded Photos Row --}}
                <tr class="album-photos-row" id="album-photos-{{ $album->id }}">
                    <td colspan="6" style="padding:0; border-top:none;">
                        <div style="border-top:2px solid var(--brand-color); background:#f8fafc;">
                            <div style="padding:12px 16px 4px; display:flex; align-items:center; justify-content:space-between;">
                                <span style="font-weight:700; color:var(--brand-color); font-size:14px;">
                                    <i class="bi bi-images me-1"></i>{{ $album->name }} — All Photos
                                </span>
                                <button class="btn-admin btn-admin-primary" style="padding:4px 12px; font-size:13px;" data-bs-toggle="modal" data-bs-target="#uploadModal" onclick="document.querySelector('#uploadModal select[name=album_id]').value='{{ $album->id }}'">
                                    <i class="bi bi-plus"></i> Add Photos
                                </button>
                            </div>
                            <div class="album-photos-grid">
                                @forelse($albumPhotos as $photo)
                                <div class="album-photo-card">
                                    {{-- Cover badge --}}
                                    @if($album->cover_image && ($album->cover_image === $photo->file_path || $album->cover_image === $photo->url))
                                        <span class="album-photo-cover-badge"><i class="bi bi-star-fill"></i> Cover</span>
                                    @endif

                                    {{-- Thumbnail --}}
                                    @if($photo->type === 'image' && $photo->file_path)
                                        <img src="{{ asset('backend/images/gallery/'.$photo->file_path) }}?v={{ $photo->updated_at->timestamp }}" alt="Photo">
                                    @elseif($photo->type === 'image_url' && $photo->url)
                                        <img src="{{ $photo->url }}" alt="Photo" onerror="this.src='{{ asset('backend/images/placeholder.png') }}'">
                                    @else
                                        <div style="width:100%;height:100%;background:#1a1a2e;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-play-circle" style="font-size:30px;color:#fff;opacity:0.5;"></i>
                                        </div>
                                    @endif

                                    {{-- Actions overlay --}}
                                    <div class="apc-actions">
                                        @if(in_array($photo->type, ['image', 'image_url']) && ($photo->file_path || $photo->url))
                                        <a href="{{ route('gallery.album.setCover', ['album_id' => $album->id, 'gallery_id' => $photo->id]) }}" class="apc-btn apc-btn-cover" title="Set as Cover">
                                            <i class="bi bi-star-fill"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('gallery.delete', $photo->id) }}" class="apc-btn apc-btn-delete" title="Delete" onclick="return confirm('Delete this photo?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="album-empty-state">
                                    <i class="bi bi-images" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px;"></i>
                                    No photos yet. Click "Add Photos" above.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">
                        <i class="bi bi-folder" style="font-size:36px;opacity:.3;display:block;margin-bottom:10px;"></i>
                        No albums created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
      </div>


  </div>
</div>

<script>
    // Tab toggle logic for the Add Items Modal
    document.querySelectorAll('#uploadModal .nav-link').forEach(link => {
        link.addEventListener('shown.bs.tab', function(e) {
            let target = e.target.getAttribute('href');
            let typeInput = document.getElementById('typeInput');
            let fileInput = document.getElementById('fileInput');
            let urlInput = document.getElementById('urlInput');
            let videoUrlInput = document.getElementById('videoUrlInput');
            
            // Reset disabled states
            fileInput.disabled = true;
            urlInput.disabled = true;
            videoUrlInput.disabled = true;
            
            if (target === '#tab-image') {
                // If it's the image tab, re-check the image source select
                let source = document.querySelector('#tab-image select').value;
                toggleImageInput(source);
            } else if (target === '#tab-video') {
                typeInput.value = 'video_url';
                videoUrlInput.disabled = false;
                videoUrlInput.name = 'url'; // Send video URL as 'url'
                urlInput.name = ''; // Prevent sending empty image url
            }
        });
    });

    function toggleImageInput(source) {
        let typeInput = document.getElementById('typeInput');
        let fileInput = document.getElementById('fileInput');
        let urlInput = document.getElementById('urlInput');
        let videoUrlInput = document.getElementById('videoUrlInput');
        
        let fileWrap = document.getElementById('image-upload-wrapper');
        let urlWrap = document.getElementById('image-url-wrapper');
        
        videoUrlInput.disabled = true; // Ensure video is disabled
        videoUrlInput.name = '';

        if (source === 'upload') {
            typeInput.value = 'image';
            fileWrap.style.display = 'block';
            urlWrap.style.display = 'none';
            fileInput.disabled = false;
            urlInput.disabled = true;
            urlInput.name = '';
        } else {
            typeInput.value = 'image_url';
            fileWrap.style.display = 'none';
            urlWrap.style.display = 'block';
            fileInput.disabled = true;
            urlInput.disabled = false;
            urlInput.name = 'url';
        }
    }

    // Edit Album Modal logic
    function editAlbum(id, name, status) {
        document.getElementById('editAlbumForm').action = '/admin/dashboard/gallery/albums/update/' + id;
        document.getElementById('edit_album_name').value = name;
        document.getElementById('edit_album_status').value = status;
        var editModal = new bootstrap.Modal(document.getElementById('editAlbumModal'));
        editModal.show();
    }

    // Edit Item Modal logic
    let currentImageToCropId = null;
    let currentImageToCropUrl = null;
    let cropper = null;

    function editItem(id, caption, album_id, type, localImageUrl) {
        document.getElementById('editItemForm').action = '/admin/dashboard/gallery/update/' + id;
        document.getElementById('edit_item_caption').value = caption;
        document.getElementById('edit_item_album').value = album_id;
        
        // Show touchup button only if it's a locally uploaded image
        let touchupSec = document.getElementById('touchupSection');
        if (type === 'image' && localImageUrl) {
            touchupSec.style.display = 'block';
            currentImageToCropId = id;
            currentImageToCropUrl = localImageUrl;
        } else {
            touchupSec.style.display = 'none';
            currentImageToCropId = null;
            currentImageToCropUrl = null;
        }
        
        var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
        editModal.show();
    }

    // Cropper.js Integration
    function openCropModal() {
        if(!currentImageToCropUrl) return;
        
        // Hide edit item modal
        var editModal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
        editModal.hide();
        
        // Setup crop form action
        document.getElementById('cropForm').action = '/admin/dashboard/gallery/crop/' + currentImageToCropId;
        
        let image = document.getElementById('imageToCrop');
        image.src = currentImageToCropUrl;
        
        // Show crop modal
        var cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
        cropModal.show();
        
        // Initialize cropper after modal is shown to calculate dimensions properly
        document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image, {
                viewMode: 1,
                autoCropArea: 1,
                responsive: true
            });
        }, { once: true });
    }

    function submitCrop() {
        if (!cropper) return;
        
        // Get base64 cropped image
        const canvas = cropper.getCroppedCanvas();
        if(!canvas) {
            alert('Could not crop image.');
            return;
        }
        
        const dataURL = canvas.toDataURL('image/jpeg', 0.9);
        document.getElementById('croppedImageData').value = dataURL;
        document.getElementById('cropForm').submit();
    }

    // Album expand/collapse toggle
    function toggleAlbum(albumId) {
        const header = document.getElementById('album-header-' + albumId);
        const photosRow = document.getElementById('album-photos-' + albumId);

        if (!photosRow) return;

        const isOpen = photosRow.classList.contains('open');

        // Close all other open albums first
        document.querySelectorAll('.album-photos-row.open').forEach(row => {
            row.classList.remove('open');
        });
        document.querySelectorAll('.album-row-header.expanded').forEach(row => {
            row.classList.remove('expanded');
        });

        // Toggle this album
        if (!isOpen) {
            photosRow.classList.add('open');
            header.classList.add('expanded');
            // Scroll header into view smoothly
            setTimeout(() => header.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 50);
        }
    }

    // ─── Multi-select logic ───────────────────────────────────────────────────

    function toggleCard(id) {
        const card = document.getElementById('card-' + id);
        const cb   = card ? card.querySelector('.gallery-item-cb') : null;
        if (!cb) return;
        cb.checked = !cb.checked;
        onCheckboxChange();
    }

    function onCheckboxChange() {
        const allCbs   = document.querySelectorAll('.gallery-item-cb');
        const checked  = [...allCbs].filter(cb => cb.checked);
        const count    = checked.length;
        const bar      = document.getElementById('bulk-action-bar');
        const toolbar  = document.getElementById('selection-toolbar');
        const countEl  = document.getElementById('selected-count');
        const labelEl  = document.getElementById('selection-count-label');
        const selectAllCb = document.getElementById('select-all-cb');

        // Update card visual state
        allCbs.forEach(cb => {
            const card = cb.closest('.gallery-item-card');
            if (card) card.classList.toggle('selected', cb.checked);
        });

        countEl.textContent = count;
        if (labelEl) labelEl.textContent = count + ' item' + (count === 1 ? '' : 's') + ' selected';

        if (count > 0) {
            bar.classList.add('show');
            toolbar.classList.add('show');
        } else {
            bar.classList.remove('show');
            toolbar.classList.remove('show');
        }

        // Sync select-all checkbox state
        if (selectAllCb) {
            selectAllCb.checked      = count === allCbs.length && allCbs.length > 0;
            selectAllCb.indeterminate = count > 0 && count < allCbs.length;
        }
    }

    function toggleSelectAll(checked) {
        document.querySelectorAll('.gallery-item-cb').forEach(cb => {
            cb.checked = checked;
        });
        onCheckboxChange();
    }

    function clearSelection() {
        document.querySelectorAll('.gallery-item-cb').forEach(cb => cb.checked = false);
        onCheckboxChange();
    }

    function submitBulkDelete() {
        const checked = [...document.querySelectorAll('.gallery-item-cb:checked')];
        if (checked.length === 0) return;

        if (!confirm('Delete ' + checked.length + ' selected item(s)? This cannot be undone.')) return;

        const form = document.getElementById('bulk-delete-form');
        // Remove any previous id inputs
        form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        form.submit();
    }
</script>

@endsection
