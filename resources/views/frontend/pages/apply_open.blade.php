@extends('frontend.layout.master')

@section('frontend-content')

<!-- Page Banner Section -->
<section class="page-banner" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('{{ asset('frontend/images/banner2.jpg') }}') center/cover; padding: 100px 0; text-align: center; color: white;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3" data-aos="fade-down">Apply Now</h1>
        <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">Take the first step towards your future.</p>
    </div>
</section>

<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;" data-aos="fade-up">
                    <div class="card-header bg-primary text-white p-4 text-center border-0">
                        <h3 class="mb-1"><i class="bi bi-file-earmark-person"></i> Admission Application</h3>
                        <p class="mb-0 text-white-50">Please fill out all the required fields correctly.</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('apply.store') }}" method="POST">
                            @csrf
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg" required placeholder="John Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control form-control-lg" required placeholder="+977 ...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg" required placeholder="john@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Program/Course Applying For</label>
                                    <select name="course_name" class="form-select form-select-lg">
                                        <option value="">Select a Program</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->name }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Previous School / Academic Background</label>
                                    <input type="text" name="previous_school" class="form-control form-control-lg" placeholder="Name of your last school or college">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Message or Questions (Optional)</label>
                                    <textarea name="message" class="form-control form-control-lg" rows="4" placeholder="Any additional information..."></textarea>
                                </div>
                                <div class="col-12 mt-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5" style="border-radius: 30px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                        <i class="bi bi-send"></i> Submit Application
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
