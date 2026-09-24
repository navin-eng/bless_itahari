<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;


class BannerController extends Controller
{
    public function index()
    {
        $banner = Banner::orderBy('sort_order', 'asc')->get();
        return view('backend.pages.banner.table', compact('banner'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:banners,id',
        ]);

        foreach ($request->order as $index => $id) {
            Banner::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        Cache::forget('home.banners');
        return response()->json(['success' => true]);
    }
    public function create()
    {
        return view('backend.pages.banner.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title1' => 'required|min:2',
            'title2' => 'required|min:2',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $banner = new Banner();
        $banner->title1 = ucwords($request->title1);
        $banner->title2 = ucwords($request->title2);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            
            $destinationPath = public_path('backend/images/banners');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $imageName);
            $banner->image = 'backend/images/banners/' . $imageName;
        }

        $banner->status = 1;
        $banner->sort_order = (Banner::max('sort_order') ?? 0) + 1;
        $save = $banner->save();

        if ($save) {
            Cache::forget('home.banners');
            Alert::success('Saved', 'Banner added successfully');
            return back();
        } else {
            Alert::error('Oops', 'Banner could not be saved');
            return back();
        }
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        if (is_null($banner)) {
            Alert::error('Oops', 'Banner not found');
            return redirect()->route('banner.table');
        }

        return view('backend.pages.banner.edit', compact('banner'));
    }

    public function status($id)
    {
        $banner = Banner::find($id);
        if (is_null($banner)) {
            Alert::error('Oops', 'Banner not found');
        } else {
            $banner->status = $banner->status == 1 ? 0 : 1;
            $banner->save();
            Cache::forget('home.banners');

            Alert::success('Updated', 'Banner status ' . ($banner->status ? 'activated' : 'deactivated'));
        }
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title1' => 'required|min:2',
            'title2' => 'required|min:2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $banner = Banner::find($id);
        if (is_null($banner)) {
            Alert::error('Oops', 'Banner not found');
            return redirect()->route('banner.table');
        }

        $banner->title1 = ucwords($request->title1);
        $banner->title2 = ucwords($request->title2);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;

            $destinationPath = public_path('backend/images/banners');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if ($banner->image && file_exists(public_path($banner->image))) {
                @unlink(public_path($banner->image));
            }

            $image->move($destinationPath, $imageName);
            $banner->image = 'backend/images/banners/' . $imageName;
        }

        $save = $banner->save();

        if ($save) {
            Cache::forget('home.banners');
            Alert::success('Saved', 'Banner updated successfully');
            return redirect()->route('banner.table');
        } else {
            Alert::error('Oops', 'Banner could not be updated');
            return redirect()->route('banner.table');
        }
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            if ($banner->image && file_exists(public_path($banner->image))) {
                @unlink(public_path($banner->image));
            }
            $banner->delete();
            Cache::forget('home.banners');
            Alert::success('Deleted', 'Banner deleted successfully');
        } else {
            Alert::error('Oops', 'Banner not found');
        }
        return redirect()->route('banner.table');
    }
}
