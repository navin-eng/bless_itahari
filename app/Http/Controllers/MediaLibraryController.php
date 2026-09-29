<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MediaLibraryController extends Controller
{
    public function index(Request $request)
    {
        $scanDirectories = [
            public_path('backend/images/uploads'),
            public_path('backend/images/courses'),
            public_path('backend/images/notices'),
            public_path('backend/images/banners'),
            public_path('backend/images/teachers'),
            public_path('backend/images/events'),
            public_path('backend/images/messages'),
            public_path('backend/images/settings'),
            public_path('backend/images'),
            public_path('uploads'),
            public_path('frontend/images'),
            public_path('frontend/image'),
            storage_path('app/public'),
        ];

        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif'];
        $images = [];

        foreach ($scanDirectories as $dir) {
            if (File::exists($dir) && File::isDirectory($dir)) {
                try {
                    $files = File::files($dir);
                    foreach ($files as $file) {
                        $ext = strtolower($file->getExtension());
                        if (in_array($ext, $imageExtensions)) {
                            $relativePath = str_replace(public_path() . '/', '', $file->getPathname());
                            // Normalize forward slashes for Windows compatibility
                            $relativePath = str_replace('\\', '/', $relativePath);

                            $images[] = [
                                'url' => asset($relativePath),
                                'relative_path' => $relativePath,
                                'filename' => $file->getFilename(),
                                'size' => $this->formatBytes($file->getSize()),
                                'mtime' => $file->getMTime(),
                                'date' => date('M d, Y h:i A', $file->getMTime()),
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    // Continue scanning other directories safely
                }
            }
        }

        // Sort latest uploaded first
        usort($images, function ($a, $b) {
            return $b['mtime'] <=> $a['mtime'];
        });

        // Filter search if query provided
        $query = $request->query('q');
        if (!empty($query)) {
            $q = strtolower($query);
            $images = array_values(array_filter($images, function ($img) use ($q) {
                return str_contains(strtolower($img['filename']), $q);
            }));
        }

        return response()->json([
            'success' => true,
            'count' => count($images),
            'images' => $images
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,webp,gif,svg,avif|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
        $filename = 'media_' . time() . '_' . Str::random(8) . '.' . $ext;

        $targetDir = public_path('backend/images/uploads');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $file->move($targetDir, $filename);
        $relativePath = 'backend/images/uploads/' . $filename;
        $fullPath = public_path($relativePath);

        $size = File::exists($fullPath) ? File::size($fullPath) : 0;

        $media = [
            'url' => asset($relativePath),
            'relative_path' => $relativePath,
            'filename' => $filename,
            'size' => $this->formatBytes($size),
            'mtime' => time(),
            'date' => date('M d, Y h:i A'),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully to Media Library',
            'image' => $media
        ]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
