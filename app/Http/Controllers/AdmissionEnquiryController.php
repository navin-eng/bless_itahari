<?php

namespace App\Http\Controllers;

use App\Models\AdmissionEnquiry;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdmissionEnquiryController extends Controller
{
    public function index()
    {
        $enquiries = AdmissionEnquiry::latest()->get();
        return view('backend.pages.admissions.index', compact('enquiries'));
    }

    public function show($id)
    {
        $enquiry = AdmissionEnquiry::findOrFail($id);
        
        // Mark as reviewed if it's pending and being viewed
        if ($enquiry->status === 'pending') {
            $enquiry->status = 'reviewed';
            $enquiry->save();
        }
        
        return view('backend.pages.admissions.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,contacted,rejected'
        ]);

        $enquiry = AdmissionEnquiry::findOrFail($id);
        $enquiry->status = $request->status;
        $enquiry->save();

        Alert::success('Updated', 'Admission Enquiry status updated!');
        return back();
    }

    public function destroy($id)
    {
        $enquiry = AdmissionEnquiry::findOrFail($id);
        $enquiry->delete();
        
        Alert::success('Deleted', 'Admission Enquiry deleted successfully.');
        return redirect()->route('admin.admissions.index');
    }
}
