<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AdmissionEnquiry;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdmissionController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::current();
        
        if ($settings->admissions_open) {
            $courses = \App\Models\Course::where('status', 1)->get();
            return view('frontend.pages.apply_open', compact('settings', 'courses'));
        }

        return view('frontend.pages.apply_closed', compact('settings'));
    }

    public function store(Request $request)
    {
        $settings = SiteSetting::current();
        if (!$settings->admissions_open) {
            Alert::error('Closed', 'Admissions are currently closed.');
            return back();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'course_name' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        AdmissionEnquiry::create($request->all());

        Alert::success('Success', 'Your application has been submitted successfully! We will contact you soon.');
        return redirect()->route('home');
    }
}
