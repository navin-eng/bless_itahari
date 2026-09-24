@extends('backend.pages.layout.master')
@push('b-title', 'Notices')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Notices</h1><p class="aph-sub">Manage notices shown in marquee or as popups.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addNoticeModal"><i class="bi bi-plus-lg"></i> Add Notice</button>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-bell-fill"></i> All Notices</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Title</th><th>Image</th><th>Display As</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($notice as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td style="font-weight:600;">{{ $data->title }}</td>
            <td>
              @if($data->image)
                <img src="{{ asset($data->image) }}" class="table-img" alt="Notice">
              @else
                <span class="badge-admin badge-info">No Image</span>
              @endif
            </td>
            <td>
              <a href="{{ route('notice.status', $data->id) }}" class="badge-admin {{ $data->show_in == 'm' ? 'badge-green' : 'badge-info' }}">
                {{ $data->show_in == 'm' ? 'Marquee' : 'Popup' }}
              </a>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('notice.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('notice.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:40px;color:#718096;">No notices added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="addNoticeModalLabel" style="font-weight: 600; color: #1e293b;">Add New Notice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('notice.store') }}" enctype="multipart/form-data" method="POST">
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
              <label class="form-label" style="font-weight: 500;">Title</label>
              <input type="text" name="title" value="{{ old('title') }}" class="form-control" style="border-radius: 8px;" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-weight: 500;">Image</label>
              <input type="file" name="image" class="form-control" style="border-radius: 8px;">
            </div>
            <div class="col-12">
              <label class="form-label" style="font-weight: 500;">Description</label>
              <textarea class="form-control" name="description" rows="4" style="border-radius: 8px;">{{ old('description') }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Save Notice</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('addNoticeModal'));
        myModal.show();
    });
</script>
@endif
@endpush
@endsection
