@extends('backend.pages.layout.master')
@push('b-title', 'Team')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Our Team</h1><p class="aph-sub">Manage administrative, teaching, and non-teaching staff.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal"><i class="bi bi-plus-lg"></i> Add Member</button>
</div>
<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-people-fill"></i> All Team Members</span>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th><i class="bi bi-arrows-move text-muted"></i></th><th>Photo</th><th>Name</th><th>Role</th><th>Staff Type</th><th>Order</th><th>Profile</th><th>Action</th></tr></thead>
        <tbody id="teacherList">
          @forelse($teacher as $data)
          <tr data-id="{{ $data->id }}" style="cursor: grab;">
            <td><i class="bi bi-grip-vertical text-muted"></i></td>
            <td><img src="{{ asset($data->image) }}" class="table-img-round" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td>{{ $data->role }}</td>
            <td>
              @php
                $typeLabel = match($data->staff_type) {
                  'administrative' => 'Administrative',
                  'non_teaching'   => 'Non-Teaching',
                  default          => 'Teaching',
                };
                $typeClass = match($data->staff_type) {
                  'administrative' => 'badge-info',
                  'non_teaching'   => 'badge-warning',
                  default          => 'badge-green',
                };
              @endphp
              <span class="badge-admin {{ $typeClass }}">{{ $typeLabel }}</span>
            </td>
            <td><span class="badge-admin badge-secondary">{{ $data->sort_order ?? 0 }}</span></td>
            <td>
              @if($data->facebook_link)
                <a href="{{ $data->facebook_link }}" target="_blank" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-facebook"></i> Profile</a>
              @else <span style="color:#718096;">—</span> @endif
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('teacher.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('teacher.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#718096;">No faculty added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="addTeacherModalLabel" style="font-weight: 600; color: #1e293b;">Add New Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('teacher.store') }}" enctype="multipart/form-data" method="POST">
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
                <input type="text" name="name" value="{{ old('name') }}" class="admin-input" placeholder="e.g. Ram Prasad Sharma" required>
            </div>
            <div class="col-md-6">
                <label class="admin-label">Designation / Role <span style="color:#e53e3e">*</span></label>
                <input type="text" name="role" value="{{ old('role') }}" class="admin-input" placeholder="e.g. Assistant Professor" required>
            </div>
            <div class="col-md-6">
                <label class="admin-label">Staff Category <span style="color:#e53e3e">*</span></label>
                <select name="staff_type" class="admin-select" required>
                    <option value="">Select category</option>
                    <option value="administrative" {{ old('staff_type') === 'administrative' ? 'selected' : '' }}>Administrative Staff</option>
                    <option value="teaching" {{ old('staff_type') === 'teaching' ? 'selected' : '' }}>Teaching Staff</option>
                    <option value="non_teaching" {{ old('staff_type') === 'non_teaching' ? 'selected' : '' }}>Non-Teaching Staff</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="admin-label">Photo <span style="color:#e53e3e">*</span></label>
                <input type="file" name="image" class="admin-input" accept="image/*" required>
            </div>
            <div class="col-md-6">
                <label class="admin-label">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="admin-input">
            </div>
            <div class="col-md-6">
                <label class="admin-label">Facebook Profile Link</label>
                <input type="text" name="facebook_link" value="{{ old('facebook_link') }}" class="admin-input" placeholder="https://facebook.com/... (optional)">
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Save Member</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('teacherList');
    if (el) {
        var sortable = Sortable.create(el, {
            animation: 150,
            ghostClass: 'bg-light',
            handle: 'tr', // Whole row is draggable, but you can also restrict to the grip icon
            onEnd: function (evt) {
                var order = [];
                el.querySelectorAll('tr[data-id]').forEach(function(row, index) {
                    order.push({
                        id: row.getAttribute('data-id'),
                        position: index + 1
                    });
                });

                // Update the UI order column badges immediately
                el.querySelectorAll('tr[data-id]').forEach(function(row, index) {
                    var orderBadge = row.querySelector('.badge-secondary');
                    if(orderBadge) orderBadge.textContent = index + 1;
                });

                // Send ajax request to save order
                fetch('{{ route('teacher.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if(!data.success) {
                        alert('Failed to save order.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});

@if($errors->any())
    var myModal = new bootstrap.Modal(document.getElementById('addTeacherModal'));
    myModal.show();
@endif
</script>
@endpush
