<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');

        $query = Notice::latest();

        if ($tab === 'active') {
            $query->active();
        } elseif ($tab === 'expired') {
            $query->expired();
        }

        $notice = $query->get();

        $allCount = Notice::count();
        $activeCount = Notice::active()->count();
        $expiredCount = Notice::expired()->count();

        return view('backend.pages.notice.table', compact('notice', 'tab', 'allCount', 'activeCount', 'expiredCount'));
    }

    public function create()
    {
        return view('backend.pages.notice.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255|min:2',
            'description' => 'required|string',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
            'image_url'   => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip|max:25600',
            'expires_at'  => 'nullable|date',
            'show_in'     => 'nullable|in:m,p,b',
        ]);

        $notice = new Notice();
        $notice->title = ucwords($request->title);
        $notice->slug = Str::slug($request->title);
        $notice->description = $request->description;
        $notice->show_in = $request->input('show_in', 'm');

        // Handle Expiry Date
        if ($request->filled('expires_at')) {
            $notice->expires_at = Carbon::parse($request->expires_at)->endOfDay();
        } else {
            $notice->expires_at = null;
        }

        // Handle Featured Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'notice_' . time() . '_' . Str::random(10) . '.' . $extension;
            $destinationPath = public_path('backend/images/notices');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $notice->image = 'backend/images/notices/' . $imageName;
        } elseif ($request->filled('image_url')) {
            $notice->image = $request->image_url;
        } else {
            $notice->image = null;
        }

        // Handle File / PDF Attachment
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileSize = $this->formatBytes($file->getSize());
            $extension = $file->getClientOriginalExtension();
            $fileName = 'doc_' . time() . '_' . Str::random(10) . '.' . $extension;

            $fileDest = public_path('backend/files/notices');
            if (!file_exists($fileDest)) {
                mkdir($fileDest, 0755, true);
            }
            $file->move($fileDest, $fileName);

            $notice->file = 'backend/files/notices/' . $fileName;
            $notice->file_name = $originalName;
            $notice->file_size = $fileSize;
        }

        $save = $notice->save();

        if ($save) {
            $this->flushCaches();
            Alert::success('Saved', 'Notice published successfully');
            return redirect()->route('notice.table');
        } else {
            Alert::error('Oops', 'Notice could not be saved');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $notice = Notice::find($id);
        if (is_null($notice)) {
            Alert::error('Oops', 'Notice not found');
            return redirect()->route('notice.table');
        }
        return view('backend.pages.notice.edit', compact('notice'));
    }

    public function update(Request $request, Notice $noticeModel, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255|min:2',
            'description' => 'required|string',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
            'image_url'   => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip|max:25600',
            'expires_at'  => 'nullable|date',
            'show_in'     => 'nullable|in:m,p,b',
        ]);

        $notice = Notice::find($id);
        if (is_null($notice)) {
            Alert::error('Oops', 'Notice not found');
            return redirect()->route('notice.table');
        }

        $notice->title = ucwords($request->title);
        $notice->slug = Str::slug($request->title);
        $notice->description = $request->description;
        $notice->show_in = $request->input('show_in', $notice->show_in ?? 'm');

        // Handle Expiry Date
        if ($request->filled('expires_at')) {
            $notice->expires_at = Carbon::parse($request->expires_at)->endOfDay();
        } else {
            $notice->expires_at = null;
        }

        // Handle Image
        if ($request->hasFile('image')) {
            // Delete old image if existed and local
            if ($notice->image && file_exists(public_path($notice->image))) {
                @unlink(public_path($notice->image));
            }
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'notice_' . time() . '_' . Str::random(10) . '.' . $extension;
            $destinationPath = public_path('backend/images/notices');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $notice->image = 'backend/images/notices/' . $imageName;
        } elseif ($request->filled('image_url')) {
            $notice->image = $request->image_url;
        } elseif ($request->boolean('remove_image')) {
            if ($notice->image && file_exists(public_path($notice->image))) {
                @unlink(public_path($notice->image));
            }
            $notice->image = null;
        }

        // Handle File / PDF Attachment
        if ($request->hasFile('file')) {
            // Delete old file if existed
            if ($notice->file && file_exists(public_path($notice->file))) {
                @unlink(public_path($notice->file));
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileSize = $this->formatBytes($file->getSize());
            $extension = $file->getClientOriginalExtension();
            $fileName = 'doc_' . time() . '_' . Str::random(10) . '.' . $extension;

            $fileDest = public_path('backend/files/notices');
            if (!file_exists($fileDest)) {
                mkdir($fileDest, 0755, true);
            }
            $file->move($fileDest, $fileName);

            $notice->file = 'backend/files/notices/' . $fileName;
            $notice->file_name = $originalName;
            $notice->file_size = $fileSize;
        } elseif ($request->boolean('remove_file')) {
            if ($notice->file && file_exists(public_path($notice->file))) {
                @unlink(public_path($notice->file));
            }
            $notice->file = null;
            $notice->file_name = null;
            $notice->file_size = null;
        }

        $save = $notice->save();

        if ($save) {
            $this->flushCaches();
            Alert::success('Updated', 'Notice updated successfully');
            return redirect()->route('notice.table');
        } else {
            Alert::error('Oops', 'Notice could not be updated');
            return back()->withInput();
        }
    }

    public function status($id)
    {
        $notice = Notice::find($id);
        if (is_null($notice)) {
            Alert::error('Oops', 'Notice not found');
            return back();
        }

        if ($notice->show_in === 'p') {
            $notice->show_in = 'm';
            $notice->save();
            $this->flushCaches();
            Alert::success('Updated', 'Display switched to Marquee');
        } elseif ($notice->show_in === 'm') {
            $notice->show_in = 'b';
            $notice->save();
            $this->flushCaches();
            Alert::success('Updated', 'Display set to Notice Board only');
        } else {
            $notice->show_in = 'p';
            $notice->save();
            $this->flushCaches();
            Alert::success('Updated', 'Display switched to Popup Alert');
        }

        return back();
    }

    public function destroy($id)
    {
        $notice = Notice::find($id);
        if ($notice) {
            if ($notice->image && file_exists(public_path($notice->image))) {
                @unlink(public_path($notice->image));
            }
            if ($notice->file && file_exists(public_path($notice->file))) {
                @unlink(public_path($notice->file));
            }
            $notice->delete();
            $this->flushCaches();
            Alert::success('Deleted', 'Notice deleted successfully');
        } else {
            Alert::error('Oops', 'Notice not found');
        }

        return redirect()->route('notice.table');
    }

    private function flushCaches()
    {
        Cache::forget('home.popup_notice');
        Cache::forget('home.marquee_notice');
        Cache::forget('header_marquee_notice');
        Cache::forget('sticky_notices_list');
    }

    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
