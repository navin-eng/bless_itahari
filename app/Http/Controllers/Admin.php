<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Token;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Admin extends Controller
{
    public function login()
    {
        // SECURITY FIX: Never truncate tokens on page load — that would let anyone
        // visiting the login page destroy all pending password-reset OTPs.
        if (Auth::check()) {
            return redirect('/admin/dashboard');
        }
        return view('backend.auth.login');
    }

    public function register()
    {
        // SECURITY FIX: Removed Token::truncate() — it should never run on a page load.
        // Only a super admin (a_type=A) who is already logged in may access this page.
        if (DB::table('users')->count() > 0 && (!Auth::check() || Auth::user()->a_type !== 'A')) {
            return redirect()->route('admin.login')->with('error', 'New users can only be created by the super admin.');
        }
        return view('backend.auth.register');
    }

    // Registering the user
    public function registerAdmin(Request $request)
    {
        // SECURITY FIX: Removed Token::truncate().
        // SECURITY FIX: If users already exist, require super admin auth (double-checked here as well).
        $count = DB::table('users')->count();

        if ($count > 0 && (!Auth::check() || Auth::user()->a_type !== 'A')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized.');
        }

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

        $admin           = new User();
        $admin->name     = $request->name;
        $admin->email    = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->a_type   = ($count === 0) ? 'A' : 'E';
        $admin->image    = $imageName ? 'backend/admin/images/' . $imageName : 'backend/admin/images/default-avatar.png';
        $admin->save();

        if ($count === 0) {
            session()->flash('success', 'Registered successfully. Please log in.');
            return redirect('/admin/dashboard/login');
        }

        session()->flash('success', 'New editor registered successfully.');
        return back();
    }

    public function adminCheck(Request $request)
    {
        // SECURITY FIX: Removed Token::truncate().
        // SECURITY FIX: Validate inputs before passing to Auth::attempt().
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate(); // Prevent session fixation attacks
            return redirect('/admin/dashboard')->with('success', 'Login successful.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function forgotPassword()
    {
        // SECURITY FIX: Removed Token::truncate() — page loads must never destroy data.
        return view('backend.auth.resetemail');
    }

    public function emailCheck(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $checkEmail = User::where('email', $request->email)->first();
        if (!$checkEmail) {
            // SECURITY FIX: Use a generic message to avoid user enumeration.
            return back()->with('success', 'If that email exists in our system, you will receive an OTP shortly.');
        }

        // SECURITY FIX: Delete only THIS user's old tokens before inserting a new one.
        // Do NOT truncate the whole table — that would destroy other admins' OTPs.
        DB::table('tokens')->where('email', $request->email)->delete();

        $otp = rand(100000, 999999); // 6-digit OTP is stronger than 5-digit
        DB::table('tokens')->insert([
            'email'      => $request->email,
            'code'       => $otp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mail_data = [
            'sender'   => 'donotreplygplc@gmail.com',
            'reciever' => $request->email,
            'from'     => 'Shiksha Sandesh',
            'subject'  => 'Forgot Password - OTP',
            'body'     => $otp,
        ];
        Mail::send('backend.mail.otp', $mail_data, function ($message) use ($mail_data) {
            $message->to($mail_data['reciever'])
                ->from($mail_data['sender'], $mail_data['from'])
                ->subject($mail_data['subject']);
        });

        return redirect('/admin/dashboard/reset/password')->with('success', 'If that email exists, you will receive an OTP shortly.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'code'     => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $codeCheck = Token::where('code', $request->code)->first();

        if (!$codeCheck) {
            return back()->with('error', 'Verification code does not match or has expired.');
        }

        // SECURITY FIX: Check that the OTP is not older than 30 minutes
        if ($codeCheck->created_at && now()->diffInMinutes($codeCheck->created_at) > 30) {
            $codeCheck->delete();
            return back()->with('error', 'This OTP has expired. Please request a new one.');
        }

        $user = User::where('email', $codeCheck->email)->first();
        if (!$user) {
            return back()->with('error', 'An error occurred. Please try again.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // SECURITY FIX: Delete only the used token, not the entire table.
        $codeCheck->delete();

        return redirect('/admin/dashboard/login')->with('success', 'Password reset successfully. Please log in.');
    }

    public function profileUpdate(Request $request)
    {
        $admin = User::find($request->id);

        if (!$admin) {
            return back()->with('error', 'User not found.');
        }

        $admin->name  = $request->name;
        $admin->email = $request->email;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        // SECURITY FIX: A_type cannot be changed via profile update to prevent privilege escalation
        // a_type is not touched here — it stays whatever it was before.

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(20) . rand(0, 9999) . time() . '.' . $extension;
            $image->move('backend/admin/images/', $imageName);
            $admin->image = 'backend/admin/images/' . $imageName;
        }

        $admin->save();
        return back()->with('success', 'Profile updated.');
    }

    public function adminTable()
    {
        $editor = User::where('a_type', 'E')->get();
        return view('backend.pages.admin', compact('editor'));
    }

    public function adminDelete($id)
    {
        $admin = User::find($id);
        if (!$admin) {
            return back()->with('error', 'User not found.');
        }
        // SECURITY FIX: Prevent deleting the only super admin
        if ($admin->a_type === 'A' && User::where('a_type', 'A')->count() <= 1) {
            return back()->with('error', 'Cannot delete the only super admin.');
        }
        $admin->delete();
        return back()->with('success', 'Editor deleted.');
    }

    public function profile()
    {
        return view('backend.pages.profile');
    }
}
