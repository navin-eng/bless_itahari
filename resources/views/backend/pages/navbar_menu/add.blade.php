@extends('backend.pages.layout.master')
@push('b-title', 'Add Navbar Menu')
@section('backend-content')
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="h4">Add Navbar Menu Item</h5>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('navbar_menu.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Menu Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. About Us" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">URL / Link</label>
                        <input type="text" name="url" class="form-control" placeholder="e.g. /about-us or https://google.com">
                        <small class="text-muted">Leave empty if this is a dropdown.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Item Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="standard">Standard Link</option>
                            <option value="course_dropdown">Courses Dropdown (Auto-generates course list)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Order Position *</label>
                        <input type="number" name="order" class="form-control" value="0" required>
                        <small class="text-muted">Lower numbers appear first (e.g. 1, 2, 3).</small>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-success">Save Menu Item</button>
                        <a href="{{ route('navbar_menu.table') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
