@extends('backend.pages.layout.master')
@push('b-title', 'Academic Levels & Programs')
@section('backend-content')
<div class="admin-page-header">
  <div>
    <h1 class="aph-title">Academic Levels & Programs (PG to Grade 12)</h1>
    <p class="aph-sub">Manage school educational tiers, curriculum guidelines (CDC/NEB), academic rules, and admission criteria.</p>
  </div>
  <a href="{{ route('course.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add New Level</a>
</div>
<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-mortarboard-fill"></i> All School Levels</span>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Level Name</th>
            <th>Tier / Category</th>
            <th>Grade Span</th>
            <th>Evaluation System</th>
            <th>School Hours</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($course as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                @if($data->image)
                  <img src="{{ asset($data->image) }}" class="table-img rounded" alt="{{ $data->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                @endif
                <div>
                  <div style="font-weight:600; color: #1e293b;">{{ $data->name }}</div>
                  <small class="text-muted">{{ $data->duration }}</small>
                </div>
              </div>
            </td>
            <td>
              <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 11px;">
                {{ $data->academic_level ?? 'School Level' }}
              </span>
            </td>
            <td><span class="fw-semibold text-dark">{{ $data->grade_span ?: 'N/A' }}</span></td>
            <td style="max-width: 220px; font-size: 12px; color: #475569;">
              {{ Str::limit($data->evaluation_system ?: ($data->semester ?: 'Annual Session'), 50) }}
            </td>
            <td style="white-space:nowrap;font-size:12px;">
              @if($data->starting_time || $data->closing_time)
                <i class="bi bi-clock text-primary"></i> {{ $data->starting_time }} – {{ $data->closing_time }}
              @else
                <span class="text-muted">Standard</span>
              @endif
            </td>
            <td>
              <a href="{{ route('course.status', $data->id) }}" class="badge-admin {{ $data->status==1 ? 'badge-active' : 'badge-inactive' }}">
                {{ $data->status==1 ? 'Active' : 'Inactive' }}
              </a>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('course.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info" title="Edit Level"><i class="bi bi-pencil"></i></a>
                <a href="{{ url('course/' . $data->slug) }}" target="_blank" class="btn-admin btn-admin-sm btn-admin-outline" title="Preview on Website"><i class="bi bi-box-arrow-up-right"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('course.destroy', $data->id) }}" title="Delete"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:40px;color:#718096;">No academic levels configured yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
