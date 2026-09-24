<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\AboutUsFaq;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AboutUsController extends Controller
{
    public function create()
    {
        $aboutus = AboutUs::first();
        $faqs = AboutUsFaq::orderBy('sort_order')->get();
        $siteSettings = SiteSetting::current();

        return view('backend.pages.aboutus.add', compact('aboutus', 'faqs', 'siteSettings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desc' => 'required',
        ]);
        $condition = DB::table('about_us')->count();
        if($condition == 0)
        {
            $aboutus = new AboutUs();
            $aboutus->desc = $request->desc;
            $aboutus->save();
            Alert::success('Saved', 'aboutus saved successfully');
            return back();
        }
        else
        {
            Alert::error('error','Not Allowed');
        }

    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'desc' => 'required',
        ]);
        $aboutus = AboutUs::find($id);
        $aboutus->desc = $request->desc;
        $aboutus->update();
        Alert::success('Saved', 'aboutus updated successfully');
        return back();

    }

    public function updateStructured(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('aboutus.add');
        }

        $settings = SiteSetting::first();
        if (!$settings) {
            $settings = SiteSetting::create([]);
        }

        $fields = [
            'about_layout', 'about_principal_name', 'about_principal_designation',
            'about_principal_message', 'about_mission', 'about_vision',
            'about_established_year', 'about_affiliation', 'about_intro',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $settings->$field = $request->$field;
            }
        }

        // Handle core values (JSON)
        if ($request->has('about_values')) {
            $values = array_filter($request->about_values ?? []);
            $settings->about_values = !empty($values) ? json_encode(array_values($values)) : null;
        }

        // Handle image uploads
        $imageFields = ['about_hero_image', 'about_school_image', 'about_principal_image'];
        foreach ($imageFields as $imageField) {
            if ($request->hasFile($imageField)) {
                $file = $request->file($imageField);
                $filename = $imageField . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('backend/images/about'), $filename);
                $settings->$imageField = 'backend/images/about/' . $filename;
            }
        }

        // Handle image removals
        foreach ($imageFields as $imageField) {
            if ($request->has('remove_' . $imageField) && $request->input('remove_' . $imageField)) {
                if ($settings->$imageField && file_exists(public_path($settings->$imageField))) {
                    @unlink(public_path($settings->$imageField));
                }
                $settings->$imageField = null;
            }
        }

        $settings->save();
        
        // Force flush the site settings cache
        \Illuminate\Support\Facades\Cache::forget('site_settings.current');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        if ($request->has('desc')) {
            $aboutus = \App\Models\AboutUs::first();
            if (!$aboutus) {
                $aboutus = new \App\Models\AboutUs();
            }
            $aboutus->desc = $request->desc ?? '';
            $aboutus->save();
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Layout updated successfully']);
        }

        Alert::success('Saved', 'About Us page updated successfully');
        return back();
    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        AboutUsFaq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order,
            'status' => 1,
        ]);

        Alert::success('Saved', 'FAQ added successfully');

        return back();
    }

    public function faqUpdate(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        $faq = AboutUsFaq::findOrFail($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order,
        ]);

        Alert::success('Updated', 'FAQ updated successfully');

        return back();
    }

    public function faqStatus($id)
    {
        $faq = AboutUsFaq::findOrFail($id);
        $faq->status = $faq->status ? 0 : 1;
        $faq->save();

        Alert::success('Updated', 'FAQ status changed');

        return back();
    }

    public function faqDestroy($id)
    {
        AboutUsFaq::findOrFail($id)->delete();

        Alert::success('Deleted', 'FAQ deleted successfully');

        return back();
    }
}
