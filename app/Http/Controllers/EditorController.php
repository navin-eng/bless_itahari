<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    //

    public function index()
    {
        $editor = User::where('a_type', 'E')->get();
        return view('backend.pages.editor.table', compact('editor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . rand(0, 9999) . time() . '.' . $extension;
            $image->move('backend/admin/images/', $imageName);
        }

        $editor           = new User();
        $editor->name     = $request->name;
        $editor->email    = $request->email;
        $editor->password = Hash::make($request->password);
        $editor->a_type   = 'E'; // Always Editor — admin cannot be created from this form
        $editor->image    = $imageName ? 'backend/admin/images/' . $imageName : 'backend/admin/images/default-avatar.png';
        $editor->save();

        session()->flash('success', 'New editor created successfully.');
        return redirect()->route('editor.table');
    }

    public function edit($id)
    {
        $editor = User::find($id);
        return view('backend.pages.editor.edit', compact('editor'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $editor = User::find($id);
        if (!$editor) {
            return back()->with('error', 'User not found.');
        }

        $editor->name  = $request->name;
        $editor->email = $request->email;

        // Only update password if one was supplied
        if (!empty($request->password)) {
            $editor->password = Hash::make($request->password);
        }

        // SECURITY: a_type cannot be changed from this form — it stays E (Editor)
        // The original code had a bug: used = (assignment) instead of == (comparison)
        // which silently made every user an Admin. Fixed here.
        $editor->a_type = 'E';

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . rand(0, 9999) . time() . '.' . $extension;
            $image->move('backend/admin/images/', $imageName);
            $editor->image = 'backend/admin/images/' . $imageName;
        }

        $editor->save();
        session()->flash('success', 'Updated successfully.');
        return redirect()->route('editor.table');
    }

    public function delete($id)
    {
        $editor = User::find($id);
        $editor->delete();
        session()->flash('success', 'deleted successfully');
        return redirect('/admin/dashboard/editor/table');
    }
}
