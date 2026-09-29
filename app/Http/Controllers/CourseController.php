<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
{
    protected function rules($courseId = null, $hasImageUrl = false)
    {
        $nameRule = 'required|min:2|max:120|unique:courses,name' . ($courseId ? ',' . $courseId : '');

        return [
            'name' => $nameRule,
            'academic_level' => 'required|string|max:100',
            'grade_span' => 'nullable|string|max:100',
            'duration' => 'required|string|max:100',
            'semester' => 'nullable|string|max:100',
            'requirement' => 'nullable|string|max:255',
            'evaluation_system' => 'nullable|string|max:255',
            'curriculum' => 'nullable|string',
            'rules' => 'nullable|string',
            'admission_procedure' => 'nullable|string',
            'starting_time' => 'nullable',
            'closing_time' => 'nullable',
            'description' => 'required|string|max:1000',
            'fulldescription' => 'nullable|string',
            'image' => ($courseId || $hasImageUrl) ? 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120' : 'required_without:image_url|nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ];
    }

    public function index()
    {
        $course = Course::all();
        return view('backend.pages.course.table', compact('course'));
    }
    public function create()
    {
        return view('backend.pages.course.add');
    }
    public function store(Request $request)
    {
        $request->validate($this->rules(null, $request->filled('image_url')), [
            'name.unique' => 'A course or level with this name already exists in the system. Please enter a unique name (e.g. Science - Grade 11).',
            'image.required_without' => 'Please select an image from the Media Library or upload a new image file.',
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->academic_level = $request->academic_level;
        $course->grade_span = $request->grade_span ?: '';

        // Auto-generate unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $count = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }
        $course->slug = $slug;

        $course->duration = $request->duration;
        $course->semester = $request->semester ?: ($request->academic_level ? $request->academic_level . ' (Annual)' : 'Annual Session');
        $course->requirement = $request->requirement ?: 'As per School & NEB Criteria';
        $course->evaluation_system = $request->evaluation_system ?: '';
        $course->curriculum = $request->curriculum ?: '';
        $course->rules = $request->rules ?: '';
        $course->admission_procedure = $request->admission_procedure ?: '';
        $course->starting_time = $request->starting_time ? Carbon::parse($request->starting_time)->format('g:i A') : '';
        $course->closing_time = $request->closing_time ? Carbon::parse($request->closing_time)->format('g:i A') : '';
        $course->description = $request->description;
        $course->fulldescription = $request->fulldescription ?: '';
        $course->status = 1;
        $course->gallery = '[]';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/courses/', $imageName);
            $course->image = 'backend/images/courses/' . $imageName;
        } elseif ($request->filled('image_url')) {
            $course->image = $request->image_url;
        }
        if ($request->hasFile('gallery')) {
            $images = [];
            foreach ($request->file('gallery') as $img) {
                $extension = $img->getClientOriginalExtension();
                $imageName = Str::random(20) . time() . '.' . $extension;
                $img->move('backend/images/courses/', $imageName);
                $images[] = $imageName;
            }
            $course->gallery = json_encode($images);
        }
        $save = $course->save();
        if ($save == true) {
            Cache::forget('home.courses');
            Cache::forget('courses.all');

            Alert::success('Saved', 'Course saved successfully');
            return redirect()->route('course.table')->with('success', 'Academic level saved successfully.');
        } else {
            Alert::error('Oops', 'Course could not be saved');
            return back()->with('error', 'Course could not be saved.');
        }
    }
    public function edit($id)
    {
        $course = Course::find($id);
        if(is_null($course))
        {
            Alert::error('Oops','Academic level not found');
            return redirect()->route('course.table')->with('error', 'Academic level not found.');
        }
        else
        {
            return view('backend.pages.course.edit',compact('course'));
        }
    }
    public function status($id)
    {
        $course = Course::find($id);
        if (is_null($course)) {
            Alert::error('Oops', 'Could not find course');
            return back()->with('error', 'Academic level not found.');
        } else {
            $newStatus = ($course->status == 1) ? 0 : 1;
            $course->status = $newStatus;
            $course->save();
            Cache::forget('home.courses');
            Cache::forget('courses.all');

            $statusText = $newStatus == 1 ? 'Activated' : 'Deactivated';
            Alert::success('Updated', "Status {$statusText}");
            return back()->with('success', "Academic level {$statusText} successfully.");
        }
    }
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            Alert::error('Oops', 'Academic level not found.');
            return redirect()->route('course.table')->with('error', 'Academic level not found.');
        }

        $request->validate($this->rules($id, $request->filled('image_url')), [
            'name.unique' => 'A course or level with this name already exists. Please enter a unique name.',
        ]);

        $course->name = $request->name;
        $course->academic_level = $request->academic_level;
        $course->grade_span = $request->grade_span ?: '';
        $course->slug = Str::slug($request->name);
        $course->duration = $request->duration;
        $course->semester = $request->semester ?: ($request->academic_level ? $request->academic_level . ' (Annual)' : 'Annual Session');
        $course->requirement = $request->requirement ?: 'As per School & NEB Criteria';
        $course->evaluation_system = $request->evaluation_system ?: '';
        $course->curriculum = $request->curriculum ?: '';
        $course->rules = $request->rules ?: '';
        $course->admission_procedure = $request->admission_procedure ?: '';
        if($request->starting_time != null) {
            $course->starting_time = Carbon::parse($request->starting_time)->format('g:i A');
        } else {
            $course->starting_time = '';
        }
        if($request->closing_time != null) {
            $course->closing_time = Carbon::parse($request->closing_time)->format('g:i A');
        } else {
            $course->closing_time = '';
        }
        $course->description = $request->description;
        $course->fulldescription = $request->fulldescription ?: '';

        if ($request->hasFile('image')) {
            // Delete old image if custom
            if (!empty($course->image) && file_exists(public_path($course->image)) && !str_contains($course->image, 'default')) {
                @unlink(public_path($course->image));
            }
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $image->move('backend/images/courses/', $imageName);
            $course->image = 'backend/images/courses/' . $imageName;
        } elseif ($request->filled('image_url')) {
            $course->image = $request->image_url;
        }

        if ($request->hasFile('gallery')) {
            $existingGallery = [];
            if (!empty($course->gallery)) {
                $decoded = json_decode($course->gallery, true);
                if (is_array($decoded)) {
                    $existingGallery = $decoded;
                }
            }
            foreach ($request->file('gallery') as $img) {
                $extension = $img->getClientOriginalExtension();
                $imageName = Str::random(20) . time() . '.' . $extension;
                $img->move('backend/images/courses/', $imageName);
                $existingGallery[] = $imageName;
            }
            $course->gallery = json_encode(array_values($existingGallery));
        }

        $save = $course->save();
        if ($save == true) {
            Cache::forget('home.courses');
            Cache::forget('courses.all');

            Alert::success('Saved', 'Course updated successfully');
            return redirect()->route('course.table')->with('success', 'Academic level updated successfully.');
        } else {
            Alert::error('Oops', 'Course could not be updated');
            return redirect()->route('course.table')->with('error', 'Academic level could not be updated.');
        }
    }
    public function destroy(Request $request, $id)
    {
        $course = Course::find($id);
        if ($course) {
            $courseName = $course->name;

            // Delete cover image if custom
            if (!empty($course->image) && file_exists(public_path($course->image)) && !str_contains($course->image, 'default')) {
                @unlink(public_path($course->image));
            }

            // Delete gallery images
            if (!empty($course->gallery)) {
                $gallery = json_decode($course->gallery, true);
                if (is_array($gallery)) {
                    foreach ($gallery as $gImg) {
                        $path = public_path('backend/images/courses/' . $gImg);
                        if (file_exists($path)) {
                            @unlink($path);
                        }
                    }
                }
            }

            $course->delete();
            Cache::forget('home.courses');
            Cache::forget('courses.all');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Academic level '{$courseName}' was successfully deleted."
                ]);
            }

            Alert::success('Deleted', "Academic level '{$courseName}' deleted successfully.");
            return redirect()->route('course.table')->with('success', "Academic level '{$courseName}' was deleted successfully.");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Academic level not found.'
            ], 404);
        }

        Alert::error('Oops', 'Course not found');
        return redirect()->route('course.table')->with('error', 'Academic level not found.');
    }

    public function galleryDelete($id, $index)
    {
        $course = Course::find($id);
        if (!$course) {
            Alert::error('Oops', 'Academic level not found');
            return back()->with('error', 'Academic level not found.');
        }

        $gallery = json_decode($course->gallery, true);
        if (!is_array($gallery)) {
            $gallery = [];
        }

        if (isset($gallery[$index])) {
            $fileName = $gallery[$index];
            $path = public_path('backend/images/courses/' . $fileName);
            if (file_exists($path)) {
                @unlink($path);
            }
            array_splice($gallery, $index, 1);
            $course->gallery = json_encode(array_values($gallery));
            $course->save();
            Cache::forget('home.courses');
            Cache::forget('courses.all');

            Alert::success('Success', 'Gallery image deleted.');
            return back()->with('success', 'Gallery image deleted.');
        }

        Alert::error('Oops', 'Image not found in gallery.');
        return back()->with('error', 'Image not found in gallery.');
    }

    public function seedDefaults()
    {
        try {
            (new \Database\Seeders\SchoolLevelsSeeder())->run();
            Cache::forget('home.courses');
            Cache::forget('courses.all');
            Alert::success('Success', 'Standard Nepal School levels (PG to Grade 12) have been loaded successfully.');
            return redirect()->route('course.table')->with('success', 'Standard Nepal School levels loaded successfully.');
        } catch (\Throwable $e) {
            Alert::error('Error', 'Could not load levels: ' . $e->getMessage());
            return redirect()->route('course.table')->with('error', 'Could not load levels: ' . $e->getMessage());
        }
    }
}

