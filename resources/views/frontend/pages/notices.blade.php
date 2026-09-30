@extends('frontend.layout.master')

@section('title', 'Notices & Announcements')
@section('meta_description', 'Stay updated with the latest official announcements, circulars, exam schedules, and news from our school.')

@section('frontend-content')

<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>All Notices</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Notices
            </nav>
        </div>
    </div>
</div>

<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Updates</span>
            <h2 class="section-title mt-2">Notice Board</h2>
            <div class="section-divider center"></div>
            <p class="text-muted mt-3 mb-0">Search announcements, check PDF documents, filter active notices, and view official school updates.</p>
        </div>

        <div class="notice-board-shell" data-aos="fade-up" data-aos-delay="100">
            <div class="notice-toolbar">
                <input type="search" id="noticeSearch" class="form-control" placeholder="Search notices by title...">
                <select id="noticeStatusFilter" class="form-control">
                    <option value="all">All Statuses</option>
                    <option value="active" selected>Active Only</option>
                    <option value="expired">Expired Notices</option>
                </select>
                <select id="noticeFilter" class="form-control">
                    <option value="all">All Placement Types</option>
                    <option value="m">Marquee</option>
                    <option value="p">Popup</option>
                    <option value="b">Board Only</option>
                </select>
                <select id="noticeSort" class="form-control">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="title">Title A-Z</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="noticeTable">
                    <thead>
                        <tr>
                            <th>Notice Title & Preview</th>
                            <th>Attachment</th>
                            <th>Placement</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notices as $notice)
                            <tr data-title="{{ strtolower($notice->title) }}" 
                                data-type="{{ $notice->show_in ?? 'b' }}" 
                                data-status="{{ $notice->isExpired() ? 'expired' : 'active' }}" 
                                data-date="{{ optional($notice->created_at)->timestamp ?? 0 }}">
                                <td>
                                    <div class="notice-table-title">
                                        @if($notice->image)
                                            <img src="{{ asset($notice->image) }}" alt="{{ $notice->title }}">
                                        @else
                                            <div class="notice-placeholder-icon">
                                                <i class="fa-solid fa-bullhorn text-primary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ url('notice/detail/' . $notice->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                                {{ $notice->title }}
                                            </a>
                                            <p class="mb-0 text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 85) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($notice->hasFile())
                                        @if($notice->isPdf())
                                            <a href="{{ url('notice/detail/' . $notice->id) }}#pdfViewerContainer" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none px-2 py-1.5" title="View attached PDF">
                                                <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                            </a>
                                        @else
                                            <a href="{{ asset($notice->file) }}" download class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1.5" title="Download attachment">
                                                <i class="fa-solid fa-paperclip me-1"></i> File
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted small">&mdash;</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $notice->show_in === 'p' ? 'bg-warning text-dark' : ($notice->show_in === 'm' ? 'bg-success' : 'bg-secondary') }}">
                                        {{ $notice->show_in === 'p' ? 'Popup' : ($notice->show_in === 'm' ? 'Marquee' : 'Board') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-dark"><i class="fa-regular fa-calendar-check me-1 text-muted"></i>{{ format_system_date($notice->created_at) }}</span>
                                        @if($notice->expires_at)
                                            <div class="text-muted mt-1" style="font-size: 11px;">
                                                <i class="fa-solid fa-hourglass-half me-1"></i>Exp: {{ format_system_date($notice->expires_at) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($notice->isExpired())
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Expired
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="fa-solid fa-circle-check me-1"></i> Active
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('notice/detail/' . $notice->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-bell-slash fs-2 mb-2 d-block text-secondary opacity-50"></i>
                                    No notices published yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .notice-board-shell {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
        }
        .notice-toolbar {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }
        .notice-table-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .notice-table-title img {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #e5e7eb;
        }
        .notice-placeholder-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(26, 77, 140, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.25rem;
        }
        .hover-primary:hover {
            color: var(--primary) !important;
        }
        @media (max-width: 991px) {
            .notice-toolbar {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 576px) {
            .notice-toolbar {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const search = document.getElementById('noticeSearch');
            const statusFilter = document.getElementById('noticeStatusFilter');
            const filter = document.getElementById('noticeFilter');
            const sort = document.getElementById('noticeSort');
            const tbody = document.querySelector('#noticeTable tbody');
            if (!search || !statusFilter || !filter || !sort || !tbody) return;

            const applyNoticeFilters = () => {
                const query = search.value.trim().toLowerCase();
                const type = filter.value;
                const status = statusFilter.value;
                const rows = [...tbody.querySelectorAll('tr[data-title]')];

                rows.forEach((row) => {
                    const title = row.dataset.title || '';
                    const rowType = row.dataset.type || '';
                    const rowStatus = row.dataset.status || '';

                    const matchesQuery = !query || title.includes(query);
                    const matchesType = type === 'all' || rowType === type;
                    const matchesStatus = status === 'all' || rowStatus === status;

                    row.style.display = matchesQuery && matchesType && matchesStatus ? '' : 'none';
                });

                const visibleRows = rows.filter((row) => row.style.display !== 'none');
                visibleRows.sort((a, b) => {
                    if (sort.value === 'title') {
                        return (a.dataset.title || '').localeCompare(b.dataset.title || '');
                    }
                    if (sort.value === 'oldest') {
                        return Number(a.dataset.date) - Number(b.dataset.date);
                    }
                    return Number(b.dataset.date) - Number(a.dataset.date);
                });

                visibleRows.forEach((row) => tbody.appendChild(row));
            };

            search.addEventListener('input', applyNoticeFilters);
            statusFilter.addEventListener('change', applyNoticeFilters);
            filter.addEventListener('change', applyNoticeFilters);
            sort.addEventListener('change', applyNoticeFilters);

            // Run initial filter on load (applies Active by default)
            applyNoticeFilters();
        })();
    </script>
@endpush

@endsection
