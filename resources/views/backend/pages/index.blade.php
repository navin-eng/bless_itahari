@extends('backend.pages.layout.master')
@push('b-title', 'Dashboard')

@section('backend-content')
@include('sweetalert::alert')

@php
  $courses    = App\Models\Course::count();
  $teachers   = App\Models\Teacher::count();
  $events     = App\Models\Event::count();
  $notices    = App\Models\Notice::count();
  $banners    = App\Models\Banner::count();
  $galleries  = App\Models\Gallery::count();
  $admissions = App\Models\AdmissionEnquiry::count();

  // Generate last 6 months for chart
  $chartLabels = [];
  $chartData = [];
  for ($i = 5; $i >= 0; $i--) {
      $date = now()->subMonths($i);
      $chartLabels[] = $date->format('M');
      $chartData[] = App\Models\AdmissionEnquiry::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count();
  }
@endphp

<div class="admin-page-header">
  <div>
    <h1 class="aph-title">Welcome back, {{ Auth::user()->name }} 👋</h1>
    <p class="aph-sub">Here is your KPI dashboard and quick actions.</p>
  </div>
</div>

{{-- KPI Stat Cards --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-4 col-xl-3">
    <a href="{{ route('admin.admissions.index') }}" class="stat-card">
      <div class="stat-icon" style="background: rgba(var(--bs-primary-rgb), 0.1); color: var(--bs-primary);"><i class="bi bi-inbox-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $admissions }}</div>
        <div class="stat-label">Admissions</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('course.table') }}" class="stat-card">
      <div class="stat-icon green"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $courses }}</div>
        <div class="stat-label">Courses</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('teacher.table') }}" class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $teachers }}</div>
        <div class="stat-label">Faculty</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('event.table') }}" class="stat-card">
      <div class="stat-icon amber"><i class="bi bi-calendar2-event-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $events }}</div>
        <div class="stat-label">Events</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-3">
    <a href="{{ route('notice.table') }}" class="stat-card">
      <div class="stat-icon rose"><i class="bi bi-bell-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $notices }}</div>
        <div class="stat-label">Notices</div>
      </div>
    </a>
  </div>
</div>

{{-- Site Health & Storage Overview --}}
@if(isset($siteHealth))
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="admin-card border-0 shadow-sm">
      <div class="admin-card-header d-flex flex-wrap align-items-center justify-content-between gap-2 bg-light py-3 px-4 rounded-top">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-success-subtle text-success p-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="bi bi-heart-pulse-fill fs-5"></i>
          </div>
          <div>
            <h5 class="mb-0 fw-bold text-dark fs-6">Site Health & Storage Monitor</h5>
            <small class="text-muted">Real-time system diagnostics, image payload & server metrics</small>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge {{ $siteHealth['health_score'] >= 90 ? 'bg-success' : ($siteHealth['health_score'] >= 70 ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2 fs-7 fw-semibold">
            <i class="bi bi-shield-check me-1"></i> Health Score: {{ $siteHealth['health_score'] }}%
          </span>
          <form action="{{ route('admin.clear-cache') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary fw-medium" title="Clear system cache and recalculate metrics">
              <i class="bi bi-arrow-clockwise me-1"></i> Purge Cache & Scan
            </button>
          </form>
        </div>
      </div>
      <div class="admin-card-body p-4">
        <div class="row g-4 align-items-stretch">
          
          {{-- Image Assets Storage --}}
          <div class="col-12 col-md-6 col-xl-3 border-end-md">
            <div class="p-3 rounded bg-light-subtle h-100 border">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-uppercase small fw-bold text-muted tracking-wider"><i class="bi bi-images text-primary me-1"></i> Image Assets</span>
                <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $siteHealth['image_count'] }} files</span>
              </div>
              <h3 class="fw-extrabold text-primary mb-1">{{ $siteHealth['image_size'] }}</h3>
              <p class="text-muted small mb-0">Total space consumed by uploaded & system images</p>
            </div>
          </div>

          {{-- Documents Storage --}}
          <div class="col-12 col-md-6 col-xl-3 border-end-md">
            <div class="p-3 rounded bg-light-subtle h-100 border">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-uppercase small fw-bold text-muted tracking-wider"><i class="bi bi-file-earmark-pdf text-danger me-1"></i> Documents</span>
                <span class="badge bg-danger-subtle text-danger fw-semibold">{{ $siteHealth['doc_count'] }} files</span>
              </div>
              <h3 class="fw-extrabold text-danger mb-1">{{ $siteHealth['doc_size'] }}</h3>
              <p class="text-muted small mb-0">PDFs, Word docs & attachment storage space</p>
            </div>
          </div>

          {{-- Database Storage --}}
          <div class="col-12 col-md-6 col-xl-3 border-end-md">
            <div class="p-3 rounded bg-light-subtle h-100 border">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-uppercase small fw-bold text-muted tracking-wider"><i class="bi bi-database text-warning me-1"></i> Database Size</span>
                <span class="badge bg-warning-subtle text-warning fw-semibold">MySQL</span>
              </div>
              <h3 class="fw-extrabold text-warning mb-1">{{ $siteHealth['db_size'] }}</h3>
              <p class="text-muted small mb-0">Table indexes & database data overhead</p>
            </div>
          </div>

          {{-- Server Disk Usage --}}
          <div class="col-12 col-md-6 col-xl-3">
            <div class="p-3 rounded bg-light-subtle h-100 border">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-uppercase small fw-bold text-muted tracking-wider"><i class="bi bi-hdd-network text-info me-1"></i> Server Disk</span>
                <span class="badge bg-info-subtle text-info fw-semibold">{{ $siteHealth['disk_used_percent'] }}% used</span>
              </div>
              <h3 class="fw-extrabold text-info mb-1">{{ $siteHealth['disk_free'] }}</h3>
              <p class="text-muted small mb-1">Available free space (Total: {{ $siteHealth['disk_total'] }})</p>
              @if($siteHealth['disk_used_percent'] > 0)
                <div class="progress" style="height: 6px;">
                  <div class="progress-bar {{ $siteHealth['disk_used_percent'] > 85 ? 'bg-danger' : 'bg-info' }}" role="progressbar" style="width: {{ $siteHealth['disk_used_percent'] }}%"></div>
                </div>
              @endif
            </div>
          </div>

        </div>

        {{-- System Diagnostics & Checks --}}
        <div class="pt-3 mt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
          <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge bg-dark-subtle text-dark border px-2 py-1"><i class="bi bi-code-slash me-1"></i> PHP v{{ $siteHealth['php_version'] }}</span>
            <span class="badge bg-dark-subtle text-dark border px-2 py-1"><i class="bi bi-layers me-1"></i> Laravel v{{ $siteHealth['laravel_version'] }}</span>
            @foreach($siteHealth['checks'] as $check)
              @if($check['type'] === 'success')
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> {{ $check['message'] }}</span>
              @elseif($check['type'] === 'warning')
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $check['message'] }}</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1"><i class="bi bi-info-circle me-1"></i> {{ $check['message'] }}</span>
              @endif
            @endforeach
          </div>
          <div class="text-muted fs-8">
            <i class="bi bi-clock-history me-1"></i> Last updated: {{ $siteHealth['last_updated'] }}
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endif

{{-- Google Analytics Widget --}}
@if(!isset($analyticsDisabled))
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <span class="card-title"><i class="bi bi-google"></i> Google Analytics (Last 30 Days)</span>
                <a href="{{ route('site.settings.edit') }}#analytics" class="btn btn-sm btn-outline-secondary">Configure <i class="bi bi-gear"></i></a>
            </div>
            <div class="admin-card-body p-4">
                @if(empty(\App\Models\SiteSetting::current()->analytics_property_id))
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-bar-chart ms-2 fs-1 text-light"></i>
                        <h6 class="mt-2 mb-1">Analytics Not Configured</h6>
                        <p class="small mb-0">Please set your Google Analytics Property ID in the <a href="{{ route('site.settings.edit') }}">Site Settings</a> to view live data.</p>
                    </div>
                @elseif(isset($analyticsError) && $analyticsError)
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Analytics API Error:</strong> {{ $analyticsError }}
                    </div>
                @elseif(isset($analyticsData) && $analyticsData)
                    <div class="row text-center">
                        <div class="col-6 col-md-3 border-end">
                            <h2 class="fw-bold text-primary mb-1">{{ number_format($analyticsData['activeUsers']) }}</h2>
                            <p class="text-muted mb-0 text-uppercase small fw-bold tracking-wide">Active Users</p>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <h2 class="fw-bold text-success mb-1">{{ number_format($analyticsData['screenPageViews']) }}</h2>
                            <p class="text-muted mb-0 text-uppercase small fw-bold tracking-wide">Page Views</p>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <h2 class="fw-bold text-warning mb-1">{{ number_format($analyticsData['sessions']) }}</h2>
                            <p class="text-muted mb-0 text-uppercase small fw-bold tracking-wide">Sessions</p>
                        </div>
                        <div class="col-6 col-md-3">
                            <h2 class="fw-bold text-info mb-1">{{ number_format($analyticsData['newUsers']) }}</h2>
                            <p class="text-muted mb-0 text-uppercase small fw-bold tracking-wide">New Users</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-3">
  {{-- Insights Graph --}}
  <div class="col-lg-7">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <span class="card-title"><i class="bi bi-graph-up-arrow"></i> Admission Insights (Last 6 Months)</span>
      </div>
      <div class="admin-card-body">
        <canvas id="admissionsChart" height="250"></canvas>
      </div>
    </div>
  </div>

  {{-- Quick Actions (Draggable) --}}
  <div class="col-lg-5">
    <div class="admin-card h-100">
      <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span class="card-title"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</span>
        <small class="text-muted"><i class="bi bi-arrows-move"></i> Drag to reorder</small>
      </div>
      <div class="admin-card-body p-3">
        <div id="quickActionsGrid" class="row g-2">
          
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="add-course">
            <a href="{{ route('course.add') }}" class="quick-action-btn">
              <i class="bi bi-mortarboard text-success"></i>
              <span>Course</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="add-faculty">
            <a href="{{ route('teacher.add') }}" class="quick-action-btn">
              <i class="bi bi-person-plus text-primary"></i>
              <span>Faculty</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="post-notice">
            <a href="{{ route('notice.add') }}" class="quick-action-btn">
              <i class="bi bi-megaphone text-danger"></i>
              <span>Notice</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="create-event">
            <a href="{{ route('event.add') }}" class="quick-action-btn">
              <i class="bi bi-calendar-plus text-warning"></i>
              <span>Event</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="site-settings">
            <a href="{{ route('site.settings.edit') }}" class="quick-action-btn">
              <i class="bi bi-sliders2 text-info"></i>
              <span>Settings</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="home-layout">
            <a href="{{ route('home.sections.index') }}" class="quick-action-btn">
              <i class="bi bi-layout-text-window-reverse text-secondary"></i>
              <span>Layout</span>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- Add Chart.js and SortableJS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
  // Setup Chart
  const ctx = document.getElementById('admissionsChart').getContext('2d');
  new Chart(ctx, {
      type: 'line',
      data: {
          labels: {!! json_encode($chartLabels) !!},
          datasets: [{
              label: 'New Applications',
              data: {!! json_encode($chartData) !!},
              borderColor: '#1a4d8c',
              backgroundColor: 'rgba(26, 77, 140, 0.1)',
              borderWidth: 2,
              pointBackgroundColor: '#f59e0b',
              fill: true,
              tension: 0.4
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
              legend: { display: false }
          },
          scales: {
              y: { beginAtZero: true, ticks: { precision: 0 } }
          }
      }
  });

  // Setup Sortable Quick Actions
  const grid = document.getElementById('quickActionsGrid');
  
  // 1. Load order from localStorage
  const savedOrder = JSON.parse(localStorage.getItem('quickActionsOrder'));
  if (savedOrder && savedOrder.length > 0) {
      const items = Array.from(grid.children);
      savedOrder.forEach(id => {
          const item = items.find(el => el.dataset.id === id);
          if (item) grid.appendChild(item); // Reorder by appending
      });
  }

  // 2. Initialize SortableJS
  new Sortable(grid, {
      animation: 150,
      ghostClass: 'sortable-ghost',
      onEnd: function () {
          // Save new order to localStorage
          const newOrder = Array.from(grid.children).map(el => el.dataset.id);
          localStorage.setItem('quickActionsOrder', JSON.stringify(newOrder));
      }
  });
</script>
@endpush

{{-- Custom CSS for Quick Actions Grid --}}
@push('styles')
<style>
  .quick-action-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 15px 10px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      text-decoration: none;
      color: #334155;
      font-weight: 500;
      font-size: 13px;
      transition: all 0.2s;
      height: 100%;
      cursor: grab;
  }
  .quick-action-btn:hover {
      background: #fff;
      border-color: #cbd5e1;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
  }
  .quick-action-btn i {
      font-size: 24px;
      margin-bottom: 8px;
  }
  .sortable-ghost {
      opacity: 0.4;
      background: #e2e8f0;
  }
</style>
@endpush
@endsection
