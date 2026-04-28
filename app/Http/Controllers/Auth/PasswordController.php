<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasswordController extends Controller
{

/* FORGOT PASSWORD */

public function forgotForm()
{
    return view('auth.forgot-password');
}

public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    $otp = rand(100000,999999);

    DB::table('password_otps')->updateOrInsert(
        ['email'=>$request->email],
        [
            'otp'=>$otp,
            'expires_at'=>Carbon::now()->addMinutes(10),
            'created_at'=>now(),
            'updated_at'=>now()
        ]
    );

    Mail::raw("Your OTP is: $otp", function($msg) use ($request){
        $msg->to($request->email)
            ->subject('Password Reset OTP');
    });

    session(['reset_email'=>$request->email]);

    return redirect()->route('verify.form')
        ->with('success','OTP sent to email');
}

public function verifyForm()
{
    return view('auth.verify-otp');
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp'=>'required'
    ]);

    $record = DB::table('password_otps')
        ->where('email',session('reset_email'))
        ->where('otp',$request->otp)
        ->first();

    if(!$record || Carbon::now()->gt($record->expires_at)){
        return back()->withErrors(['otp'=>'Invalid or expired OTP']);
    }

    return redirect()->route('reset.form');
}

public function resetForm()
{
    return view('auth.reset-password');
}

public function resetPassword(Request $request)
{
    $request->validate([
        'password'=>'required|min:6|confirmed'
    ]);

    $user = User::where('email',session('reset_email'))->first();

    $user->update([
        'password'=>Hash::make($request->password)
    ]);

    DB::table('password_otps')->where('email',$user->email)->delete();

    return redirect('/login')->with('success','Password reset successful');
}


/* CHANGE PASSWORD */

public function changeForm()
{
    return view('auth.change-password');
}

public function changePassword(Request $request)
{
    $request->validate([
        'old_password'=>'required',
        'password'=>'required|min:6|confirmed'
    ]);

    $user = auth()->user();

    if(!Hash::check($request->old_password,$user->password)){
        return back()->withErrors(['old_password'=>'Old password incorrect']);
    }

    $user->update([
        'password'=>Hash::make($request->password)
    ]);

    return back()->with('success','Password updated');
}

}