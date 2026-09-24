@extends('backend.pages.layout.master')
@push('b-title', 'Navbar Menu')
@section('backend-content')
    <div class="row" style="margin-bottom:16px;">
        <div class="col-6">
            <h5 class="h4">Navbar Menu Items</h5>
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNavbarModal">+ Add New Link</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-menus">
                @forelse ($menus as $menu)
                    <tr data-id="{{ $menu->id }}">
                        <td>
                            <i class="fas fa-grip-vertical handle" style="cursor: grab; color: #aaa; margin-right: 10px;" title="Drag to reorder"></i>
                            <span class="menu-order">{{ $menu->order }}</span>
                        </td>
                        <td><strong>{{ $menu->name }}</strong></td>
                        <td>
                            @if($menu->url)
                                <a href="{{ url($menu->url) }}" target="_blank" class="text-primary">{{ $menu->url }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($menu->type == 'course_dropdown')
                                <span class="badge bg-info">Courses Dropdown</span>
                            @else
                                <span class="badge bg-secondary">Standard Link</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('navbar_menu.status', $menu->id) }}">
                                @if ($menu->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('navbar_menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @php
                                $isDefault = in_array(strtolower(trim($menu->name)), ['home', 'about us', 'about', 'contact', 'contact us', 'gallery', 'calendar', 'faculties', 'academics']) || in_array(strtolower(trim($menu->url)), ['', '/', 'about/us', '/about/us', 'contact', '/contact', 'gallery', '/gallery', 'calendar', '/calendar', 'member', '/member', 'course', '/course']);
                            @endphp
                            @if(!$isDefault)
                                <a href="{{ route('navbar_menu.destroy', $menu->id) }}" class="btn btn-sm btn-danger deleteBtn"
                                   data-href="{{ route('navbar_menu.destroy', $menu->id) }}">Delete</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No menu items found. <a href="{{ route('navbar_menu.add') }}">Add one now.</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <!-- Add Navbar Modal -->
    <div class="modal fade" id="addNavbarModal" tabindex="-1" aria-labelledby="addNavbarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNavbarModalLabel">Add Navbar Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('navbar_menu.store') }}" method="POST">
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
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Menu Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. About Us" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">URL / Link</label>
                                <input type="text" name="url" class="form-control" placeholder="e.g. /about-us or https://google.com">
                                <small class="text-muted">Leave empty if this is a dropdown.</small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Item Type *</label>
                                <select name="type" class="form-control" required>
                                    <option value="standard">Standard Link</option>
                                    <option value="course_dropdown">Courses Dropdown (Auto-generates course list)</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Order Position *</label>
                                <input type="number" name="order" class="form-control" value="0" required>
                                <small class="text-muted">Lower numbers appear first (e.g. 1, 2, 3).</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Menu Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sortableList = document.getElementById('sortable-menus');
        if(sortableList && sortableList.querySelectorAll('tr[data-id]').length > 1) {
            new Sortable(sortableList, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: function(evt) {
                    const rows = sortableList.querySelectorAll('tr[data-id]');
                    let ids = [];
                    rows.forEach((row, index) => {
                        ids.push(row.getAttribute('data-id'));
                        // Update visual order
                        const orderSpan = row.querySelector('.menu-order');
                        if (orderSpan) orderSpan.innerText = index + 1;
                    });

                    fetch('{{ route("navbar_menu.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            console.log('Order updated successfully.');
                        } else {
                            console.error('Failed to update order');
                        }
                    })
                    .catch(error => console.error('Error reordering:', error));
                }
            });
        }
        
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addNavbarModal'));
            myModal.show();
        @endif
    });
</script>
@endpush
