<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;


class TestimonialController extends Controller
{
    public function index()
    {
        $testimonial = Testimonial::orderBy('sort_order', 'asc')->get();
        return view('backend.pages.testimonial.table', compact('testimonial'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:testimonials,id',
        ]);

        foreach ($request->order as $index => $id) {
            Testimonial::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        Cache::forget('home.testimonials');
        return response()->json(['success' => true]);
    }
    public function create()
    {
        return view('backend.pages.testimonial.add');
    }
    public function store(Request $request)
    {
        if ($request->has('testimonials')) {
            $request->validate([
                'testimonials' => 'required|array|min:1',
                'testimonials.*.name' => 'required|string|min:2|max:60',
                'testimonials.*.role' => 'required|string|max:100',
                'testimonials.*.description' => 'required|string',
                'testimonials.*.image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            $maxOrder = Testimonial::max('sort_order') ?? 0;
            foreach ($request->input('testimonials', []) as $index => $item) {
                $testimonial = new Testimonial();
                $testimonial->name = $item['name'];
                $testimonial->role = $item['role'];
                $testimonial->description = $item['description'];
                $testimonial->sort_order = ++$maxOrder;

                if ($request->hasFile("testimonials.$index.image")) {
                    $testimonial->image = $this->uploadTestimonialImage($request->file("testimonials.$index.image"));
                }

                $testimonial->save();
            }

            Cache::forget('home.testimonials');

            Alert::success('Saved', 'Testimonials saved successfully');
            return back();
        }

        $request->validate([
            'name' => 'required|min:2|max:60',
            'description' => 'required',
            'role' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);
        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->role = $request->role;
        $testimonial->description = $request->description;
        $testimonial->sort_order = (Testimonial::max('sort_order') ?? 0) + 1;
        if ($request->hasFile('image')) {
            $testimonial->image = $this->uploadTestimonialImage($request->file('image'));
        }
        $save = $testimonial->save();
        if ($save) {
            Cache::forget('home.testimonials');
            Alert::success('Saved', 'Testimonial saved successfully');
            return back();
        } else {
            Alert::error('Oops', 'Testimonial could not be saved');
            return back();
        }
    }
    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        if (is_null($testimonial)) {
            Alert::error('Oops', 'Testimonial not found');
            return redirect()->route('testimonial.table');
        }

        return view('backend.pages.testimonial.edit', compact('testimonial'));
    }

    public function status($id)
    {
        $testimonial = Testimonial::find($id);
        if (is_null($testimonial)) {
            Alert::error('Oops', 'Testimonial not found');
        } else {
            $testimonial->status = $testimonial->status == 1 ? null : 1;
            $testimonial->save();
            Cache::forget('home.testimonials');

            Alert::success('Updated', 'Status updated successfully');
        }
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:2|max:60',
            'description' => 'required',
            'role' => 'required|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $testimonial = Testimonial::find($id);
        if (is_null($testimonial)) {
            Alert::error('Oops', 'Testimonial not found');
            return redirect()->route('testimonial.table');
        }

        $testimonial->name = $request->name;
        $testimonial->role = $request->role;
        $testimonial->description = $request->description;

        if ($request->hasFile('image')) {
            if ($testimonial->image && file_exists(public_path($testimonial->image))) {
                @unlink(public_path($testimonial->image));
            }
            $testimonial->image = $this->uploadTestimonialImage($request->file('image'));
        }

        $save = $testimonial->save();
        if ($save) {
            Cache::forget('home.testimonials');
            Alert::success('Saved', 'Testimonial updated successfully');
            return redirect()->route('testimonial.table');
        } else {
            Alert::error('Oops', 'Testimonial could not update');
            return redirect()->route('testimonial.table');
        }
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            if ($testimonial->image && file_exists(public_path($testimonial->image))) {
                @unlink(public_path($testimonial->image));
            }
            $testimonial->delete();
            Cache::forget('home.testimonials');
            Alert::success('Deleted', 'Testimonial deleted successfully');
        } else {
            Alert::error('Oops', 'Testimonial not found');
        }
        return redirect()->route('testimonial.table');
    }

    private function uploadTestimonialImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        $imageName = Str::random(20) . time() . '.' . $extension;
        $destinationPath = public_path('backend/images/testimonials');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $image->move($destinationPath, $imageName);

        return 'backend/images/testimonials/' . $imageName;
    }
}
