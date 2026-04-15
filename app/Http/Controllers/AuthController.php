<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {

        return view('login');
    }
    public function checkUser(Request $request)
    {
        $mobile = $request->mobile;

        $user = User::where('mobile_number', $mobile)->first();

        if ($user->is_verified == 1) {
            session(['mobile_number' => $mobile]);

            return response()->json([
                'status' => 'verified',
                'message' => ''
            ]);
        } else {
            // Generate OTP
            $otp = rand(1000, 9999);

            // Store OTP (session / DB)
            session(['otp' => $otp, 'mobile_number' => $mobile, 'success' => "OTP sent to your mobile number: {$otp} (Demo: In production, this would be sent via SMS)"]);

            // TODO: Send OTP via SMS API

            return response()->json([
                'status' => 'not_verified',
                'message' => "OTP sent to your mobile number: " . $otp . " (Demo: In production, this would be sent via SMS)"
            ]);
        }
    }
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'otp_code' => 'required|digits:4'
        ]);

        $mobile = $request->mobile_number;
        $otp = $request->otp_code;

        // Check OTP from session (or DB)
        if (session('otp') != $otp || session('mobile_number') != $mobile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired OTP'
            ]);
        }

        // Get user
        $user = User::where('mobile_number', $mobile)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        // Mark verified
        $user->is_verified = 1;

        // Check password
        if (!$user->password) {

            // Generate random password
            $password = $this->generatePassword(8);

            // Save hashed password
            $user->password = Hash::make($password);
            $user->save();
            session()->forget(['otp', 'mobile_number', 'success']);
            // TODO: Send SMS here
            session(['success' => "OTP verified! Password sent to your mobile (Demo: $password)"]);


            return response()->json([
                'status' => 'success',
                'success' => "OTP verified! Password sent to your mobile (Demo: $password)"
            ]);
        } else {
            $user->save();
            session()->forget(['otp', 'mobile_number', 'success']);
            session(['success' => "OTP verified! Please login with your password"]);
            return response()->json([
                'status' => 'success',
                'success' => 'OTP verified! Please login with your password'
            ]);
        }
    }
    private function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'password' => 'required'
        ]);

        $mobile = $request->mobile_number;
        $password = $request->password;

        $user = User::where('mobile_number', $mobile)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        if (!$user->is_verified) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not verified. Please verify OTP first.'
            ]);
        }

        // Check password
        if (Hash::check($password, $user->password)) {

            // Login user (session)
            session(['user' => $user]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid password'
            ]);
        }
    }
    public function logout(Request $request)
    {
        // Logout user (if using Auth)
        auth()->logout();

        // Destroy session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Optional: clear custom session
        session()->forget(['user', 'otp', 'mobile_number']);

        return redirect('/')->with('success', 'Logged out successfully');
    }
}
