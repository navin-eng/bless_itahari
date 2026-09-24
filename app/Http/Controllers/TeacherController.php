<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;


class TeacherController extends Controller
{
    public function index()
    {
        $teacher = Teacher::orderBy('sort_order', 'asc')->get();
        return view('backend.pages.teacher.table', compact('teacher'));
    }
    public function create()
    {
        return view('backend.pages.teacher.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:30',
            'role' => 'required',
            'staff_type' => 'required|in:teaching,non_teaching,administrative',
            'facebook_link' => 'nullable',
            'sort_order' => 'nullable|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->role = $request->role;
        $teacher->staff_type = $request->staff_type;
        $teacher->facebook_link = $request->facebook_link;
        $teacher->sort_order = $request->sort_order ?? 0;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $destinationPath = public_path('backend/images/teachers');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $imageName);
            $teacher->image = 'backend/images/teachers/' . $imageName;
        }
        $save = $teacher->save();
        if ($save == true) {

            Alert::success('Saved', 'teacher saved successfully');
            return back();
        } else {
            Alert::error('oops', 'teacher couldnot saved');
            return back();
        }
    }
    public function edit($id)
    {
        $teacher = Teacher::find($id);
        if(is_null($teacher))
        {
            Alert::error('oops','Something went wrong');
            return redirect()->route('teacher.table');
        }
        else
        {
            return view('backend.pages.teacher.edit',compact('teacher'));
        }
    }
    public function status($id)
    {
        $teacher = Teacher::find($id);
        if (is_null($teacher)) {
            Alert::error('oops', 'We Couldnot find teacher');
        } else {
            if ($teacher->status == 1) {
                $teacher->status = null;
                $teacher->update();
                Alert::success('Updated', 'Status Deactivate');
                return back();
            } else {
                $teacher->status = 1;
                $teacher->update();
                Alert::success('Updated', 'Status Activate');
                return back();
            }
        }
    }
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|min:2|max:30',
            'role' => 'required',
            'staff_type' => 'required|in:teaching,non_teaching,administrative',
            'sort_order' => 'nullable|integer',
            'facebook_link' => 'nullable',
        ]);
        $teacher = Teacher::find($id);
        if (!$teacher) {
            Alert::error('oops', 'Teacher not found');
            return redirect()->route('teacher.table');
        }
        $teacher->name = $request->name;
        $teacher->role = $request->role;
        $teacher->staff_type = $request->staff_type;
        $teacher->facebook_link = $request->facebook_link;
        $teacher->sort_order = $request->sort_order ?? 0;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            $destinationPath = public_path('backend/images/teachers');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if ($teacher->image && file_exists(public_path($teacher->image))) {
                @unlink(public_path($teacher->image));
            }
            $image->move($destinationPath, $imageName);
            $teacher->image = 'backend/images/teachers/' . $imageName;
        }
        $save = $teacher->update();
        if ($save == true) {

            Alert::success('Saved', 'teacher update successfully');
            return redirect()->route('teacher.table');
        } else {
            Alert::error('oops', 'teacher couldnot update');
            return redirect()->route('teacher.table');
        }
    }
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            if ($teacher->image && file_exists(public_path($teacher->image))) {
                @unlink(public_path($teacher->image));
            }
            $teacher->delete();
            Alert::success('Deleted', 'teacher deleted');
        } else {
            Alert::error('oops', 'Teacher not found');
        }
        return redirect()->route('teacher.table');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:teachers,id',
            'order.*.position' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            Teacher::where('id', $item['id'])->update(['sort_order' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }
}
