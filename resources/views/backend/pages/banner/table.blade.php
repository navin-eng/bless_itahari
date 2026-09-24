@extends('backend.pages.layout.master')
@push('b-title', 'Banners')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Banners</h1><p class="aph-sub">Manage homepage hero slider banners.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal"><i class="bi bi-plus-lg"></i> Add Banner</button>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-image-fill"></i> All Banners</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th><i class="bi bi-arrows-move text-muted"></i></th><th>#</th><th>Image</th><th>Title One</th><th>Title Two</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="sortable-table-body">
          @forelse($banner as $data)
          <tr data-id="{{ $data->id }}">
            <td><i class="bi bi-grip-vertical text-muted drag-handle" style="cursor: grab; font-size: 1.2rem;"></i></td>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img" alt="Banner"></td>
            <td style="font-weight:600;">{{ $data->title1 }}</td>
            <td style="color:#718096;">{{ $data->title2 }}</td>
            <td><a href="{{ route('banner.status', $data->id) }}" class="badge-admin {{ $data->status==1 ? 'badge-active' : 'badge-inactive' }}">{{ $data->status==1 ? 'Active' : 'Inactive' }}</a></td>
            <td>
              <div style="display:flex;gap:6px;">
                <button type="button" class="btn-admin btn-admin-sm btn-admin-info" data-bs-toggle="modal" data-bs-target="#editBannerModal{{ $data->id }}" title="Edit Banner"><i class="bi bi-pencil"></i></button>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('banner.destroy', $data->id) }}" title="Delete Banner"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#718096;">No banners added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="addBannerModalLabel" style="font-weight: 600; color: #1e293b;">Add New Banner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('banner.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-body p-4">
          @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px;">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="mb-3">
              <label class="admin-label">Title (One) <span style="color:#e53e3e">*</span></label>
              <input type="text" name="title1" value="{{ old('title1') }}" class="admin-input" required>
          </div>
          <div class="mb-3">
              <label class="admin-label">Title (Two) <span style="color:#e53e3e">*</span></label>
              <input type="text" name="title2" value="{{ old('title2') }}" class="admin-input" required>
          </div>
          <div class="mb-3">
              <label class="admin-label">Image <span style="color:#e53e3e">*</span></label>
              <input type="file" name="image" class="admin-input" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Save Banner</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Banner Modals -->
@foreach($banner as $data)
<div class="modal fade" id="editBannerModal{{ $data->id }}" tabindex="-1" aria-labelledby="editBannerModalLabel{{ $data->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="editBannerModalLabel{{ $data->id }}" style="font-weight: 600; color: #1e293b;">Edit Banner #{{ $loop->iteration }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('banner.update', $data->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
              <label class="admin-label">Title (One) <span style="color:#e53e3e">*</span></label>
              <input type="text" name="title1" value="{{ old('title1', $data->title1) }}" class="admin-input" required>
          </div>
          <div class="mb-3">
              <label class="admin-label">Title (Two) <span style="color:#e53e3e">*</span></label>
              <input type="text" name="title2" value="{{ old('title2', $data->title2) }}" class="admin-input" required>
          </div>
          <div class="mb-3">
              <label class="admin-label">Replace Image</label>
              <input type="file" name="image" class="admin-input" accept="image/*">
              <small class="text-muted d-block mt-1">Leave empty to keep current image.</small>
              @if($data->image)
              <div class="mt-2" style="background:#f1f5f9; padding:8px; border-radius:8px; display:inline-block;">
                <p class="mb-1" style="font-size:12px; color:#64748b; font-weight:600;">Current Image Preview:</p>
                <img src="{{ asset($data->image) }}" alt="Banner" style="max-height: 80px; max-width: 100%; border-radius: 6px; border: 1px solid #cbd5e1; object-fit: cover;">
              </div>
              @endif
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Update Banner</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('addBannerModal'));
            myModal.show();
        });
    @endif
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('sortable-table-body');
        if (el) {
            var sortable = Sortable.create(el, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function (evt) {
                    var order = [];
                    el.querySelectorAll('tr').forEach(function (row) {
                        if (row.getAttribute('data-id')) {
                            order.push(row.getAttribute('data-id'));
                        }
                    });

                    fetch('{{ route('banner.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              const Toast = Swal.mixin({
                                  toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
                              });
                              Toast.fire({ icon: 'success', title: 'Order saved successfully' });
                              // Update row numbers
                              el.querySelectorAll('tr').forEach(function (row, index) {
                                  let badge = row.querySelector('.sr-badge');
                                  if (badge) badge.innerText = index + 1;
                              });
                          }
                      });
                }
            });
        }
    });
</script>
@endpush
