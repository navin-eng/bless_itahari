<aside class="admin-sidebar" id="adminSidebar">
  @php
    $sidebarSettings = \App\Models\SiteSetting::current();
  @endphp

  {{-- Logo Header --}}
  <div class="sb-header">
    <a href="{{ url('admin/dashboard') }}" class="sb-logo">
      <div class="sb-logo-icon">
        <img src="{{ $sidebarSettings->site_logo ? asset($sidebarSettings->site_logo) : asset('backend/images/logo.png') }}" alt="{{ $sidebarSettings->site_name ?? 'BLESS' }}">
      </div>
      <div class="sb-logo-text">
        <span class="sb-name">{{ $sidebarSettings->site_short_name ?? 'BLESS' }} Admin</span>
        <span class="sb-status"><span class="status-dot"></span> System Active</span>
      </div>
    </a>
  </div>

  {{-- Sidebar Search --}}
  <div class="sb-search-wrap">
    <div class="sb-search-box">
      <i class="bi bi-search sb-search-icon"></i>
      <input type="text" id="sbSearchInput" class="sb-search-input" placeholder="Quick Search...">
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="sb-nav">
    <ul class="sb-menu">

      {{-- Overview --}}
      <li class="sb-group-label">Overview</li>
      <li class="sb-item">
        <a href="{{ url('admin/dashboard') }}" class="sb-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" title="Dashboard">
          <div class="sb-icon-box"><i class="bi bi-grid-1x2-fill"></i></div>
          <span class="sb-text">Dashboard</span>
        </a>
      </li>

      {{-- Academics --}}
      <li class="sb-group-label">Academics</li>
      <li class="sb-item">
        <a href="{{ route('course.table') }}" class="sb-link {{ request()->routeIs('course.*') ? 'active' : '' }}" title="Courses">
          <div class="sb-icon-box"><i class="bi bi-mortarboard-fill"></i></div>
          <span class="sb-text">Courses</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('teacher.table') }}" class="sb-link {{ request()->routeIs('teacher.*') ? 'active' : '' }}" title="Faculty">
          <div class="sb-icon-box"><i class="bi bi-people-fill"></i></div>
          <span class="sb-text">Faculty</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('admin.admissions.index') }}" class="sb-link {{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}" title="Admissions">
          <div class="sb-icon-box"><i class="bi bi-inbox-fill"></i></div>
          <span class="sb-text">Admissions</span>
        </a>
      </li>

      {{-- Content Management --}}
      <li class="sb-group-label">Management</li>
      <li class="sb-item">
        <a href="{{ route('notice.table') }}" class="sb-link {{ request()->routeIs('notice.*') ? 'active' : '' }}" title="Notices">
          <div class="sb-icon-box"><i class="bi bi-bell-fill"></i></div>
          <span class="sb-text">Notices</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('event.table') }}" class="sb-link {{ request()->routeIs('event.*') ? 'active' : '' }}" title="Events">
          <div class="sb-icon-box"><i class="bi bi-calendar2-event-fill"></i></div>
          <span class="sb-text">Events</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('gallery.table') }}" class="sb-link {{ request()->routeIs('gallery.table') ? 'active' : '' }}" title="Gallery">
          <div class="sb-icon-box"><i class="bi bi-grid-3x3-gap-fill"></i></div>
          <span class="sb-text">Gallery</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('testimonial.table') }}" class="sb-link {{ request()->routeIs('testimonial.*') ? 'active' : '' }}" title="Testimonials">
          <div class="sb-icon-box"><i class="bi bi-chat-quote-fill"></i></div>
          <span class="sb-text">Testimonials</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('banner.table') }}" class="sb-link {{ request()->routeIs('banner.*') ? 'active' : '' }}" title="Banners">
          <div class="sb-icon-box"><i class="bi bi-image-fill"></i></div>
          <span class="sb-text">Banners</span>
        </a>
      </li>

      {{-- Pages & Menus --}}
      <li class="sb-group-label">Pages & Site</li>
      <li class="sb-item">
        <a href="{{ route('page.table') }}" class="sb-link {{ request()->is('admin/dashboard/page*') ? 'active' : '' }}" title="HTML Pages">
          <div class="sb-icon-box"><i class="bi bi-file-earmark-code-fill"></i></div>
          <span class="sb-text">HTML Pages</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('navbar_menu.table') }}" class="sb-link {{ request()->is('admin/dashboard/navbar-menu*') ? 'active' : '' }}" title="Navbar Menu">
          <div class="sb-icon-box"><i class="bi bi-menu-button-wide-fill"></i></div>
          <span class="sb-text">Navbar Menu</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('college_message.table') }}" class="sb-link {{ request()->is('admin/dashboard/college-message*') ? 'active' : '' }}" title="College Messages">
          <div class="sb-icon-box"><i class="bi bi-person-lines-fill"></i></div>
          <span class="sb-text">College Messages</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('message.index') }}" class="sb-link {{ request()->routeIs('message.index') ? 'active' : '' }}" title="Visitor Messages">
          <div class="sb-icon-box"><i class="bi bi-envelope-open-fill"></i></div>
          <span class="sb-text">Visitor Messages</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('aboutus.add') }}" class="sb-link {{ request()->routeIs('aboutus.add') ? 'active' : '' }}" title="About Us Page">
          <div class="sb-icon-box"><i class="bi bi-building"></i></div>
          <span class="sb-text">About Us Page</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('privacy.add') }}" class="sb-link {{ request()->routeIs('privacy.add') ? 'active' : '' }}" title="Privacy Policy Page">
          <div class="sb-icon-box"><i class="bi bi-shield-check"></i></div>
          <span class="sb-text">Privacy Policy</span>
        </a>
      </li>

      {{-- Settings --}}
      <li class="sb-group-label">Settings</li>
      <li class="sb-item">
        <a href="{{ route('site.settings.edit') }}" class="sb-link {{ request()->routeIs('site.settings.edit') ? 'active' : '' }}" title="Site Settings">
          <div class="sb-icon-box"><i class="bi bi-sliders2"></i></div>
          <span class="sb-text">Site Settings</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('home.sections.index') }}" class="sb-link {{ request()->routeIs('home.sections.index') ? 'active' : '' }}" title="Homepage Layout">
          <div class="sb-icon-box"><i class="bi bi-layout-text-window-reverse"></i></div>
          <span class="sb-text">Homepage Layout</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('counter.table') }}" class="sb-link {{ request()->routeIs('counter.table') ? 'active' : '' }}" title="Stats Counter">
          <div class="sb-icon-box"><i class="bi bi-bar-chart-fill"></i></div>
          <span class="sb-text">Stats Counter</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('editor.table') }}" class="sb-link {{ request()->routeIs('editor.table') ? 'active' : '' }}" title="Editors / Users">
          <div class="sb-icon-box"><i class="bi bi-people-fill"></i></div>
          <span class="sb-text">Editors / Users</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('admin.profile') }}" class="sb-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" title="My Profile">
          <div class="sb-icon-box"><i class="bi bi-person-circle"></i></div>
          <span class="sb-text">My Profile</span>
        </a>
      </li>

    </ul>
  </nav>

  {{-- Sidebar footer --}}
  <div class="sb-footer">
    <div class="sb-user-card">
      <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
          <div class="sb-user-avatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
          </div>
          <div class="sb-user-info text-truncate">
            <div class="sb-user-name text-truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
            <div class="sb-user-role">Administrator</div>
          </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="m-0 p-0">
          @csrf
          <button type="submit" class="sb-logout-btn" title="Sign Out">
            <i class="bi bi-box-arrow-right"></i>
          </button>
        </form>
      </div>
    </div>
  </div>

</aside>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sbSearchInput');
    if(searchInput) {
      searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const items = document.querySelectorAll('.sb-nav .sb-item');
        const groups = document.querySelectorAll('.sb-nav .sb-group-label');

        items.forEach(item => {
          const text = item.textContent || item.innerText;
          if (text.toLowerCase().indexOf(filter) > -1) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });

        groups.forEach(group => {
          group.style.display = filter.length > 0 ? 'none' : '';
        });
      });
    }
  });
</script>
