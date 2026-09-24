@extends('backend.pages.layout.master')
@push('b-title', 'Admission Enquiries')
@section('backend-content')

<div class="admin-page-header">
    <div>
        <h1 class="aph-title">Admission Enquiries</h1>
        <p class="aph-sub">Manage and track student admission applications.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span class="card-title"><i class="bi bi-inbox-fill text-primary"></i> Application Inbox</span>
    </div>
    
    <div class="admin-card-body p-0">
        <div class="table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Applicant Name</th>
                        <th>Applied For</th>
                        <th>Contact</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries as $app)
                    <tr>
                        <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
                        <td style="font-weight:600;">{{ $app->name }}</td>
                        <td><span class="badge-admin badge-info">{{ $app->course_name ?? 'General' }}</span></td>
                        <td>
                            <div style="font-size:12px;"><i class="bi bi-envelope"></i> {{ $app->email }}</div>
                            <div style="font-size:12px;"><i class="bi bi-telephone"></i> {{ $app->phone }}</div>
                        </td>
                        <td><span style="font-size:13px;color:#64748b;">{{ $app->created_at->format('M d, Y g:i A') }}</span></td>
                        <td>
                            @if($app->status == 'pending')
                                <span class="badge-admin badge-warning"><i class="bi bi-clock-history"></i> Pending</span>
                            @elseif($app->status == 'reviewed')
                                <span class="badge-admin badge-primary"><i class="bi bi-eye"></i> Reviewed</span>
                            @elseif($app->status == 'contacted')
                                <span class="badge-admin badge-green"><i class="bi bi-check-circle"></i> Contacted</span>
                            @else
                                <span class="badge-admin badge-secondary"><i class="bi bi-x-circle"></i> Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.admissions.show', $app->id) }}" class="btn-admin btn-admin-sm btn-admin-info" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.admissions.delete', $app->id) }}" class="btn-admin btn-admin-sm btn-admin-danger" onclick="return confirm('Are you sure you want to delete this enquiry?')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size:40px;color:#cbd5e1;margin-bottom:10px;display:block;"></i>
                                <h5 style="color:#64748b;">No Applications Yet</h5>
                                <p style="color:#94a3b8;font-size:14px;max-width:300px;margin:0 auto;">When students apply using the "Apply Now" form, they will appear here.</p>
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
