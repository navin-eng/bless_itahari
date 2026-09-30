@extends('frontend.layout.master')

@section('title', 'सूचनाहरू (Notices & Circulars)')
@section('meta_description', 'Official notices, circulars, exam routines, and announcements from Blooming Lotus English Secondary School.')

@section('frontend-content')

<div class="notice-portal-wrapper">
    <div class="container py-4 py-md-5">

        {{-- Top Header Section Matching Screenshot --}}
        <div class="notice-portal-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="notice-portal-title mb-1">सूचना</h1>
                <nav class="notice-breadcrumb" aria-label="breadcrumb">
                    <a href="{{ route('home') }}">गृहपृष्ठ</a>
                    <span class="divider">/</span>
                    <span class="active">सूचनाहरू (Notices)</span>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                {{-- Mobile View Mode Switcher --}}
                <div class="btn-group btn-group-sm view-mode-toggle d-md-none" role="group">
                    <button type="button" class="btn btn-outline-secondary active" id="btnTableView" title="तालिका (Table View)">
                        <i class="fa-solid fa-table-list"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnCardView" title="कार्ड (Card View)">
                        <i class="fa-solid fa-grip"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btnResetFilters" title="फिल्टर रिसेट">
                    <i class="fa-solid fa-arrows-rotate me-1"></i> रिसेट
                </button>
            </div>
        </div>

        {{-- Filter Box (Matching Screenshot Toolbar) --}}
        <div class="notice-filter-card mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group input-group-sm notice-input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-regular fa-calendar"></i></span>
                        <input type="text" id="filterDateFrom" class="form-control border-start-0" placeholder="मिति देखि (YYYY-MM-DD)" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group input-group-sm notice-input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-regular fa-calendar-check"></i></span>
                        <input type="text" id="filterDateTo" class="form-control border-start-0" placeholder="मिति सम्म (YYYY-MM-DD)" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="notice-input-group">
                        <select id="filterBranch" class="form-select form-select-sm">
                            <option value="all">सम्बन्धित शाखा (सबै)</option>
                            <option value="academic">शैक्षिक तथा परीक्षा शाखा</option>
                            <option value="administration">प्रशासन महाशाखा</option>
                            <option value="admission">भर्ना शाखा</option>
                            <option value="active">सक्रिय सूचनाहरू मात्र</option>
                            <option value="expired">म्याद सकिएका सूचनाहरू</option>
                            <option value="has_file">कागजात / PDF भएका सूचना</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group input-group-sm notice-input-group">
                        <input type="search" id="filterSearch" class="form-control border-end-0" placeholder="सूचना खोज्नुहोस...">
                        <span class="input-group-text bg-white border-start-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sub-bar with Per Page and Active Filter Summary --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 px-1">
            <div class="small text-muted" id="activeFilterBadgeContainer">
                <span id="filterStatsText">सबै सूचनाहरू प्रदर्शन गरिँदैछ</span>
            </div>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <label for="perPageSelect" class="small text-secondary mb-0 fw-semibold text-nowrap">Per Page</label>
                <select id="perPageSelect" class="form-select form-select-sm notice-perpage-select">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>

        {{-- Tabular Notice Table (Exact style as screenshot) --}}
        <div class="notice-table-container shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table align-middle notice-tabular-table mb-0" id="portalNoticeTable">
                    <thead>
                        <tr>
                            <th class="col-date" id="sortDateHeader" style="cursor: pointer;" title="मिति अनुसार क्रमबद्ध गर्नुहोस्">
                                मिति <i class="fa-solid fa-caret-down ms-1 text-secondary" id="sortDateIcon"></i>
                            </th>
                            <th class="col-desc">सूचना विवरण</th>
                            <th class="col-files text-center">फाइलहरू</th>
                            <th class="col-action text-center">एक्सन</th>
                        </tr>
                    </thead>
                    <tbody id="noticeTableBody">
                        @forelse($notices as $notice)
                            @php
                                $isExpired = $notice->isExpired();
                                $hasFile = $notice->hasFile();
                                $isPdf = $notice->isPdf();
                                $nepaliDate = to_nepali_bs_date($notice->created_at);
                                $relativeTime = to_nepali_relative_time($notice->created_at);
                                $adDate = optional($notice->created_at)->format('Y-m-d') ?? '';
                                
                                // Automatic smart branch detection from title / description
                                $titleLower = mb_strtolower($notice->title . ' ' . $notice->description);
                                $branchName = 'सामान्य / प्रशासन शाखा';
                                $branchType = 'administration';
                                if (str_contains($titleLower, 'exam') || str_contains($titleLower, 'routine') || str_contains($titleLower, 'परीक्षा') || str_contains($titleLower, 'terminal')) {
                                    $branchName = 'शैक्षिक / परीक्षा शाखा';
                                    $branchType = 'academic';
                                } elseif (str_contains($titleLower, 'admission') || str_contains($titleLower, 'भर्ना') || str_contains($titleLower, 'intake')) {
                                    $branchName = 'भर्ना शाखा';
                                    $branchType = 'admission';
                                } elseif (str_contains($titleLower, 'holiday') || str_contains($titleLower, 'बिदा') || str_contains($titleLower, 'meeting') || str_contains($titleLower, 'ptm')) {
                                    $branchName = 'प्रशासन महाशाखा';
                                    $branchType = 'administration';
                                }
                            @endphp
                            <tr class="notice-row"
                                data-id="{{ $notice->id }}"
                                data-title="{{ mb_strtolower($notice->title) }}"
                                data-desc="{{ mb_strtolower(strip_tags($notice->description)) }}"
                                data-date="{{ optional($notice->created_at)->timestamp ?? 0 }}"
                                data-addate="{{ $adDate }}"
                                data-status="{{ $isExpired ? 'expired' : 'active' }}"
                                data-branch="{{ $branchType }}"
                                data-hasfile="{{ $hasFile ? '1' : '0' }}">
                                
                                {{-- Column 1: Date (मिति) --}}
                                <td class="col-date">
                                    <div class="notice-date-cell">
                                        <span class="nepali-date" title="A.D. {{ $adDate }}">{{ $nepaliDate ?: $adDate }}</span>
                                        <span class="ad-date-sub">{{ $adDate }}</span>
                                    </div>
                                </td>

                                {{-- Column 2: Notice Description (सूचना विवरण) --}}
                                <td class="col-desc">
                                    <div class="notice-desc-cell">
                                        <a href="{{ route('notice.detail', $notice->id) }}" class="notice-title-link">
                                            {{ $notice->title }}
                                        </a>

                                        <div class="notice-meta-line">
                                            <span class="meta-item time-ago" title="{{ optional($notice->created_at)->format('Y-m-d h:i A') }}">
                                                <i class="fa-regular fa-clock me-1"></i>{{ $relativeTime }}
                                            </span>

                                            <span class="meta-separator">|</span>

                                            <span class="meta-item branch-name text-muted">
                                                {{ $branchName }}
                                            </span>

                                            @if($isExpired)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle status-pill ms-2">
                                                    म्याद सकिएको (Expired)
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle status-pill ms-2">
                                                    सक्रिय
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Column 3: Files (फाइलहरू) --}}
                                <td class="col-files text-center">
                                    @if($hasFile)
                                        @if($isPdf)
                                            <a href="{{ route('notice.detail', $notice->id) }}?view_pdf=1" 
                                               class="notice-document-icon" 
                                               title="PDF कागजात हेर्नुहोस् ({{ $notice->file_name ?? 'PDF' }})">
                                                <svg width="22" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <line x1="10" y1="9" x2="8" y2="9"></line>
                                                </svg>
                                            </a>
                                        @else
                                            <a href="{{ asset($notice->file) }}" 
                                               download 
                                               class="notice-document-icon" 
                                               title="फाइल डाउनलोड गर्नुहोस् ({{ $notice->file_name ?? 'Download' }})">
                                                <svg width="22" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <path d="M12 18v-6"></path>
                                                    <path d="m9 15 3 3 3-3"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted opacity-25">&mdash;</span>
                                    @endif
                                </td>

                                {{-- Column 4: Action (एक्सन) --}}
                                <td class="col-action text-center">
                                    <a href="{{ route('notice.detail', $notice->id) }}" 
                                       class="notice-action-btn" 
                                       title="सूचना हेर्नुहोस् (View Notice)">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr id="noNoticesRow">
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-folder-open fs-2 mb-2 d-block opacity-50"></i>
                                    कुनै पनि सूचना फेला परेन (No notices found).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Empty search result state --}}
            <div id="noFilterMatchMessage" class="text-center py-5 text-muted d-none">
                <i class="fa-solid fa-search fs-2 mb-2 d-block opacity-50 text-secondary"></i>
                <p class="mb-1 fw-semibold text-dark">तपाईंले खोज्नुभएको विवरण अनुसार कुनै सूचना भेटिएन।</p>
                <small>कृपया फरक खोज शब्द वा मिति छनोट गर्नुहोस्।</small>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('btnResetFilters').click();">
                        सबै सूचना हेर्नुहोस्
                    </button>
                </div>
            </div>
        </div>

        {{-- Bottom Footer with Count and Pagination Matching Screenshot --}}
        <div class="notice-pagination-bar d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="notice-results-counter text-muted small" id="paginationStats">
                Showing 1 to 10 of {{ count($notices) }} results
            </div>

            <nav aria-label="Notice pagination">
                <ul class="pagination pagination-sm mb-0 notice-pagination-list" id="paginationControls">
                    {{-- Dynamically populated via JS --}}
                </ul>
            </nav>
        </div>

    </div>
</div>

@push('styles')
<style>
    /* Clean Government / Institutional Portal Styling Matching Screenshot */
    .notice-portal-wrapper {
        background-color: #fbfcfe;
        min-height: 80vh;
    }

    .notice-portal-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1a202c;
        letter-spacing: -0.5px;
    }

    .notice-breadcrumb {
        font-size: 0.85rem;
        color: #64748b;
    }
    .notice-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .notice-breadcrumb a:hover {
        color: var(--primary, #1e3a8a);
        text-decoration: underline;
    }
    .notice-breadcrumb .divider {
        margin: 0 6px;
        opacity: 0.5;
    }
    .notice-breadcrumb .active {
        color: #334155;
        font-weight: 500;
    }

    /* Filters Card */
    .notice-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 16px;
    }

    .notice-input-group .form-control,
    .notice-input-group .form-select {
        height: 38px;
        font-size: 0.875rem;
        color: #334155;
        border-color: #cbd5e1;
    }
    .notice-input-group .form-control:focus,
    .notice-input-group .form-select:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.2);
    }
    .notice-perpage-select {
        width: 80px;
        height: 34px;
        font-size: 0.825rem;
        border-color: #cbd5e1;
    }

    /* Table Container & Table */
    .notice-table-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .notice-tabular-table {
        margin-bottom: 0;
        border-collapse: collapse;
        width: 100%;
    }

    .notice-tabular-table thead th {
        background-color: #ffffff;
        color: #111827;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .notice-tabular-table tbody td {
        padding: 16px 16px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        background-color: #ffffff;
        transition: background-color 0.15s ease;
    }

    .notice-tabular-table tbody tr.notice-row:hover td {
        background-color: #f8fafc;
    }

    /* Column Widths */
    .col-date {
        width: 145px;
        min-width: 130px;
    }
    .col-desc {
        width: auto;
    }
    .col-files {
        width: 90px;
        min-width: 80px;
    }
    .col-action {
        width: 75px;
        min-width: 70px;
    }

    /* Cell Contents */
    .notice-date-cell {
        display: flex;
        flex-direction: column;
    }
    .notice-date-cell .nepali-date {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        letter-spacing: 0.2px;
    }
    .notice-date-cell .ad-date-sub {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 1px;
    }

    .notice-desc-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .notice-title-link {
        font-size: 0.98rem;
        font-weight: 600;
        color: #1e293b;
        text-decoration: none;
        line-height: 1.45;
        transition: color 0.15s ease;
    }
    .notice-title-link:hover {
        color: #0f8a5f;
    }

    .notice-meta-line {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 0.825rem;
        color: #64748b;
    }
    .meta-separator {
        opacity: 0.4;
        font-size: 0.75rem;
    }
    .status-pill {
        font-size: 0.72rem;
        font-weight: 500;
        padding: 2px 7px;
    }

    /* Document Outline Icon (Matching screenshot folded outline) */
    .notice-document-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        color: #475569;
        border-radius: 6px;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .notice-document-icon:hover {
        color: #dc2626;
        background-color: #fee2e2;
        transform: scale(1.08);
    }

    /* Action Green Square Button (Exact Match to Screenshot) */
    .notice-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background-color: #0f8a5f;
        color: #ffffff !important;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.18s ease;
        box-shadow: 0 1px 3px rgba(15, 138, 95, 0.2);
    }
    .notice-action-btn:hover {
        background-color: #0b6b49;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(15, 138, 95, 0.3);
    }

    /* Pagination Styling (Matching Screenshot) */
    .notice-pagination-list .page-link {
        color: #334155;
        border-color: #cbd5e1;
        padding: 5px 11px;
        font-size: 0.85rem;
        border-radius: 4px;
        margin: 0 2px;
    }
    .notice-pagination-list .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        font-weight: 600;
    }
    .notice-pagination-list .page-link:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    /* Responsive Mobile Handling */
    @media (max-width: 767.98px) {
        .notice-portal-title {
            font-size: 1.75rem;
        }

        /* Mobile Card Mode when toggled */
        body.card-view-active .notice-tabular-table thead {
            display: none;
        }
        body.card-view-active .notice-tabular-table,
        body.card-view-active .notice-tabular-table tbody,
        body.card-view-active .notice-tabular-table tr.notice-row {
            display: block;
            width: 100%;
        }
        body.card-view-active .notice-tabular-table tr.notice-row {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 12px;
            padding: 12px;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        body.card-view-active .notice-tabular-table td {
            display: block;
            width: 100% !important;
            padding: 4px 0 !important;
            border: none !important;
        }
        body.card-view-active .notice-tabular-table td.col-date {
            margin-bottom: 6px;
        }
        body.card-view-active .notice-tabular-table td.col-files,
        body.card-view-active .notice-tabular-table td.col-action {
            display: inline-block !important;
            width: auto !important;
            margin-top: 10px;
            margin-right: 8px;
        }
        body.card-view-active .notice-tabular-table td.col-action {
            float: right;
        }

        /* Standard mobile table scrolling */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
        .notice-tabular-table thead th {
            padding: 10px 12px;
            font-size: 0.85rem;
        }
        .notice-tabular-table tbody td {
            padding: 12px 12px;
        }
        .col-date {
            width: 110px;
            min-width: 100px;
        }
        .notice-date-cell .nepali-date {
            font-size: 0.85rem;
        }
        .notice-title-link {
            font-size: 0.92rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('noticeTableBody');
    const allRows = Array.from(tableBody.querySelectorAll('tr.notice-row'));
    const searchInput = document.getElementById('filterSearch');
    const dateFromInput = document.getElementById('filterDateFrom');
    const dateToInput = document.getElementById('filterDateTo');
    const branchSelect = document.getElementById('filterBranch');
    const perPageSelect = document.getElementById('perPageSelect');
    const paginationControls = document.getElementById('paginationControls');
    const paginationStats = document.getElementById('paginationStats');
    const noFilterMatchMessage = document.getElementById('noFilterMatchMessage');
    const filterStatsText = document.getElementById('filterStatsText');
    const btnResetFilters = document.getElementById('btnResetFilters');
    const sortDateHeader = document.getElementById('sortDateHeader');
    const sortDateIcon = document.getElementById('sortDateIcon');

    // Mobile view toggles
    const btnTableView = document.getElementById('btnTableView');
    const btnCardView = document.getElementById('btnCardView');

    if (btnTableView && btnCardView) {
        btnTableView.addEventListener('click', function() {
            btnTableView.classList.add('active');
            btnCardView.classList.remove('active');
            document.body.classList.remove('card-view-active');
        });
        btnCardView.addEventListener('click', function() {
            btnCardView.classList.add('active');
            btnTableView.classList.remove('active');
            document.body.classList.add('card-view-active');
        });
    }

    let currentPage = 1;
    let sortAsc = false; // default latest first (desc)

    function getFilteredRows() {
        const query = (searchInput.value || '').trim().toLowerCase();
        const dateFrom = (dateFromInput.value || '').trim();
        const dateTo = (dateToInput.value || '').trim();
        const branchVal = branchSelect.value;

        return allRows.filter(row => {
            const title = row.getAttribute('data-title') || '';
            const desc = row.getAttribute('data-desc') || '';
            const adDate = row.getAttribute('data-addate') || '';
            const status = row.getAttribute('data-status') || '';
            const branch = row.getAttribute('data-branch') || '';
            const hasFile = row.getAttribute('data-hasfile') === '1';

            // Keyword search
            if (query && !title.includes(query) && !desc.includes(query)) {
                return false;
            }

            // Date From
            if (dateFrom && adDate && adDate < dateFrom) {
                return false;
            }

            // Date To
            if (dateTo && adDate && adDate > dateTo) {
                return false;
            }

            // Branch / Status Filter
            if (branchVal !== 'all') {
                if (branchVal === 'active' && status !== 'active') return false;
                if (branchVal === 'expired' && status !== 'expired') return false;
                if (branchVal === 'has_file' && !hasFile) return false;
                if (['academic', 'administration', 'admission'].includes(branchVal) && branch !== branchVal) {
                    return false;
                }
            }

            return true;
        });
    }

    function sortRows(rows) {
        return rows.sort((a, b) => {
            const dateA = parseInt(a.getAttribute('data-date')) || 0;
            const dateB = parseInt(b.getAttribute('data-date')) || 0;
            return sortAsc ? (dateA - dateB) : (dateB - dateA);
        });
    }

    function renderPagination(totalItems, perPage) {
        paginationControls.innerHTML = '';
        if (totalItems === 0) {
            paginationStats.textContent = 'Showing 0 to 0 of 0 results';
            return;
        }

        const totalPages = perPage === 'all' ? 1 : Math.ceil(totalItems / perPage);
        if (currentPage > totalPages) currentPage = totalPages;

        const startIdx = perPage === 'all' ? 1 : (currentPage - 1) * perPage + 1;
        const endIdx = perPage === 'all' ? totalItems : Math.min(currentPage * perPage, totalItems);
        paginationStats.textContent = `Showing ${startIdx} to ${endIdx} of ${totalItems} results`;

        if (totalPages <= 1) return;

        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous">&lsaquo;</a>`;
        prevLi.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
        paginationControls.appendChild(prevLi);

        // Page numbers calculation with ellipsis
        const pages = [];
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                pages.push(i);
            } else if (pages[pages.length - 1] !== '...') {
                pages.push('...');
            }
        }

        pages.forEach(p => {
            const li = document.createElement('li');
            if (p === '...') {
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link">...</span>';
            } else {
                li.className = `page-item ${p === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${p}</a>`;
                li.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = p;
                    renderTable();
                });
            }
            paginationControls.appendChild(li);
        });

        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next">&rsaquo;</a>`;
        nextLi.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });
        paginationControls.appendChild(nextLi);
    }

    function renderTable() {
        const filtered = getFilteredRows();
        const sorted = sortRows(filtered);
        const totalItems = sorted.length;
        const perPageVal = perPageSelect.value;
        const perPage = perPageVal === 'all' ? 'all' : parseInt(perPageVal);

        // Hide all rows initially
        allRows.forEach(row => row.style.display = 'none');

        if (totalItems === 0) {
            noFilterMatchMessage.classList.remove('d-none');
            renderPagination(0, perPage);
            return;
        }

        noFilterMatchMessage.classList.add('d-none');

        const startIndex = perPage === 'all' ? 0 : (currentPage - 1) * perPage;
        const endIndex = perPage === 'all' ? totalItems : Math.min(startIndex + perPage, totalItems);

        // Append visible rows in sorted order
        for (let i = startIndex; i < endIndex; i++) {
            const row = sorted[i];
            row.style.display = '';
            tableBody.appendChild(row);
        }

        renderPagination(totalItems, perPage);

        // Update stats banner
        if (filterStatsText) {
            if (totalItems === allRows.length) {
                filterStatsText.textContent = `सबै सूचनाहरू प्रदर्शन गरिँदैछ (${totalItems})`;
            } else {
                filterStatsText.textContent = `फिल्टर गरिएको: ${totalItems} / ${allRows.length} सूचनाहरू`;
            }
        }
    }

    // Toggle Sort Order
    if (sortDateHeader) {
        sortDateHeader.addEventListener('click', function () {
            sortAsc = !sortAsc;
            sortDateIcon.className = sortAsc ? 'fa-solid fa-caret-up ms-1 text-primary' : 'fa-solid fa-caret-down ms-1 text-primary';
            currentPage = 1;
            renderTable();
        });
    }

    // Input listeners
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentPage = 1;
            renderTable();
        }, 150);
    });

    dateFromInput.addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    dateToInput.addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    branchSelect.addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    perPageSelect.addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    btnResetFilters.addEventListener('click', function () {
        searchInput.value = '';
        dateFromInput.value = '';
        dateToInput.value = '';
        branchSelect.value = 'all';
        perPageSelect.value = '10';
        currentPage = 1;
        sortAsc = false;
        sortDateIcon.className = 'fa-solid fa-caret-down ms-1 text-secondary';
        renderTable();
    });

    // Initial render
    renderTable();
});
</script>
@endpush

@endsection
