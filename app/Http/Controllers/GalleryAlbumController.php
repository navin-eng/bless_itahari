<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;

class GalleryAlbumController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::latest()->get();
        // Auto-fill cover_image for any album that is missing one, or if the current one doesn't exist physically (due to old relative path bug)
        foreach ($albums as $album) {
            $coverMissingOrBroken = false;
            
            if (!$album->cover_image) {
                $coverMissingOrBroken = true;
            } elseif (!file_exists(public_path('backend/images/gallery/' . $album->cover_image))) {
                // If it looks like a local file name but isn't on disk, reset it
                if (!str_contains($album->cover_image, 'http')) {
                    $coverMissingOrBroken = true;
                }
            }

            if ($coverMissingOrBroken) {
                // Find first valid local image
                $first = \App\Models\Gallery::where('album_id', $album->id)
                    ->where('type', 'image')
                    ->whereNotNull('file_path')
                    ->oldest()
                    ->first();
                    
                if ($first) {
                    $album->cover_image = $first->file_path;
                    $album->save();
                } else {
                    // Try to find an image URL fallback if no local files exist
                    $firstUrl = \App\Models\Gallery::where('album_id', $album->id)
                        ->where('type', 'image_url')
                        ->whereNotNull('url')
                        ->oldest()
                        ->first();
                    if ($firstUrl) {
                        $album->cover_image = $firstUrl->url;
                        $album->save();
                    }
                }
            }
        }
        return view('backend.pages.gallery.albums', compact('albums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $album         = new GalleryAlbum();
        $album->name   = $request->name;
        $album->slug   = Str::slug($request->name) . '-' . time();
        $album->status = $request->status ?? 'active';

        if ($request->hasFile('cover_image')) {
            $img       = $request->file('cover_image');
            $extension = $img->getClientOriginalExtension();
            $imageName = Str::random(20) . time() . '.' . $extension;
            // Use public_path() so file lands in the correct public directory
            $img->move(public_path('backend/images/gallery/'), $imageName);
            $album->cover_image = $imageName;
        }

        if ($album->save()) {
            Alert::success('Saved', 'Album created successfully');
        } else {
            Alert::error('Error', 'Could not create album');
        }
        return back();
    }

    public function update(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|in:active,inactive'
        ]);

        $album->name = $request->name;
        $album->status = $request->status;

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Delete old if exists
            if ($album->cover_image && file_exists(public_path('backend/images/gallery/' . $album->cover_image))) {
                unlink(public_path('backend/images/gallery/' . $album->cover_image));
            }

            // Simple move without GD compression for cover (or could use GD if preferred, but standard move is fine here)
            $image->move(public_path('backend/images/gallery/'), $filename);
            $album->cover_image = $filename;
        }

        $album->save();

        return redirect()->back()->with('success', 'Album updated successfully.');
    }

    public function setCover($album_id, $gallery_id)
    {
        $album = GalleryAlbum::findOrFail($album_id);
        $gallery = \App\Models\Gallery::findOrFail($gallery_id);

        if ($gallery->type === 'image' && $gallery->file_path) {
            $album->cover_image = $gallery->file_path;
            $album->save();
            return back()->with('success', 'Album cover updated successfully.');
        } elseif ($gallery->type === 'image_url' && $gallery->url) {
            $album->cover_image = $gallery->url;
            $album->save();
            return back()->with('success', 'Album cover updated successfully.');
        }

        return back()->with('error', 'Selected item cannot be used as cover.');
    }

    public function delete($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        
        if ($album->cover_image) {
            $imagePath = public_path('backend/images/gallery/' . $album->cover_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        // Photos inside the album will cascade delete in DB, but their files will remain.
        // For a full implementation, we should delete the photos' files too.
        $photos = \App\Models\Gallery::where('album_id', $id)->get();
        foreach($photos as $photo) {
            if($photo->file_path) {
                $p = public_path('backend/images/gallery/' . $photo->file_path);
                if (File::exists($p)) File::delete($p);
            }
        }

        $album->delete();
        Alert::success('Success', 'Album deleted');
        return back();
    }
}
