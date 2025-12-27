<?php

namespace App\Http\Controllers\Authentication;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\VerifyUser;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'      => 'required|string|min:3|max:100',
            'email'     => 'required|email',
            'password'  => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        if (User::where('email', $request->email)->exists()) {
            return redirect()->route('register')->with('error', 'User already exists with this email address !');
        }

        DB::beginTransaction();
        try {
            $validated = $validated->validated();
            $validated['role'] = 'user';
            $token = Str::random(64);
            $user  = User::create($validated);

            $verifyLink = url('/verify-email/' . $token . '?email=' . $request->email);
            $data         = [];
            $email_sent   = '';
            $data['link'] = $verifyLink;
            $data['name'] = $user->name;
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $email_sent = Mail::to($request->email)->send(new VerifyUser($data));
            }
            if ($email_sent) {
                $user->update([
                    'email_verification_token'    => $token,
                    'email_verification_sent_at'  => Carbon::now()
                ]);
            }
            Session::put('email', $request->email);
            DB::commit();
            return redirect()->route('verify-user')->with('success', "Registration successful,Please Verify Your Email.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.'.$e);
        }
    }

    public function verifyUserForm()
    {
        return view('auth.verify-user');
    }

    public function resendEmailLink(Request $request)
    {
        $email = Session::get('email');
        $user  = User::where('email', $email)->first();
        if ($user->is_verified == 1) {
            return back()->with('success', 'Your email is already verified!');
        }
        $lastRequest = User::where('email', $email)->orderBy('email_verification_sent_at', 'desc')->first();
        if ($lastRequest && Carbon::parse($lastRequest->email_verification_sent_at)->addMinutes(1)->isFuture()) {
            return back()->with('error', 'Please wait for 1 minutes to requesting another verification link.');
        }
        $token = Str::random(64);
        $verifyLink = url('/verify-email/' . $token . '?email=' . $request->email);
        $data         = [];
        $email_sent   = '';
        $data['link'] = $verifyLink;
        $data['name'] = $user->name;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $email_sent = Mail::to($request->email)->send(new VerifyUser($data));
        }
        if ($email_sent) {
            $user->update([
                'email_verification_token'    => $token,
                'email_verification_sent_at'  => Carbon::now()
            ]);
        }
        Session::put('email', $request->email);
        return back()->with('success', 'Link has been resent successfully.');
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
        }

        if (Carbon::parse($user->email_verification_sent_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'link has been expired.']);
        }

        User::where('id', $user->id)->update([
            'is_verified'              => 1,
            'email_verification_token' => null,
            'email_verified_at'        => now()
        ]);
        Session::forget('email');
        return redirect()->route('login')->with('success', 'Email verified successfully. You can now login.');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);
        if ($validated->fails()) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        try {
            $validated = $validated->validated();
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => "User does not exist!"]);
            }
            if ($user->is_block == 1) {
                return back()->withErrors(['email' => "Contact to admin!"]);
            }
            if ($user->is_verified == 0) {
                return back()->withErrors(['email' => "Please verify yout account to login!"]);
            }
            if (!Hash::check($validated['password'], $user->password)) {
                return back()->withErrors(['password' => "Invalid password!"]);
            }
            if (!Auth::attempt($validated)) {
                return back()->withErrors(['email' => "Authentication failed!"]);
            }
            return redirect()->route('index')->with('success', "Login successful.");
        } catch (\Exception $e) {
            return back()->withErrors(['email' => "Something went wrong. Please try again later."]);
        }
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $token = Str::random(64);
        PasswordResetToken::where('email', $request->email)->delete();
        PasswordResetToken::create([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now()
        ]);
        $resetLink    = url('/reset-password/' . $token . '?email=' . $request->email);
        $data         = [];
        $data['link'] = $resetLink;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)->send(new ForgotPassword($data));
        }
        Session::put('email', $request->email);
        return back()->with('success', 'Password reset link sent to your registered email.');
    }

    public function resendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email       = Session::get('email');
        $lastRequest = PasswordResetToken::where('email', $email)->orderBy('created_at', 'desc')->first();
        if ($lastRequest && Carbon::parse($lastRequest->created_at)->addMinutes(1)->isFuture()) {
            return back()->with('error', 'Please wait for 1 minutes to requesting another reset link.');
        }
        PasswordResetToken::where('email', $request->email)->delete();
        $token = Str::random(64);
        PasswordResetToken::create([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now()
        ]);
        $resetLink = url('/reset-password/' . $token . '?email=' . $request->email);
        $data         = [];
        $data['link'] = $resetLink;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)->send(new ForgotPassword($data));
        }
        Session::put('email', $email);
        return back()->with('success', 'Password reset link has been resent successfully.');
    }

    public function resetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'       => 'required|email|exists:users,email',
            'password'    => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        $reset = PasswordResetToken::where('email', $request->email)->first();
        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid or expired reset request.']);
        }
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            PasswordResetToken::where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Reset link has been expired.']);
        }
        User::where('email', $request->email)->update([
            'password'   => Hash::make($request->password),
            'updated_at' => now()
        ]);
        PasswordResetToken::where('email', $request->email)->delete();
        Session::forget('email');
        return redirect()->route('login')->with('success', 'Password updated successfully. Please login.');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
