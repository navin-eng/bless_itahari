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

<!-- Header Row -->
<div class="dash-welcome-card mb-4 p-4 rounded-4 text-white position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(255,255,255,0.08);">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 position-relative" style="z-index: 2;">
    <div>
      <div class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2 px-3 py-1 rounded-pill">
        <i class="bi bi-speedometer2 me-1"></i> Admin Command Center
      </div>
      <h2 class="dash-greeting fw-extrabold mb-1 fs-3">Welcome back, {{ Auth::user()->name }} 👋</h2>
      <p class="text-white-50 mb-0 small">Overview of site health, metrics, admissions, and quick management controls.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <form action="{{ route('admin.clear-cache') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-light font-weight-bold shadow-sm rounded-3">
          <i class="bi bi-arrow-clockwise text-primary me-1"></i> Purge Cache
        </button>
      </form>
      <a href="{{ route('site.settings.edit') }}" class="btn btn-sm btn-primary rounded-3">
        <i class="bi bi-gear-fill me-1"></i> Settings
      </a>
    </div>
  </div>
</div>

<!-- KPI Mini Stat Cards Grid -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-4 col-xl-2-4">
    <a href="{{ route('admin.admissions.index') }}" class="kpi-mini-card shadow-sm text-decoration-none">
      <div class="kpi-icon bg-primary-subtle text-primary"><i class="bi bi-inbox-fill"></i></div>
      <div class="kpi-info">
        <div class="kpi-value text-dark">{{ $admissions }}</div>
        <div class="kpi-title text-muted">Admissions</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2-4">
    <a href="{{ route('course.table') }}" class="kpi-mini-card shadow-sm text-decoration-none">
      <div class="kpi-icon bg-success-subtle text-success"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="kpi-info">
        <div class="kpi-value text-dark">{{ $courses }}</div>
        <div class="kpi-title text-muted">Courses</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2-4">
    <a href="{{ route('teacher.table') }}" class="kpi-mini-card shadow-sm text-decoration-none">
      <div class="kpi-icon bg-info-subtle text-info"><i class="bi bi-person-badge-fill"></i></div>
      <div class="kpi-info">
        <div class="kpi-value text-dark">{{ $teachers }}</div>
        <div class="kpi-title text-muted">Faculty</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2-4">
    <a href="{{ route('event.table') }}" class="kpi-mini-card shadow-sm text-decoration-none">
      <div class="kpi-icon bg-warning-subtle text-warning"><i class="bi bi-calendar2-event-fill"></i></div>
      <div class="kpi-info">
        <div class="kpi-value text-dark">{{ $events }}</div>
        <div class="kpi-title text-muted">Events</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2-4">
    <a href="{{ route('notice.table') }}" class="kpi-mini-card shadow-sm text-decoration-none">
      <div class="kpi-icon bg-danger-subtle text-danger"><i class="bi bi-bell-fill"></i></div>
      <div class="kpi-info">
        <div class="kpi-value text-dark">{{ $notices }}</div>
        <div class="kpi-title text-muted">Notices</div>
      </div>
    </a>
  </div>
</div>

<!-- Main Cards Grid (2 Equal Columns) -->
<div class="row g-4 mb-4">
  
  <!-- Left Card: Site Health & Storage Monitor -->
  @if(isset($siteHealth))
  <div class="col-lg-6">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-success-subtle text-success p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-heart-pulse-fill"></i>
          </div>
          <h6 class="mb-0 fw-bold text-dark">Site Health & Storage Monitor</h6>
        </div>
        <span class="badge {{ $siteHealth['health_score'] >= 90 ? 'bg-success' : ($siteHealth['health_score'] >= 70 ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill px-3 py-1 fs-7">
          Health: {{ $siteHealth['health_score'] }}%
        </span>
      </div>
      <div class="card-body p-4">
        
        <!-- 4 Storage Metric Tiles -->
        <div class="row g-3 mb-3">
          <div class="col-6">
            <div class="metric-tile p-3 rounded-3 bg-light border">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fs-8 fw-bold text-muted text-uppercase">Images</span>
                <i class="bi bi-images text-primary"></i>
              </div>
              <div class="fs-5 fw-bold text-primary">{{ $siteHealth['image_size'] }}</div>
              <div class="fs-8 text-muted">{{ $siteHealth['image_count'] }} files stored</div>
            </div>
          </div>
          <div class="col-6">
            <div class="metric-tile p-3 rounded-3 bg-light border">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fs-8 fw-bold text-muted text-uppercase">Documents</span>
                <i class="bi bi-file-earmark-pdf text-danger"></i>
              </div>
              <div class="fs-5 fw-bold text-danger">{{ $siteHealth['doc_size'] }}</div>
              <div class="fs-8 text-muted">{{ $siteHealth['doc_count'] }} files stored</div>
            </div>
          </div>
          <div class="col-6">
            <div class="metric-tile p-3 rounded-3 bg-light border">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fs-8 fw-bold text-muted text-uppercase">Database</span>
                <i class="bi bi-database text-warning"></i>
              </div>
              <div class="fs-5 fw-bold text-warning">{{ $siteHealth['db_size'] }}</div>
              <div class="fs-8 text-muted">MySQL Indexes & Tables</div>
            </div>
          </div>
          <div class="col-6">
            <div class="metric-tile p-3 rounded-3 bg-light border">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fs-8 fw-bold text-muted text-uppercase">Server Disk</span>
                <i class="bi bi-hdd-network text-info"></i>
              </div>
              <div class="fs-5 fw-bold text-info">{{ $siteHealth['disk_free'] }}</div>
              <div class="fs-8 text-muted">Free space ({{ $siteHealth['disk_used_percent'] }}% used)</div>
            </div>
          </div>
        </div>

        <!-- Health Badges & Checks -->
        <div class="pt-2 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
          <div class="d-flex flex-wrap align-items-center gap-1">
            <span class="badge bg-light text-dark border"><i class="bi bi-code-slash me-1 text-secondary"></i> PHP {{ $siteHealth['php_version'] }}</span>
            <span class="badge bg-light text-dark border"><i class="bi bi-layers me-1 text-secondary"></i> Laravel {{ $siteHealth['laravel_version'] }}</span>
            @foreach($siteHealth['checks'] as $check)
              @if($check['type'] === 'success')
                <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle-fill me-1"></i> {{ $check['message'] }}</span>
              @elseif($check['type'] === 'warning')
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $check['message'] }}</span>
              @endif
            @endforeach
          </div>
          <small class="text-muted fs-8"><i class="bi bi-clock me-1"></i> Scan: {{ $siteHealth['last_updated'] }}</small>
        </div>

      </div>
    </div>
  </div>
  @endif

  <!-- Right Card: Admission Insights Graph -->
  <div class="col-lg-6">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-primary-subtle text-primary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
          <h6 class="mb-0 fw-bold text-dark">Admission Insights (Last 6 Months)</h6>
        </div>
        <a href="{{ route('admin.admissions.index') }}" class="btn btn-xs btn-outline-primary rounded-pill px-3">View All</a>
      </div>
      <div class="card-body p-4 d-flex align-items-center justify-content-center">
        <div style="width: 100%; height: 215px;">
          <canvas id="admissionsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Bottom Grid: Quick Actions & Analytics -->
<div class="row g-4 mb-4">
  
  <!-- Left Card: Quick Actions Grid -->
  <div class="col-lg-6">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-warning-subtle text-warning p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-lightning-charge-fill"></i>
          </div>
          <h6 class="mb-0 fw-bold text-dark">Quick Actions</h6>
        </div>
        <small class="text-muted fs-8"><i class="bi bi-arrows-move me-1"></i> Drag to reorder</small>
      </div>
      <div class="card-body p-3">
        <div id="quickActionsGrid" class="row g-2">
          
          <div class="col-4" data-id="add-course">
            <a href="{{ route('course.add') }}" class="quick-action-card">
              <div class="qa-icon bg-success-subtle text-success"><i class="bi bi-mortarboard-fill"></i></div>
              <span class="qa-label">Course</span>
            </a>
          </div>
          <div class="col-4" data-id="add-faculty">
            <a href="{{ route('teacher.add') }}" class="quick-action-card">
              <div class="qa-icon bg-primary-subtle text-primary"><i class="bi bi-person-plus-fill"></i></div>
              <span class="qa-label">Faculty</span>
            </a>
          </div>
          <div class="col-4" data-id="post-notice">
            <a href="{{ route('notice.add') }}" class="quick-action-card">
              <div class="qa-icon bg-danger-subtle text-danger"><i class="bi bi-megaphone-fill"></i></div>
              <span class="qa-label">Notice</span>
            </a>
          </div>
          <div class="col-4" data-id="create-event">
            <a href="{{ route('event.add') }}" class="quick-action-card">
              <div class="qa-icon bg-warning-subtle text-warning"><i class="bi bi-calendar-plus-fill"></i></div>
              <span class="qa-label">Event</span>
            </a>
          </div>
          <div class="col-4" data-id="site-settings">
            <a href="{{ route('site.settings.edit') }}" class="quick-action-card">
              <div class="qa-icon bg-info-subtle text-info"><i class="bi bi-sliders2"></i></div>
              <span class="qa-label">Settings</span>
            </a>
          </div>
          <div class="col-4" data-id="home-layout">
            <a href="{{ route('home.sections.index') }}" class="quick-action-card">
              <div class="qa-icon bg-secondary-subtle text-secondary"><i class="bi bi-layout-text-window-reverse"></i></div>
              <span class="qa-label">Layout</span>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Right Card: Google Analytics / Traffic Overview -->
  <div class="col-lg-6">
    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-danger-subtle text-danger p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
            <i class="bi bi-google"></i>
          </div>
          <h6 class="mb-0 fw-bold text-dark">Google Analytics (30 Days)</h6>
        </div>
        <a href="{{ route('site.settings.edit') }}#analytics" class="btn btn-xs btn-outline-secondary rounded-pill px-3">Configure</a>
      </div>
      <div class="card-body p-4 d-flex align-items-center">
        @if(!isset($analyticsDisabled) && isset($analyticsData) && $analyticsData)
          <div class="row g-3 w-100 text-center">
            <div class="col-6">
              <div class="p-3 bg-light rounded-3 border">
                <h3 class="fw-bold text-primary mb-1">{{ number_format($analyticsData['activeUsers']) }}</h3>
                <span class="fs-8 text-muted text-uppercase fw-semibold">Active Users</span>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-light rounded-3 border">
                <h3 class="fw-bold text-success mb-1">{{ number_format($analyticsData['screenPageViews']) }}</h3>
                <span class="fs-8 text-muted text-uppercase fw-semibold">Page Views</span>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-light rounded-3 border">
                <h3 class="fw-bold text-warning mb-1">{{ number_format($analyticsData['sessions']) }}</h3>
                <span class="fs-8 text-muted text-uppercase fw-semibold">Sessions</span>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-light rounded-3 border">
                <h3 class="fw-bold text-info mb-1">{{ number_format($analyticsData['newUsers']) }}</h3>
                <span class="fs-8 text-muted text-uppercase fw-semibold">New Users</span>
              </div>
            </div>
          </div>
        @else
          <div class="w-100 text-center py-3 text-muted">
            <i class="bi bi-bar-chart fs-1 text-light"></i>
            <h6 class="mt-2 mb-1 fs-6 text-dark">Analytics Widget Ready</h6>
            <p class="small mb-0 text-muted">Set your GA Property ID in <a href="{{ route('site.settings.edit') }}">Site Settings</a> to view live metrics.</p>
          </div>
        @endif
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
              label: 'Applications',
              data: {!! json_encode($chartData) !!},
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37, 99, 235, 0.08)',
              borderWidth: 2.5,
              pointBackgroundColor: '#f59e0b',
              pointRadius: 4,
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
              x: { grid: { display: false } },
              y: { beginAtZero: true, ticks: { precision: 0 } }
          }
      }
  });

  // Setup Sortable Quick Actions
  const grid = document.getElementById('quickActionsGrid');
  
  const savedOrder = JSON.parse(localStorage.getItem('quickActionsOrder'));
  if (savedOrder && savedOrder.length > 0) {
      const items = Array.from(grid.children);
      savedOrder.forEach(id => {
          const item = items.find(el => el.dataset.id === id);
          if (item) grid.appendChild(item);
      });
  }

  new Sortable(grid, {
      animation: 150,
      ghostClass: 'sortable-ghost',
      onEnd: function () {
          const newOrder = Array.from(grid.children).map(el => el.dataset.id);
          localStorage.setItem('quickActionsOrder', JSON.stringify(newOrder));
      }
  });
</script>
@endpush

@push('styles')
<style>
  /* 5 Column Grid for KPI Mini Cards */
  @media (min-width: 1200px) {
    .col-xl-2-4 {
      flex: 0 0 auto;
      width: 20%;
    }
  }

  .kpi-mini-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.25s ease-in-out;
  }
  .kpi-mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08) !important;
    border-color: #cbd5e1;
  }
  .kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .kpi-value {
    font-size: 20px;
    font-weight: 800;
    line-height: 1.2;
  }
  .kpi-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }

  /* Quick Action Grid Card */
  .quick-action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    text-decoration: none;
    color: #1e293b;
    transition: all 0.2s ease;
    height: 100%;
    cursor: grab;
  }
  .quick-action-card:hover {
    background: #ffffff;
    border-color: #94a3b8;
    box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
    color: #0f172a;
  }
  .qa-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 8px;
  }
  .qa-label {
    font-size: 13px;
    font-weight: 600;
  }
  .sortable-ghost {
    opacity: 0.4;
    background: #cbd5e1;
  }
  .fs-7 { font-size: 0.825rem; }
  .fs-8 { font-size: 0.75rem; }
  .btn-xs { padding: 0.25rem 0.6rem; font-size: 0.75rem; }
</style>
@endpush
@endsection
