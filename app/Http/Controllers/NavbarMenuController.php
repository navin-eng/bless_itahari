<?php

namespace App\Http\Controllers;

use App\Models\NavbarMenu;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class NavbarMenuController extends Controller
{
    public function index()
    {
        $menus = NavbarMenu::orderBy('order', 'asc')->get();
        return view('backend.pages.navbar_menu.table', compact('menus'));
    }

    public function create()
    {
        return view('backend.pages.navbar_menu.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|min:2',
            'url'   => 'nullable|string',
            'type'  => 'required|in:standard,course_dropdown',
            'order' => 'required|integer',
        ]);

        $menu = new NavbarMenu();
        $menu->name  = ucwords($request->name);
        $menu->url   = $request->url;
        $menu->type  = $request->type;
        $menu->order = $request->order;
        $menu->status = 1;
        $saved = $menu->save();

        if ($saved) {
            Alert::success('Saved', 'Navbar Menu item saved successfully');
            return redirect()->route('navbar_menu.table');
        }

        Alert::error('Oops', 'Menu could not be saved');
        return back();
    }

    public function edit($id)
    {
        $menu = NavbarMenu::findOrFail($id);
        return view('backend.pages.navbar_menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|min:2',
            'url'   => 'nullable|string',
            'type'  => 'required|in:standard,course_dropdown',
            'order' => 'required|integer',
        ]);

        $menu = NavbarMenu::findOrFail($id);
        $menu->name  = ucwords($request->name);
        $menu->url   = $request->url;
        $menu->type  = $request->type;
        $menu->order = $request->order;
        $saved = $menu->update();

        if ($saved) {
            Alert::success('Updated', 'Navbar Menu item updated successfully');
            return redirect()->route('navbar_menu.table');
        }

        Alert::error('Oops', 'Menu could not be updated');
        return back();
    }

    public function status($id)
    {
        $menu = NavbarMenu::findOrFail($id);
        $menu->status = $menu->status == 1 ? 0 : 1;
        $menu->update();
        Alert::success('Updated', 'Menu status changed');
        return back();
    }

    public function destroy($id)
    {
        $menu = NavbarMenu::findOrFail($id);

        $defaultNames = ['home', 'about us', 'about', 'contact', 'contact us', 'gallery', 'calendar', 'faculties', 'academics'];
        $defaultUrls = ['', '/', 'about/us', '/about/us', 'contact', '/contact', 'gallery', '/gallery', 'calendar', '/calendar', 'member', '/member', 'course', '/course'];

        if (in_array(strtolower(trim($menu->name)), $defaultNames) || in_array(strtolower(trim($menu->url)), $defaultUrls)) {
            Alert::error('Restricted', 'This is a default menu item and cannot be deleted.');
            return redirect()->route('navbar_menu.table');
        }

        $menu->delete();
        Alert::success('Deleted', 'Menu deleted successfully');
        return redirect()->route('navbar_menu.table');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:navbar_menus,id',
        ]);

        foreach ($request->ids as $index => $id) {
            NavbarMenu::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
