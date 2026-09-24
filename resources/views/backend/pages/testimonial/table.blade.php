@extends('backend.pages.layout.master')
@push('b-title', 'Testimonials')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Testimonials</h1><p class="aph-sub">Manage student and alumni testimonials.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addTestimonialModal"><i class="bi bi-plus-lg"></i> Add Testimonial</button>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-chat-quote-fill"></i> All Testimonials</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th><i class="bi bi-arrows-move text-muted"></i></th><th>#</th><th>Photo</th><th>Name</th><th>Role</th><th>Action</th></tr></thead>
        <tbody id="sortable-table-body">
          @forelse($testimonial as $data)
          <tr data-id="{{ $data->id }}">
            <td><i class="bi bi-grip-vertical text-muted drag-handle" style="cursor: grab; font-size: 1.2rem;"></i></td>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img-round" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td>{{ $data->role }}</td>
            <td>
              <div style="display:flex;gap:6px;">
                <button type="button" class="btn-admin btn-admin-sm btn-admin-info" data-bs-toggle="modal" data-bs-target="#editTestimonialModal{{ $data->id }}" title="Edit Testimonial"><i class="bi bi-pencil"></i></button>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('testimonial.destroy', $data->id) }}" title="Delete Testimonial"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:40px;color:#718096;">No testimonials added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-labelledby="addTestimonialModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="addTestimonialModalLabel" style="font-weight: 600; color: #1e293b;">Add New Testimonial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('testimonial.store') }}" enctype="multipart/form-data" method="POST">
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
          <div class="row g-3">
            <div class="col-md-6">
                <label class="admin-label">Full Name <span style="color:#e53e3e">*</span></label>
                <input type="text" name="testimonials[0][name]" value="{{ old('testimonials.0.name') }}" class="admin-input" placeholder="e.g. Ram Prasad Sharma" required>
            </div>
            <div class="col-md-6">
                <label class="admin-label">Role / Identity <span style="color:#e53e3e">*</span></label>
                <input type="text" name="testimonials[0][role]" value="{{ old('testimonials.0.role') }}" class="admin-input" placeholder="e.g. BBA Student" required>
            </div>
            <div class="col-md-12">
                <label class="admin-label">Photo <span style="color:#e53e3e">*</span></label>
                <input type="file" name="testimonials[0][image]" class="admin-input" accept="image/*" required>
            </div>
            <div class="col-md-12">
                <label class="admin-label">Testimonial Message <span style="color:#e53e3e">*</span></label>
                <textarea name="testimonials[0][description]" class="admin-textarea" rows="4" required>{{ old('testimonials.0.description') }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Save Testimonial</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Testimonial Modals -->
@foreach($testimonial as $data)
<div class="modal fade" id="editTestimonialModal{{ $data->id }}" tabindex="-1" aria-labelledby="editTestimonialModalLabel{{ $data->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="editTestimonialModalLabel{{ $data->id }}" style="font-weight: 600; color: #1e293b;">Edit Testimonial #{{ $loop->iteration }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('testimonial.update', $data->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
                <label class="admin-label">Full Name <span style="color:#e53e3e">*</span></label>
                <input type="text" name="name" value="{{ old('name', $data->name) }}" class="admin-input" required>
            </div>
            <div class="col-md-6">
                <label class="admin-label">Role / Identity <span style="color:#e53e3e">*</span></label>
                <input type="text" name="role" value="{{ old('role', $data->role) }}" class="admin-input" required>
            </div>
            <div class="col-md-12">
                <label class="admin-label">Replace Photo</label>
                <input type="file" name="image" class="admin-input" accept="image/*">
                <small class="text-muted d-block mt-1">Leave empty to keep existing photo.</small>
                @if($data->image)
                <div class="mt-2" style="background:#f1f5f9; padding:8px; border-radius:8px; display:inline-block;">
                  <p class="mb-1" style="font-size:12px; color:#64748b; font-weight:600;">Current Photo:</p>
                  <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" style="height: 60px; width: 60px; border-radius: 50%; object-fit: cover; border: 1px solid #cbd5e1;">
                </div>
                @endif
            </div>
            <div class="col-md-12">
                <label class="admin-label">Testimonial Message <span style="color:#e53e3e">*</span></label>
                <textarea name="description" class="admin-textarea" rows="4" required>{{ old('description', $data->description) }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Update Testimonial</button>
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
            var myModal = new bootstrap.Modal(document.getElementById('addTestimonialModal'));
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

                    fetch('{{ route('testimonial.reorder') }}', {
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
