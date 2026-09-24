<footer class="admin-footer">
  @php($footerSettings = \App\Models\SiteSetting::current())
  <span>&copy; {{ date('Y') }} {{ $footerSettings->site_name ?? 'Shiksha Sandesh English School' }}. All rights reserved.</span>
  <span>Powered by <strong>nstudios</strong></span>
</footer>
