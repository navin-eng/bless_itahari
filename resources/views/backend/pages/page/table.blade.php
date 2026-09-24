@extends('backend.pages.layout.master')
@push('b-title', 'Pages')
@section('backend-content')
    <div class="row" style="margin-bottom:16px;">
        <div class="col-6">
            <h5 class="h4">Custom Pages</h5>
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPageModal">+ Add New Page</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Slug / URL</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $index => $page)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td>
                            <a href="{{ url('/page/' . $page->slug) }}" target="_blank" class="text-primary">
                                /page/{{ $page->slug }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('page.status', $page->id) }}">
                                @if ($page->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </a>
                        </td>
                        <td>{{ $page->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('page.edit', $page->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('page.destroy', $page->id) }}" class="btn btn-sm btn-danger deleteBtn"
                               data-href="{{ route('page.destroy', $page->id) }}">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No pages found. <a href="{{ route('page.add') }}">Add one now.</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <!-- Add Page Modal -->
    <div class="modal fade" id="addPageModal" tabindex="-1" aria-labelledby="addPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPageModalLabel">Add New Page (HTML)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('page.store') }}" method="POST" style="width:100%;">
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
                            <div class="col-md-8 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control"
                                        placeholder="Enter page title" required>
                                    <small class="text-muted">The URL slug will be auto-generated from the title.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">HTML Content</label>
                                    <div style="border:1px solid #ced4da; border-radius:4px; overflow:hidden;">
                                        <div id="toolbar" style="background:#f8f9fa; padding:8px; border-bottom:1px solid #ced4da; display:flex; flex-wrap:wrap; gap:4px;">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('h2')">H2</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('h3')">H3</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('p')">P</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('strong')"><b>B</b></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('em')"><i>I</i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('ul')">UL</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('ol')">OL</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('li')">LI</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('a href=\'#\'')">Link</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('img src=\'\' alt=\'\'')">IMG</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('div class=\'\'')">DIV</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('table class=\'table table-bordered\'')">Table</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('tr')">TR</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('td')">TD</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('span')">Span</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('br /')">BR</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('hr /')">HR</button>
                                        </div>
                                        <textarea id="htmlEditor" name="content" rows="18"
                                            style="width:100%; border:none; padding:12px; font-family:monospace; font-size:13px; resize:vertical; outline:none; background:#1e1e2e; color:#cdd6f4;">{{ old('content') }}</textarea>
                                    </div>
                                    <small class="text-muted">Write raw HTML. Use the toolbar buttons to insert common tags.</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="card" style="border:1px solid #dee2e6;">
                                    <div class="card-header"><strong>Preview</strong></div>
                                    <div class="card-body" id="previewBox" style="min-height:200px; max-height:400px; overflow:auto; font-size:14px;"></div>
                                </div>
                                <br>
                                <button type="button" class="btn btn-outline-info w-100" onclick="updatePreview()">Refresh Preview</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-5">Save Page</button>
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
            var myModal = new bootstrap.Modal(document.getElementById('addPageModal'));
            myModal.show();
        });
    @endif

    function insertTag(tag) {
        const ta = document.getElementById('htmlEditor');
        const start = ta.selectionStart;
        const end = ta.selectionEnd;
        const selected = ta.value.substring(start, end);
        const tagName = tag.split(' ')[0];
        const voidTags = ['br', 'hr', 'img', 'input'];
        let insert;
        if (tag.endsWith('/')) {
            insert = '<' + tag + '>';
        } else if (voidTags.includes(tagName)) {
            insert = '<' + tag + '>';
        } else {
            insert = '<' + tag + '>' + selected + '</' + tagName + '>';
        }
        ta.value = ta.value.substring(0, start) + insert + ta.value.substring(end);
        ta.selectionStart = ta.selectionEnd = start + insert.length;
        ta.focus();
        updatePreview();
    }

    function updatePreview() {
        var box = document.getElementById('previewBox');
        if (box) {
            box.innerHTML = document.getElementById('htmlEditor').value;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const ed = document.getElementById('htmlEditor');
        if (ed) {
            ed.addEventListener('input', function () {
                updatePreview();
            });
            updatePreview();
        }
    });
</script>
@endpush
