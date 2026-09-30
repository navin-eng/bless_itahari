@extends('backend.pages.layout.master')
@push('b-title', 'Notices')
@section('backend-content')
<div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="aph-title mb-1">Notices & Circulars</h1>
    <p class="aph-sub text-muted mb-0">Manage school notices, PDF routines, circulars, and expiry schedules.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('notice.add') }}" class="btn btn-primary px-3 py-2 rounded-3 fw-bold">
      <i class="bi bi-plus-lg me-1"></i> Add Notice
    </a>
  </div>
</div>

{{-- Status Filter Tabs --}}
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div class="nav nav-pills">
      <a href="{{ route('notice.table', ['tab' => 'all']) }}" class="nav-link {{ ($tab ?? 'all') === 'all' ? 'active' : '' }} py-1.5 px-3 rounded-pill fw-semibold">
        All Notices <span class="badge {{ ($tab ?? 'all') === 'all' ? 'bg-white text-primary' : 'bg-secondary' }} ms-1">{{ $allCount ?? count($notice) }}</span>
      </a>
      <a href="{{ route('notice.table', ['tab' => 'active']) }}" class="nav-link {{ ($tab ?? 'all') === 'active' ? 'active' : '' }} py-1.5 px-3 rounded-pill fw-semibold">
        <i class="bi bi-check-circle me-1"></i> Active <span class="badge {{ ($tab ?? 'all') === 'active' ? 'bg-white text-primary' : 'bg-success' }} ms-1">{{ $activeCount ?? 0 }}</span>
      </a>
      <a href="{{ route('notice.table', ['tab' => 'inactive']) }}" class="nav-link {{ ($tab ?? 'all') === 'inactive' ? 'active' : '' }} py-1.5 px-3 rounded-pill fw-semibold">
        <i class="bi bi-pause-circle me-1"></i> Inactive <span class="badge {{ ($tab ?? 'all') === 'inactive' ? 'bg-white text-primary' : 'bg-secondary' }} ms-1">{{ $inactiveCount ?? 0 }}</span>
      </a>
      <a href="{{ route('notice.table', ['tab' => 'expired']) }}" class="nav-link {{ ($tab ?? 'all') === 'expired' ? 'active' : '' }} py-1.5 px-3 rounded-pill fw-semibold">
        <i class="bi bi-clock-history me-1"></i> Expired <span class="badge {{ ($tab ?? 'all') === 'expired' ? 'bg-white text-primary' : 'bg-danger' }} ms-1">{{ $expiredCount ?? 0 }}</span>
      </a>
    </div>

    <div class="text-muted small px-3">
      <i class="bi bi-info-circle me-1"></i> Toggle switch lets you instantly activate or deactivate notices from public view.
    </div>
  </div>
</div>

<div class="admin-card card border-0 shadow-sm">
  <div class="admin-card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
    <span class="card-title fw-bold text-dark mb-0 fs-5">
      <i class="bi bi-bell-fill text-primary me-2"></i> Notice Records
    </span>
    <a href="{{ route('notice.add') }}" class="btn btn-primary btn-sm rounded-pill px-3">
      <i class="bi bi-plus-lg me-1"></i> Add New Notice
    </a>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-4" style="width: 50px;">#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Attachment</th>
            <th>Status</th>
            <th>Placement</th>
            <th>Published</th>
            <th>Expiry Date</th>
            <th class="text-end pe-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notice as $data)
          <tr id="noticeRow{{ $data->id }}" style="{{ !$data->is_active ? 'opacity: 0.72;' : '' }}">
            <td class="ps-4"><span class="badge bg-light text-secondary border">{{ $loop->iteration }}</span></td>
            <td>
              <div class="d-flex align-items-center gap-3">
                @if($data->image)
                  <img src="{{ asset($data->image) }}" class="rounded-2 border" style="width: 44px; height: 44px; object-fit: cover;" alt="Notice">
                @else
                  <div class="rounded-2 border bg-light d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-bell text-secondary"></i>
                  </div>
                @endif
                <div>
                  <a href="{{ url('notice/detail/' . $data->id) }}" target="_blank" class="fw-bold text-dark text-decoration-none">
                    {{ $data->title }}
                  </a>
                  <p class="text-muted small mb-0">{{ Str::limit(strip_tags($data->description), 50) }}</p>
                </div>
              </div>
            </td>
            <td>
              @if(!empty($data->category))
                <span class="badge bg-secondary-subtle text-dark border px-2 py-1">
                  <i class="bi bi-tag-fill text-primary me-1"></i>{{ $data->category }}
                </span>
              @else
                <span class="text-muted small">&mdash;</span>
              @endif
            </td>
            <td>
              @if($data->hasFile())
                @if($data->isPdf())
                  <a href="{{ asset($data->file) }}" target="_blank" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none px-2 py-1.5" title="View PDF">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                  </a>
                @else
                  <a href="{{ asset($data->file) }}" download class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1.5" title="Download Document">
                    <i class="fa-solid fa-paperclip me-1"></i> File
                  </a>
                @endif
                <div class="text-muted" style="font-size: 10px;">{{ $data->file_size }}</div>
              @else
                <span class="text-muted small">&mdash;</span>
              @endif
            </td>
            <td>
              <div class="form-check form-switch d-inline-flex align-items-center gap-2 m-0 p-0" style="min-height: auto;">
                <input class="form-check-input notice-status-toggle ms-0" type="checkbox" role="switch"
                       id="toggleNotice{{ $data->id }}"
                       data-id="{{ $data->id }}"
                       data-url="{{ route('notice.status', $data->id) }}"
                       {{ $data->is_active ? 'checked' : '' }}
                       style="cursor: pointer; width: 2.3em; height: 1.2em;"
                       title="Click to toggle Active/Inactive">
                <label class="form-check-label small fw-bold {{ $data->is_active ? 'text-success' : 'text-muted' }}" 
                       for="toggleNotice{{ $data->id }}" 
                       id="statusLabel{{ $data->id }}" 
                       style="cursor: pointer; user-select: none;">
                  {{ $data->is_active ? 'Active' : 'Inactive' }}
                </label>
              </div>
            </td>
            <td>
              <a href="{{ route('notice.placement', $data->id) }}" class="badge text-decoration-none {{ $data->show_in === 'm' ? 'bg-success' : ($data->show_in === 'p' ? 'bg-warning text-dark' : 'bg-secondary') }}" title="Click to cycle placement mode (Marquee / Popup / Board Only)">
                @if($data->show_in === 'm')
                  <i class="fa-solid fa-scroll me-1"></i> Marquee
                @elseif($data->show_in === 'p')
                  <i class="fa-solid fa-window-maximize me-1"></i> Popup
                @else
                  <i class="fa-solid fa-chalkboard me-1"></i> Board Only
                @endif
              </a>
            </td>
            <td>
              <span class="small text-dark">{{ format_system_date($data->created_at) }}</span>
            </td>
            <td>
              @if($data->expires_at)
                <div>
                  <span class="small d-block fw-semibold text-dark">{{ format_system_date($data->expires_at) }}</span>
                  @if($data->isExpired())
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1.5 py-0.5" style="font-size: 10px;">
                      <i class="bi bi-x-circle me-1"></i> Expired
                    </span>
                  @else
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5" style="font-size: 10px;">
                      <i class="bi bi-check-circle me-1"></i> Active
                    </span>
                  @endif
                </div>
              @else
                <span class="badge bg-light text-secondary border px-2 py-1 small">
                  <i class="bi bi-infinity me-1"></i> No Expiry
                </span>
              @endif
            </td>
            <td class="text-end pe-4">
              <div class="d-inline-flex gap-1">
                <a href="{{ url('notice/detail/' . $data->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View Public Notice">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('notice.edit', $data->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Notice">
                  <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger delete-wrap" data-route="{{ route('notice.destroy', $data->id) }}" title="Delete Notice">
                  <i class="bi bi-trash3"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center py-5 text-muted">
              <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary opacity-50"></i>
              No notices found in this view.
              <div class="mt-2">
                <a href="{{ route('notice.add') }}" class="btn btn-sm btn-primary">Create First Notice</a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.notice-status-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const noticeId = this.dataset.id;
            const url = this.dataset.url;
            const label = document.getElementById('statusLabel' + noticeId);
            const row = document.getElementById('noticeRow' + noticeId);
            const isChecked = this.checked;

            // Visual update
            if (label) {
                label.textContent = isChecked ? 'Active' : 'Inactive';
                label.className = 'form-check-label small fw-bold ' + (isChecked ? 'text-success' : 'text-muted');
            }
            if (row) {
                row.style.opacity = isChecked ? '1' : '0.72';
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (label) {
                        label.textContent = data.status_label;
                        label.className = 'form-check-label small fw-bold ' + (data.is_active ? 'text-success' : 'text-muted');
                    }
                    if (row) {
                        row.style.opacity = data.is_active ? '1' : '0.72';
                    }
                } else {
                    // Revert if error
                    toggle.checked = !isChecked;
                    if (label) {
                        label.textContent = !isChecked ? 'Active' : 'Inactive';
                        label.className = 'form-check-label small fw-bold ' + (!isChecked ? 'text-success' : 'text-muted');
                    }
                    if (row) {
                        row.style.opacity = !isChecked ? '1' : '0.72';
                    }
                }
            })
            .catch(err => {
                // If fetch fails, fallback to navigation
                window.location.href = url;
            });
        });
    });
});
</script>
@endpush
