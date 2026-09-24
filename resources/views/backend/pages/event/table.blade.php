@extends('backend.pages.layout.master')
@push('b-title', 'Events')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Events</h1><p class="aph-sub">Manage college events and calendar entries.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addEventModal"><i class="bi bi-plus-lg"></i> Add Event</button>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-calendar2-event-fill"></i> All Events</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Image</th><th>Event Name</th><th>Category</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($event as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td><span class="badge-admin badge-info">{{ $data->event_type_label ?? ucfirst(str_replace('_',' ',$data->event_type ?? 'event')) }}</span></td>
            <td style="white-space:nowrap;font-size:12px;">{{ date('d M Y', strtotime($data->getRawOriginal('visit_date'))) }}</td>
            <td><a href="{{ route('event.status', $data->id) }}" class="badge-admin {{ $data->status==1 ? 'badge-active' : 'badge-inactive' }}">{{ $data->status==1 ? 'Active' : 'Inactive' }}</a></td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('event.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('event.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#718096;">No events added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
        <h5 class="modal-title" id="addEventModalLabel" style="font-weight: 600; color: #1e293b;">Add New Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('event.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-body p-4">
          @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px;">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="row g-4">
            <div class="col-md-5">
                <div class="mb-3">
                    <label class="admin-label">Event Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="admin-input" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label">Calendar Category</label>
                    <select name="event_type" class="admin-select" id="eventTypeSelector">
                        <option value="event" {{ old('event_type') === 'event' ? 'selected' : '' }}>General Event</option>
                        <option value="holiday" {{ old('event_type') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                        <option value="exam" {{ old('event_type') === 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="test" {{ old('event_type') === 'test' ? 'selected' : '' }}>Test</option>
                        <option value="cca_eca" {{ old('event_type') === 'cca_eca' ? 'selected' : '' }}>CCA / ECA</option>
                        <option value="result" {{ old('event_type') === 'result' ? 'selected' : '' }}>Result</option>
                    </select>
                </div>
                <div class="alert alert-light border" id="eventCategoryHint" style="font-size: 13px; margin-bottom: 15px;">
                    Choose a category to show the relevant fields for this item.
                </div>
                <div class="mb-3">
                    <label class="admin-label" id="eventDateLabel">Event Visit Date</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date') }}" class="admin-input" required>
                </div>
                <div class="mb-3" data-event-field="venue">
                    <label class="admin-label">Venue / Notes</label>
                    <input type="text" name="venue" value="{{ old('venue', 'GPLC Campus, Itahari') }}" class="admin-input">
                </div>
                <div class="mb-3" data-event-field="result_link">
                    <label class="admin-label">Result Link (optional)</label>
                    <input type="text" name="result_link" value="{{ old('result_link') }}" class="admin-input" placeholder="Paste result page URL">
                </div>
                <div class="mb-3">
                    <label class="admin-label">Upload Cover Image</label>
                    <input type="file" name="image" class="admin-input" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label">Event Gallery Images</label>
                    <input type="file" name="gallery[]" multiple class="admin-input" accept="image/*">
                    <small class="text-muted">Upload multiple supporting images for the public event gallery.</small>
                </div>
            </div>
            <div class="col-md-7">
                <label class="admin-label">Full Description</label>
                <textarea id="eventSummernote" name="description">{{ old('description') }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 0 0 12px 12px;">
          <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="border-radius: 8px; background-color: #1a4d8c; border: none;">Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#eventSummernote').summernote({
          placeholder: 'Write more about your event',
          tabsize: 2,
          height: 480,
          dialogsInBody: true,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['codeview', 'help']]
          ]
        });

        const eventTypeSelector = document.getElementById('eventTypeSelector');
        const eventDateLabel = document.getElementById('eventDateLabel');
        const eventCategoryHint = document.getElementById('eventCategoryHint');
        const venueField = document.querySelector('[data-event-field="venue"]');
        const resultField = document.querySelector('[data-event-field="result_link"]');

        const config = {
            event: { date: 'Event Date', showVenue: true, showResult: false, hint: 'Use this for seminars, programs, celebrations, and other regular events.' },
            holiday: { date: 'Holiday Date', showVenue: false, showResult: false, hint: 'Use this for holidays and breaks. Venue is not required here.' },
            exam: { date: 'Exam Date', showVenue: true, showResult: false, hint: 'Use this for exams. You can mention the hall, room, or exam notes in venue.' },
            test: { date: 'Test Date', showVenue: true, showResult: false, hint: 'Use this for class tests, internal tests, or assessment dates.' },
            cca_eca: { date: 'Activity Date', showVenue: true, showResult: false, hint: 'Use this for CCA/ECA activities, competitions, and student participation events.' },
            result: { date: 'Result Publish Date', showVenue: false, showResult: true, hint: 'Use this for result publication. Add the result link so students can open it quickly.' },
        };

        const syncEventFields = () => {
            const selected = eventTypeSelector.value || 'event';
            const current = config[selected] || config.event;
            eventDateLabel.textContent = current.date;
            eventCategoryHint.textContent = current.hint;
            venueField.style.display = current.showVenue ? '' : 'none';
            resultField.style.display = current.showResult ? '' : 'none';
        };

        eventTypeSelector?.addEventListener('change', syncEventFields);
        syncEventFields();
        
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addEventModal'));
            myModal.show();
        @endif
    });
</script>
@endpush
