@push('styles')
<style>
/* keep Summernote inside card */
.note-editor.note-frame { border: 1px solid var(--admin-border, #e2e8f0) !important; border-radius: 8px !important; }
.note-toolbar { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
.school-tab-nav {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0;
    margin-bottom: 20px;
    overflow-x: auto;
}
.school-tab-btn {
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 10px 18px;
    font-weight: 600;
    font-size: 14px;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.school-tab-btn:hover {
    color: var(--bs-primary, #0d6efd);
}
.school-tab-btn.active {
    color: var(--bs-primary, #0d6efd);
    border-bottom-color: var(--bs-primary, #0d6efd);
    background: rgba(13, 110, 253, 0.04);
    border-radius: 6px 6px 0 0;
}
.school-tab-pane {
    display: none;
}
.school-tab-pane.active {
    display: block;
}
</style>
@endpush

@php
    $isEdit = isset($course) && $course instanceof \App\Models\Course;
    $formAction = $isEdit ? route('course.update', $course->id) : route('course.store');
    $heading = $isEdit ? 'Edit Academic Level / Program' : 'Add Academic Level / Program';
    $buttonText = $isEdit ? 'Update Level' : 'Save Academic Level';
    $courseData = [
        'name' => old('name', $isEdit ? $course->name : ''),
        'academic_level' => old('academic_level', $isEdit ? $course->academic_level : 'Basic Level'),
        'grade_span' => old('grade_span', $isEdit ? $course->grade_span : ''),
        'duration' => old('duration', $isEdit ? $course->duration : 'Annual Academic Session'),
        'semester' => old('semester', $isEdit ? $course->semester : 'Annual'),
        'requirement' => old('requirement', $isEdit ? $course->requirement : ''),
        'evaluation_system' => old('evaluation_system', $isEdit ? $course->evaluation_system : ''),
        'starting_time' => old('starting_time'),
        'closing_time' => old('closing_time'),
        'description' => old('description', $isEdit ? $course->description : ''),
        'fulldescription' => old('fulldescription', $isEdit ? $course->fulldescription : ''),
        'curriculum' => old('curriculum', $isEdit ? $course->curriculum : ''),
        'rules' => old('rules', $isEdit ? $course->rules : ''),
        'admission_procedure' => old('admission_procedure', $isEdit ? $course->admission_procedure : ''),
    ];
    if ($isEdit && !old('starting_time') && !empty($course->starting_time)) {
        try { $courseData['starting_time'] = \Illuminate\Support\Carbon::parse($course->starting_time)->format('H:i'); }
        catch (\Throwable $e) { $courseData['starting_time'] = ''; }
    }
    if ($isEdit && !old('closing_time') && !empty($course->closing_time)) {
        try { $courseData['closing_time'] = \Illuminate\Support\Carbon::parse($course->closing_time)->format('H:i'); }
        catch (\Throwable $e) { $courseData['closing_time'] = ''; }
    }
@endphp

<div class="admin-page-header">
    <div>
        <h1 class="aph-title">{{ $heading }}</h1>
        <p class="aph-sub">{{ $isEdit ? 'Update school level guidelines, curriculum, rules, and admission details.' : 'Fill in the details to add a new academic level (PG to Grade 12).' }}</p>
    </div>
    <a href="{{ route('course.table') }}" class="btn-admin btn-admin-outline"><i class="bi bi-arrow-left"></i> Back to Levels</a>
</div>

<form action="{{ $formAction }}" enctype="multipart/form-data" method="POST" id="courseForm">
@csrf
<div class="row g-4">

    {{-- ── LEFT: Basic Details ── --}}
    <div class="col-lg-4">
        <div class="admin-card" style="position:sticky;top:80px;">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-mortarboard-fill"></i> Level Structure</span>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label class="admin-label">Program / Level Name <span style="color:#e53e3e">*</span></label>
                    <input type="text" name="name" value="{{ $courseData['name'] }}" class="admin-input" placeholder="e.g. Basic Level Education (Grades 1 to 8)" required>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Academic Tier / Level Category <span style="color:#e53e3e">*</span></label>
                    <select class="admin-select" name="academic_level" required>
                        @php
                            $levels = ['Pre-Primary', 'Basic Level', 'Secondary Level', 'Higher Secondary (+2)', 'Other'];
                        @endphp
                        @foreach($levels as $lvl)
                            <option value="{{ $lvl }}" {{ $courseData['academic_level'] === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Grade Span / Classes</label>
                    <input type="text" name="grade_span" value="{{ $courseData['grade_span'] }}" class="admin-input" placeholder="e.g. Playgroup to UKG, Grades 1 to 8, SEE">
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">Session / Duration <span style="color:#e53e3e">*</span></label>
                            <input type="text" name="duration" value="{{ $courseData['duration'] }}" class="admin-input" placeholder="e.g. Annual Session" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">Term Structure</label>
                            <input type="text" name="semester" value="{{ $courseData['semester'] }}" class="admin-input" placeholder="e.g. Annual / 3 Terms">
                        </div>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Evaluation & Board System</label>
                    <input type="text" name="evaluation_system" value="{{ $courseData['evaluation_system'] }}" class="admin-input" placeholder="e.g. CAS / BLE Board (Grade 8) / SEE / NEB">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Admission Eligibility Criteria</label>
                    <input type="text" name="requirement" value="{{ $courseData['requirement'] }}" class="admin-input" placeholder="e.g. Birth Certificate / Grade 8 BLE / SEE GPA 2.0+">
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">School Start Time</label>
                            <input type="time" name="starting_time" value="{{ $courseData['starting_time'] }}" class="admin-input">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">School End Time</label>
                            <input type="time" name="closing_time" value="{{ $courseData['closing_time'] }}" class="admin-input">
                        </div>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Short Summary Slogan <span style="color:#e53e3e">*</span></label>
                    <textarea name="description" class="admin-textarea" rows="3" placeholder="Brief summary of this academic level..." required>{{ $courseData['description'] }}</textarea>
                </div>

                <hr style="border-color:var(--admin-border);margin:14px 0;">

                <div class="admin-form-group">
                    <label class="admin-label">Cover Image {{ $isEdit ? '' : '*' }}</label>
                    <input type="file" name="image" class="admin-input" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                    <span class="admin-input-hint">Recommended: 800×500px</span>
                    @if($isEdit && $course->image)
                        <div class="mt-2">
                            <img src="{{ asset($course->image) }}" alt="Preview" style="max-height: 60px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        </div>
                    @endif
                </div>

                <div class="admin-form-group mb-0">
                    <label class="admin-label">Gallery Images</label>
                    <input type="file" name="gallery[]" multiple class="admin-input" accept="image/*">
                    <span class="admin-input-hint">Select multiple images if available.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: School Level Content Sections (Curriculum, Rules, Admission, Overview) ── --}}
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-layout-text-window-reverse"></i> Level Content & NEB Guidelines</span>
            </div>
            <div class="admin-card-body">

                {{-- Tab Nav --}}
                <div class="school-tab-nav" id="schoolTabNav">
                    <button type="button" class="school-tab-btn active" data-target="#tab-curriculum">
                        <i class="bi bi-journal-bookmark-fill"></i> 1. Curriculum & Subjects
                    </button>
                    <button type="button" class="school-tab-btn" data-target="#tab-rules">
                        <i class="bi bi-shield-check"></i> 2. Rules & Regulations
                    </button>
                    <button type="button" class="school-tab-btn" data-target="#tab-admission">
                        <i class="bi bi-person-check-fill"></i> 3. Admission Procedure
                    </button>
                    <button type="button" class="school-tab-btn" data-target="#tab-overview">
                        <i class="bi bi-file-text-fill"></i> 4. Level Overview
                    </button>
                </div>

                {{-- Tab 1: Curriculum --}}
                <div id="tab-curriculum" class="school-tab-pane active">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="admin-label mb-0 fw-bold"><i class="bi bi-book me-1 text-primary"></i> Curriculum & Subject Details (CDC & NEB Guidelines)</label>
                        <span class="small text-muted">Subjects, credits, practicals, and learning areas</span>
                    </div>
                    <textarea class="summernote-field" name="curriculum">{{ $courseData['curriculum'] }}</textarea>
                </div>

                {{-- Tab 2: Rules & Regulations --}}
                <div id="tab-rules" class="school-tab-pane">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="admin-label mb-0 fw-bold"><i class="bi bi-shield-exclamation me-1 text-primary"></i> Academic Rules, Attendance & Conduct</label>
                        <span class="small text-muted">Attendance policy (75-80%), uniform, discipline, examination standards</span>
                    </div>
                    <textarea class="summernote-field" name="rules">{{ $courseData['rules'] }}</textarea>
                </div>

                {{-- Tab 3: Admission Procedure --}}
                <div id="tab-admission" class="school-tab-pane">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="admin-label mb-0 fw-bold"><i class="bi bi-pencil-square me-1 text-primary"></i> Admission Procedure & Required Documents</label>
                        <span class="small text-muted">Eligibility, age limits, entrance assessment, required certificates</span>
                    </div>
                    <textarea class="summernote-field" name="admission_procedure">{{ $courseData['admission_procedure'] }}</textarea>
                </div>

                {{-- Tab 4: Level Overview --}}
                <div id="tab-overview" class="school-tab-pane">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="admin-label mb-0 fw-bold"><i class="bi bi-info-circle me-1 text-primary"></i> Detailed Level Overview & Facilities</label>
                        <span class="small text-muted">Comprehensive description, laboratories, extracurriculars, learning environment</span>
                    </div>
                    <textarea class="summernote-field" name="fulldescription">{{ $courseData['fulldescription'] }}</textarea>
                </div>

            </div>
        </div>

        <div style="margin-top:24px;padding-bottom:12px;">
            <button type="submit" class="btn-admin btn-admin-primary" style="padding:12px 36px;font-size:15px;font-weight:600;">
                <i class="bi bi-check-lg"></i> {{ $buttonText }}
            </button>
        </div>
    </div>
</div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab switching
        const tabBtns = document.querySelectorAll('.school-tab-btn');
        const tabPanes = document.querySelectorAll('.school-tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const target = this.getAttribute('data-target');
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                const activePane = document.querySelector(target);
                if (activePane) {
                    activePane.classList.add('active');
                }
            });
        });

        // Initialize Summernote on all content fields
        $('.summernote-field').summernote({
            tabsize: 2,
            height: 380,
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
