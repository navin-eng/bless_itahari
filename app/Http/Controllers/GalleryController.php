<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
class GalleryController extends Controller
{
    public function index()
    {
        // Now fetching individually structured gallery rows.
        $gallery = Gallery::latest()->get();
        $albums = \App\Models\GalleryAlbum::all();
        
        // Auto-repair missing or broken album covers
        foreach ($albums as $album) {
            $coverBroken = false;
            if (!$album->cover_image) {
                $coverBroken = true;
            } elseif (!file_exists(public_path('backend/images/gallery/' . $album->cover_image))) {
                if (!str_contains($album->cover_image, 'http')) {
                    $coverBroken = true;
                }
            }

            if ($coverBroken) {
                $first = Gallery::where('album_id', $album->id)
                    ->where('type', 'image')
                    ->whereNotNull('file_path')
                    ->oldest()
                    ->first();
                if ($first) {
                    $album->cover_image = $first->file_path;
                    $album->save();
                } else {
                    $firstUrl = Gallery::where('album_id', $album->id)
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

        return view('backend.pages.gallery.table', compact('gallery', 'albums'));
    }

    /**
     * Download an external image (e.g. Facebook CDN) and save it locally.
     * Returns the saved filename or null on failure.
     */
    private function downloadExternalImage(string $url): ?string
    {
        try {
            $ctx = stream_context_create([
                'http' => [
                    'timeout'        => 15,
                    'follow_location' => 1,
                    'user_agent'     => 'Mozilla/5.0 (compatible; ImageFetcher/1.0)',
                    'max_redirects'  => 5,
                ],
                'ssl' => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $rawData = @file_get_contents($url, false, $ctx);
            if (!$rawData || strlen($rawData) < 100) {
                return null;
            }

            // Detect image type from content
            $finfo    = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($rawData);
            $extMap   = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
            $ext      = $extMap[$mimeType] ?? 'jpg';

            $imageName = Str::random(20) . time() . '.' . $ext;
            $destPath  = public_path('backend/images/gallery/' . $imageName);

            file_put_contents($destPath, $rawData);
            return $imageName;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Auto-set album cover if the album doesn't have one yet.
     */
    private function autoSetAlbumCover(int $albumId, string $filePath): void
    {
        $album = \App\Models\GalleryAlbum::find($albumId);
        if ($album && !$album->cover_image) {
            $album->cover_image = $filePath;
            $album->save();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'album_id' => 'nullable|exists:gallery_albums,id',
                'type'     => 'required|in:image,image_url,video_url',
                'caption'  => 'nullable|string',
            ]);

            $albumId = $request->album_id ?: null;

            if ($request->type === 'image') {
                // ── Uploaded files ──────────────────────────────────────────
                $request->validate([
                    'gallery'   => 'required|array|min:1',
                    'gallery.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
                ]);

                if ($request->hasFile('gallery')) {
                    foreach ($request->file('gallery') as $img) {
                        $extension = $img->getClientOriginalExtension();
                        $imageName = Str::random(20) . time() . '.' . $extension;
                        $tempPath  = $img->getPathname();
                        $destPath  = public_path('backend/images/gallery/' . $imageName);

                        if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                            $source = imagecreatefromjpeg($tempPath);
                            imagejpeg($source, $destPath, 75);
                            imagedestroy($source);
                        } elseif (strtolower($extension) == 'png') {
                            $source = imagecreatefrompng($tempPath);
                            imagepng($source, $destPath, 8);
                            imagedestroy($source);
                        } else {
                            $img->move('backend/images/gallery/', $imageName);
                        }

                        $galleryItem            = new Gallery();
                        $galleryItem->album_id  = $albumId;
                        $galleryItem->type      = 'image';
                        $galleryItem->file_path = $imageName;
                        $galleryItem->caption   = $request->caption;
                        $galleryItem->save();

                        // Auto-set cover for the album
                        if ($albumId) {
                            $this->autoSetAlbumCover($albumId, $imageName);
                        }
                    }
                }

            } elseif ($request->type === 'image_url') {
                // ── External Image URL (Facebook, etc.) → download locally ──
                $request->validate(['url' => 'required|string']);

                $downloadedFile = $this->downloadExternalImage($request->url);

                if ($downloadedFile) {
                    // Successfully downloaded — store as a local image
                    $galleryItem            = new Gallery();
                    $galleryItem->album_id  = $albumId;
                    $galleryItem->type      = 'image'; // stored locally now
                    $galleryItem->file_path = $downloadedFile;
                    $galleryItem->caption   = $request->caption;
                    $galleryItem->save();

                    // Auto-set cover for the album
                    if ($albumId) {
                        $this->autoSetAlbumCover($albumId, $downloadedFile);
                    }
                } else {
                    // Download failed — fall back to storing URL as-is
                    $galleryItem           = new Gallery();
                    $galleryItem->album_id = $albumId;
                    $galleryItem->type     = 'image_url';
                    $galleryItem->url      = $request->url;
                    $galleryItem->caption  = $request->caption;
                    $galleryItem->save();
                }

            } else {
                // ── Video URL ────────────────────────────────────────────────
                $request->validate(['url' => 'required|string']);

                $galleryItem           = new Gallery();
                $galleryItem->album_id = $albumId;
                $galleryItem->type     = 'video_url';
                $galleryItem->url      = $request->url;
                $galleryItem->caption  = $request->caption;
                $galleryItem->save();
            }

            Alert::success('Saved', 'Gallery item(s) saved successfully');
            return back();

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            Alert::error('Validation Error', $firstError);
            return back();
        } catch (\Exception $e) {
            Alert::error('Server Error', $e->getMessage());
            return back();
        }
    }


    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->caption = $request->caption;
        $gallery->album_id = $request->album_id;
        $gallery->save();

        Alert::success('Updated', 'Item updated successfully!');
        return back();
    }

    public function cropImage(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $request->validate([
            'cropped_image' => 'required|string',
        ]);

        if ($gallery->type !== 'image' || !$gallery->file_path) {
            return back()->with('error', 'Only uploaded images can be cropped.');
        }

        // The cropped image comes as a base64 data URL. Fix any spaces that were converted from '+' during POST.
        $base64Data = str_replace(' ', '+', $request->cropped_image);
        
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                return back()->with('error', 'Invalid image type for cropping.');
            }
            
            $base64Data = base64_decode($base64Data);
            
            if ($base64Data === false) {
                return back()->with('error', 'Base64 decode failed.');
            }
        } else {
            return back()->with('error', 'Invalid base64 string.');
        }

        // Save over the original file
        $filePath = public_path('backend/images/gallery/' . $gallery->file_path);
        
        // Use GD to save the cropped image properly instead of raw file_put_contents
        $image = imagecreatefromstring($base64Data);
        if ($image !== false) {
            if ($type == 'png') {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, $filePath, 9);
            } else {
                imagejpeg($image, $filePath, 90);
            }
            imagedestroy($image);
        } else {
            // fallback
            file_put_contents($filePath, $base64Data);
        }

        // Touch the model to update the updated_at timestamp (used for cache busting)
        $gallery->touch();

        Alert::success('Success', 'Image cropped successfully!');
        return back();
    }

    public function galleryDelete($id)
    {
        $galleryItem = Gallery::findOrFail($id);
        
        if ($galleryItem->type === 'image' && $galleryItem->file_path) {
            $imagePath = public_path('backend/images/gallery/' . $galleryItem->file_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        $galleryItem->delete();
        Alert::success('Success','Item Deleted');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:galleries,id',
        ]);

        $items = Gallery::whereIn('id', $request->ids)->get();
        $count = 0;

        foreach ($items as $item) {
            if ($item->type === 'image' && $item->file_path) {
                $path = public_path('backend/images/gallery/' . $item->file_path);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            $item->delete();
            $count++;
        }

        Alert::success('Deleted', $count . ' item(s) deleted successfully.');
        return back();
    }
}
