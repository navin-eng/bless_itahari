@extends('backend.pages.layout.master')
@push('b-title', 'Admission Details')
@section('backend-content')

<div class="admin-page-header">
    <div>
        <h1 class="aph-title">Application Details</h1>
        <p class="aph-sub">Review application from {{ $enquiry->name }}</p>
    </div>
    <a href="{{ route('admin.admissions.index') }}" class="btn-admin btn-admin-outline"><i class="bi bi-arrow-left"></i> Back to Inbox</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-person-lines-fill text-primary"></i> Applicant Information</span>
            </div>
            <div class="admin-card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Full Name</label>
                        <div style="font-size:16px;font-weight:600;color:#0f172a;">{{ $enquiry->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Applied For</label>
                        <div style="font-size:16px;font-weight:600;color:#0f172a;">{{ $enquiry->course_name ?? 'General Admission' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Email Address</label>
                        <div style="font-size:16px;font-weight:600;color:#3b82f6;"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Phone Number</label>
                        <div style="font-size:16px;font-weight:600;color:#0f172a;"><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></div>
                    </div>
                    <div class="col-md-12">
                        <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Previous School / Academic Background</label>
                        <div style="font-size:15px;color:#334155;">{{ $enquiry->previous_school ?: 'N/A' }}</div>
                    </div>
                    <div class="col-md-12">
                        <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Message / Additional Notes</label>
                        <div style="font-size:15px;color:#334155;background:#f8fafc;padding:15px;border-radius:8px;border:1px solid #e2e8f0;white-space:pre-wrap;">{{ $enquiry->message ?: 'No message provided.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-gear-fill text-primary"></i> Application Status</span>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.admissions.update', $enquiry->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="admin-label">Current Status</label>
                        <select name="status" class="admin-select" required>
                            <option value="pending" {{ $enquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $enquiry->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="contacted" {{ $enquiry->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="rejected" {{ $enquiry->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button class="btn-admin btn-admin-primary w-100" type="submit"><i class="bi bi-save"></i> Update Status</button>
                </form>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-info-circle-fill text-primary"></i> Application Meta</span>
            </div>
            <div class="admin-card-body">
                <div class="mb-3">
                    <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Application ID</label>
                    <div style="font-size:14px;color:#0f172a;font-weight:600;">#APP-{{ str_pad($enquiry->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="mb-3">
                    <label style="font-size:12px;text-transform:uppercase;color:#94a3b8;font-weight:700;letter-spacing:0.5px;">Submitted On</label>
                    <div style="font-size:14px;color:#0f172a;"><i class="bi bi-calendar3"></i> {{ $enquiry->created_at->format('M d, Y') }}</div>
                    <div style="font-size:13px;color:#64748b;"><i class="bi bi-clock"></i> {{ $enquiry->created_at->format('g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
