@extends('backend.pages.layout.master')
@push('b-title', 'College Messages')
@section('backend-content')
    <div class="row" style="margin-bottom:16px;">
        <div class="col-6">
            <h5 class="h4">Messages (Principal / Chairman / Coordinator)</h5>
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMessageModal">+ Add Message</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Message (preview)</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $index => $msg)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($msg->image)
                                <img src="{{ asset($msg->image) }}" class="table-backend-image"
                                    style="border-radius:50%; width:55px; height:55px;" alt="">
                            @else
                                <div style="width:55px;height:55px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-person-fill" style="font-size:24px; color:#adb5bd;"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $msg->name }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ $msg->designation }}</span></td>
                        <td>{{ Str::limit($msg->message, 80) }}</td>
                        <td>{{ $msg->order }}</td>
                        <td>
                            <a href="{{ route('college_message.status', $msg->id) }}">
                                @if ($msg->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('college_message.edit', $msg->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('college_message.destroy', $msg->id) }}" class="btn btn-sm btn-danger deleteBtn"
                               data-href="{{ route('college_message.destroy', $msg->id) }}">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No messages found. <a href="{{ route('college_message.add') }}">Add one now.</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <!-- Add Message Modal -->
    <div class="modal fade" id="addMessageModal" tabindex="-1" aria-labelledby="addMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMessageModalLabel">Add Message (Principal / Chairman / Coordinator)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('college_message.store') }}" enctype="multipart/form-data" method="POST" style="width:100%;">
                    @csrf
                    <div class="modal-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-7 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                        placeholder="e.g. Ram Prasad Sharma" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                                    <select name="designation" class="form-select" required>
                                        <option value="">-- Select Designation --</option>
                                        <option value="Principal" {{ old('designation') == 'Principal' ? 'selected' : '' }}>Principal</option>
                                        <option value="Chairman" {{ old('designation') == 'Chairman' ? 'selected' : '' }}>Chairman</option>
                                        <option value="Coordinator" {{ old('designation') == 'Coordinator' ? 'selected' : '' }}>Coordinator</option>
                                        <option value="Vice Principal" {{ old('designation') == 'Vice Principal' ? 'selected' : '' }}>Vice Principal</option>
                                        <option value="Director" {{ old('designation') == 'Director' ? 'selected' : '' }}>Director</option>
                                        <option value="Other" {{ old('designation') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" rows="8"
                                        placeholder="Write the message here..." required>{{ old('message') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                                        placeholder="0 = first" min="0">
                                    <small class="text-muted">Lower number appears first.</small>
                                </div>
                            </div>
                            <div class="col-md-5 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Photo</label>
                                    <input type="file" name="image" class="form-control" accept="image/*"
                                        onchange="previewImage(this)">
                                </div>
                                <div id="imagePreview" style="display:none; margin-top:10px;">
                                    <img id="previewImg" src="" alt="Preview"
                                        style="width:160px; height:180px; object-fit:cover; border-radius:8px; border:2px solid #dee2e6;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-5">Save Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('addMessageModal'));
            myModal.show();
        });
    @endif

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
