<aside class="admin-sidebar" id="adminSidebar">
  @php($sidebarSettings = \App\Models\SiteSetting::current())

  {{-- Logo --}}
  <a href="{{ url('admin/dashboard') }}" class="sb-logo">
    <img src="{{ $sidebarSettings->site_logo ? asset($sidebarSettings->site_logo) : asset('backend/images/logo.png') }}" alt="{{ $sidebarSettings->site_name ?? 'GPLC' }}">
    <div class="sb-logo-text">
      <span class="sb-name">{{ $sidebarSettings->site_short_name ?? 'SSES' }} Admin</span>
      <span class="sb-sub">{{ $sidebarSettings->site_name ?? 'Shiksha Sandesh English School' }}</span>
    </div>
  </a>

  {{-- Sidebar Search --}}
  <div class="sb-search-wrap" style="padding: 10px 20px;">
    <div style="position: relative;">
      <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;"></i>
      <input type="text" id="sbSearchInput" class="form-control" placeholder="Search menus..." style="padding-left: 35px; border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="sb-nav">
    <ul class="sb-menu">

      {{-- Overview --}}
      <li class="sb-group-label"><span class="sb-text">Dashboard</span></li>
      <li class="sb-item">
        <a href="{{ url('admin/dashboard') }}" class="sb-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" title="Dashboard">
          <i class="bi bi-grid-1x2-fill"></i><span class="sb-text">Dashboard</span>
        </a>
      </li>

      {{-- Academics --}}
      <li class="sb-group-label"><span class="sb-text">Academics</span></li>
      <li class="sb-item">
        <a href="{{ route('course.table') }}" class="sb-link {{ request()->routeIs('course.*') ? 'active' : '' }}" title="Courses">
          <i class="bi bi-mortarboard-fill"></i><span class="sb-text">Courses</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('teacher.table') }}" class="sb-link {{ request()->routeIs('teacher.*') ? 'active' : '' }}" title="Faculty">
          <i class="bi bi-people-fill"></i><span class="sb-text">Faculty</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('admin.admissions.index') }}" class="sb-link {{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}" title="Admissions">
          <i class="bi bi-inbox-fill"></i><span class="sb-text">Admissions</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('campus.calendar.index') }}" class="sb-link {{ request()->routeIs('campus.calendar.*') ? 'active' : '' }}" title="Calendar">
          <i class="bi bi-calendar3"></i><span class="sb-text">Calendar</span>
        </a>
      </li>

      {{-- Content Management --}}
      <li class="sb-group-label"><span class="sb-text">Content Management</span></li>
      <li class="sb-item">
        <a href="{{ route('notice.table') }}" class="sb-link {{ request()->routeIs('notice.*') ? 'active' : '' }}" title="Notices">
          <i class="bi bi-bell-fill"></i><span class="sb-text">Notices</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('event.table') }}" class="sb-link {{ request()->routeIs('event.*') ? 'active' : '' }}" title="Events">
          <i class="bi bi-calendar2-event-fill"></i><span class="sb-text">Events</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('gallery.table') }}" class="sb-link {{ request()->routeIs('gallery.table') ? 'active' : '' }}" title="Gallery">
          <i class="bi bi-grid-3x3-gap-fill"></i><span class="sb-text">Gallery</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('testimonial.table') }}" class="sb-link {{ request()->routeIs('testimonial.*') ? 'active' : '' }}" title="Testimonials">
          <i class="bi bi-chat-quote-fill"></i><span class="sb-text">Testimonials</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('banner.table') }}" class="sb-link {{ request()->routeIs('banner.*') ? 'active' : '' }}" title="Banners">
          <i class="bi bi-image-fill"></i><span class="sb-text">Banners</span>
        </a>
      </li>

      {{-- Pages & Menus --}}
      <li class="sb-group-label"><span class="sb-text">Pages & Menus</span></li>
      <li class="sb-item">
        <a href="{{ route('page.table') }}" class="sb-link {{ request()->is('admin/dashboard/page*') ? 'active' : '' }}" title="HTML Pages">
          <i class="bi bi-file-earmark-code-fill"></i><span class="sb-text">HTML Pages</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('navbar_menu.table') }}" class="sb-link {{ request()->is('admin/dashboard/navbar-menu*') ? 'active' : '' }}" title="Navbar Menu">
          <i class="bi bi-menu-button-wide-fill"></i><span class="sb-text">Navbar Menu</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('college_message.table') }}" class="sb-link {{ request()->is('admin/dashboard/college-message*') ? 'active' : '' }}" title="College Messages">
          <i class="bi bi-person-lines-fill"></i><span class="sb-text">College Messages</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('message.index') }}" class="sb-link {{ request()->routeIs('message.index') ? 'active' : '' }}" title="Visitor Messages">
          <i class="bi bi-envelope-open-fill"></i><span class="sb-text">Visitor Messages</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('aboutus.add') }}" class="sb-link {{ request()->routeIs('aboutus.add') ? 'active' : '' }}" title="About Us Page">
          <i class="bi bi-building"></i><span class="sb-text">About Us Page</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('privacy.add') }}" class="sb-link {{ request()->routeIs('privacy.add') ? 'active' : '' }}" title="Privacy Policy Page">
          <i class="bi bi-shield-check"></i><span class="sb-text">Privacy Policy Page</span>
        </a>
      </li>

      {{-- System & Settings --}}
      <li class="sb-group-label"><span class="sb-text">System & Settings</span></li>
      <li class="sb-item">
        <a href="{{ route('site.settings.edit') }}" class="sb-link {{ request()->routeIs('site.settings.edit') ? 'active' : '' }}" title="Site Settings">
          <i class="bi bi-sliders2"></i><span class="sb-text">Site Settings</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('home.sections.index') }}" class="sb-link {{ request()->routeIs('home.sections.index') ? 'active' : '' }}" title="Homepage Layout">
          <i class="bi bi-layout-text-window-reverse"></i><span class="sb-text">Homepage Layout</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('counter.table') }}" class="sb-link {{ request()->routeIs('counter.table') ? 'active' : '' }}" title="Stats Counter">
          <i class="bi bi-bar-chart-fill"></i><span class="sb-text">Stats Counter</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('editor.table') }}" class="sb-link {{ request()->routeIs('editor.table') ? 'active' : '' }}" title="Editors / Users">
          <i class="bi bi-people-fill"></i><span class="sb-text">Editors / Users</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('admin.profile') }}" class="sb-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" title="My Profile">
          <i class="bi bi-person-circle"></i><span class="sb-text">My Profile</span>
        </a>
      </li>

    </ul>
  </nav>

  {{-- Sidebar footer --}}
  <div class="sb-footer">
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="sb-link" title="Sign Out" style="background:none;border:none;cursor:pointer;width:100%;text-align:left;">
        <i class="bi bi-box-arrow-left"></i><span class="sb-text">Sign Out</span>
      </button>
    </form>
  </div>

</aside>

<script>
  // Sidebar Search Logic
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
            // If inside a collapse, ensure parent is visible
            const collapseParent = item.closest('.collapse');
            if(collapseParent && filter.length > 0) {
              collapseParent.classList.add('show');
              // Make sure the toggle button parent is also visible
              const toggleParent = collapseParent.closest('.sb-item');
              if(toggleParent) toggleParent.style.display = '';
            }
          } else {
            item.style.display = 'none';
          }
        });

        // Optional: hide group labels if all items inside are hidden
        // Simple approach: just keep labels visible, or hide if search active
        groups.forEach(group => {
            group.style.display = filter.length > 0 ? 'none' : '';
        });
      });
    }
  });
</script>
