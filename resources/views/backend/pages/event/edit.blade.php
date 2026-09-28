@extends('backend.pages.layout.master')
@push('b-title', 'Edit Event')

@push('styles')
<link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css" rel="stylesheet" type="text/css"/>
@endpush

@section('backend-content')
<div class="row">
    <div class="mb-3">
        <a href="{{ route('event.table') }}" class="btn btn-success">&nbsp;&nbsp;Table</a>
    </div>
    <h5 class="h4" style="text-align: center; margin:10px 0;">Back</h5>
</div>
<br>
<form action="{{ route('event.update',$event->id) }}" enctype="multipart/form-data" method="POST" style="width: 100%;">
    @csrf
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="mb-3">
                <label for="" class="form-label">Event Name</label>
                <input type="text" name="name" value="{{ $event->name }}" id="" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Calendar Category</label>
                <select name="event_type" class="form-control" id="eventTypeSelector">
                    <option value="event" {{ $event->event_type === 'event' ? 'selected' : '' }}>General Event</option>
                    <option value="holiday" {{ $event->event_type === 'holiday' ? 'selected' : '' }}>Holiday</option>
                    <option value="exam" {{ $event->event_type === 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="test" {{ $event->event_type === 'test' ? 'selected' : '' }}>Test</option>
                    <option value="cca_eca" {{ $event->event_type === 'cca_eca' ? 'selected' : '' }}>CCA / ECA</option>
                    <option value="result" {{ $event->event_type === 'result' ? 'selected' : '' }}>Result</option>
                </select>
            </div>
            <div class="alert alert-light border" id="eventCategoryHint" style="font-size: 13px;">
                Choose a category to show the relevant fields for this item.
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="" class="form-label mb-0" id="eventDateLabel">Event Visit Date</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="useNepaliDateToggle">
                        <label class="form-check-label" style="font-size: 13px;" for="useNepaliDateToggle">Use Nepali Calendar</label>
                    </div>
                </div>
                <input type="date" name="visit_date" value="{{ $event->getRawOriginal('visit_date') }}" id="englishDateInput" class="form-control" placeholder="">
                <input type="text" id="nepaliDateInput" class="form-control" placeholder="Select Nepali Date (YYYY-MM-DD)" style="display: none;" readonly>
                <small class="text-muted" id="dateHelperText" style="display: none; margin-top:5px;">This automatically saves the standard English date behind the scenes.</small>
            </div>
            <div class="mb-3" data-event-field="venue">
                <label for="" class="form-label" id="venueLabel">Venue / Location / Notes</label>
                <input type="text" name="venue" value="{{ $event->venue }}" class="form-control" id="venueInput" placeholder="e.g. School Auditorium">
            </div>
            <div class="mb-3" data-event-field="result_link">
                <label for="" class="form-label">Result Link</label>
                <input type="text" name="result_link" value="{{ $event->result_link }}" class="form-control" placeholder="https://neb.gov.np/result">
            </div>
            <div class="mb-3" data-event-field="image">
                <label for="" class="form-label">Upload Cover Image <span class="text-muted">(leave blank to keep current)</span></label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3" data-event-field="gallery">
                <label for="" class="form-label">Event Gallery Images <span class="text-muted">(adds to existing)</span></label>
                <input type="file" name="gallery[]" multiple class="form-control" accept="image/*">
                <small class="text-muted">Upload multiple supporting images for this event.</small>
            </div>
            @if(!empty($event->gallery))
                <div class="mb-3">
                    <label class="form-label">Current Gallery</label>
                    <div class="row g-2">
                        @foreach(($event->gallery ?? []) as $index => $img)
                            <div class="col-md-3 col-6">
                                <div class="border rounded p-2 h-100">
                                    <img src="{{ asset($img) }}" alt="Event Gallery" class="img-fluid rounded mb-2" style="height: 90px; width: 100%; object-fit: cover;">
                                    <a href="{{ route('event.gallery.delete', [$event->id, $index]) }}" class="btn btn-sm btn-outline-danger w-100">Delete</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-12">
            <label for="">Full Description About Your Event..</label>
            <textarea id="summernote" name="description">
                {{ $event->description }}
            </textarea>
        </div>
    </div>
    <div class="mb-3" style="margin: 10px 0;">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
@push('scripts')
<script>
    (() => {
        $('#summernote').summernote({
          placeholder: 'Write more about your course',
          tabsize: 2,
          height: 420,
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
            event:   { date: 'Event Date',          showVenue: true,  venueLabel: 'Venue / Location',         venuePlaceholder: 'e.g. School Auditorium, Main Hall', showResult: false, showImage: true,  showGallery: true,  hint: '📅 General Event — for seminars, programs, celebrations, and school activities.' },
            holiday: { date: 'Holiday Date',         showVenue: false, venueLabel: '',                         venuePlaceholder: '',                                  showResult: false, showImage: false, showGallery: false, hint: '🎉 Holiday / Break — no venue or images needed.' },
            exam:    { date: 'Exam Date',             showVenue: true,  venueLabel: 'Exam Hall / Room / Notes', venuePlaceholder: 'e.g. Exam Hall A, Ground Floor',   showResult: false, showImage: false, showGallery: false, hint: '📝 Exam — specify the hall or room. No gallery needed.' },
            test:    { date: 'Test Date',             showVenue: true,  venueLabel: 'Classroom / Location',    venuePlaceholder: 'e.g. Classroom 5B, Science Lab',   showResult: false, showImage: false, showGallery: false, hint: '✏️ Class Test — specify the classroom. No images needed.' },
            cca_eca: { date: 'Activity Date',         showVenue: true,  venueLabel: 'Activity Venue',          venuePlaceholder: 'e.g. School Ground, Music Room',   showResult: false, showImage: true,  showGallery: true,  hint: '🏆 CCA / ECA — for competitions, clubs, sports, and student activities.' },
            result:  { date: 'Result Publish Date',  showVenue: false, venueLabel: '',                         venuePlaceholder: '',                                  showResult: true,  showImage: false, showGallery: false, hint: '📊 Result — add the result URL. No venue or gallery needed.' },
        };

        const venueLabel = document.getElementById('venueLabel');
        const venueInput = document.getElementById('venueInput');

        const syncEventFields = () => {
            const selected = eventTypeSelector.value || 'event';
            const c = config[selected] || config.event;
            if (eventDateLabel)    eventDateLabel.textContent    = c.date;
            if (eventCategoryHint) eventCategoryHint.textContent = c.hint;
            if (venueField)  venueField.style.display  = c.showVenue  ? '' : 'none';
            if (resultField) resultField.style.display = c.showResult ? '' : 'none';
            const imageField   = document.querySelector('[data-event-field="image"]');
            const galleryField = document.querySelector('[data-event-field="gallery"]');
            if (imageField)   imageField.style.display   = c.showImage   ? '' : 'none';
            if (galleryField) galleryField.style.display = c.showGallery ? '' : 'none';
            if (c.showVenue && venueLabel) {
                venueLabel.textContent = c.venueLabel;
                if (venueInput) venueInput.placeholder = c.venuePlaceholder;
            }
        };

        eventTypeSelector?.addEventListener('change', syncEventFields);
        syncEventFields();

        // Nepali Date Picker Logic
        const useNepaliDateToggle = document.getElementById('useNepaliDateToggle');
        const englishDateInput = document.getElementById('englishDateInput');
        const nepaliDateInput = document.getElementById('nepaliDateInput');
        const dateHelperText = document.getElementById('dateHelperText');

        if(typeof nepaliDatePicker !== 'undefined') {
            var nepaliDatePickerEl = document.getElementById("nepaliDateInput");
            nepaliDatePickerEl.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 20,
                onChange: function() {
                    const nepaliDateStr = nepaliDateInput.value;
                    if(nepaliDateStr) {
                        const dateObj = window.NepaliFunctions.ConvertToDateObject(nepaliDateStr, "YYYY-MM-DD");
                        const englishDateObj = window.NepaliFunctions.BS2AD(dateObj);
                        if(englishDateObj) {
                            const formattedDate = `${englishDateObj.year}-${String(englishDateObj.month).padStart(2, '0')}-${String(englishDateObj.day).padStart(2, '0')}`;
                            englishDateInput.value = formattedDate;
                        }
                    }
                }
            });
        }

        useNepaliDateToggle?.addEventListener('change', function() {
            if(this.checked) {
                // Switch to Nepali
                englishDateInput.style.display = 'none';
                nepaliDateInput.style.display = 'block';
                dateHelperText.style.display = 'block';
                
                // Convert AD to BS if AD has value
                if(englishDateInput.value) {
                    const adDate = new Date(englishDateInput.value);
                    if(!isNaN(adDate.getTime())) {
                        const adDateObj = { year: adDate.getFullYear(), month: adDate.getMonth() + 1, day: adDate.getDate() };
                        const bsDateObj = window.NepaliFunctions.AD2BS(adDateObj);
                        nepaliDateInput.value = window.NepaliFunctions.ConvertDateFormat(bsDateObj, "YYYY-MM-DD");
                    }
                }
            } else {
                // Switch back to English
                englishDateInput.style.display = 'block';
                nepaliDateInput.style.display = 'none';
                dateHelperText.style.display = 'none';
            }
        });
    })();
</script>
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js" type="text/javascript"></script>
@endpush
@endsection
