<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function loginPage()
    {
        return view('pages.auth.login-page');
    }
    public function sendOTPPage()
    {
        return view('pages.auth.send-otp-page');
    }
    public function verifyOTPPage()
    {
        return view('pages.auth.verify-otp-page');
    }

    public function registrationPage()
    {
        return view('pages.auth.registration-page');
    }
    public function resetPasswordPage()
    {
        return view('pages.auth.reset-password-page');
    }
    public function profilePage()
    {
        return view('pages.dashboard.profile-page');
    }

    // Registration
    public function userRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:15|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'role' => 'customer', // Default role
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User registration failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function profile(Request $request)
    {
        $email = $request->user_email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User profile retrieved successfully',
            'user' => $user
        ], 200);
    }


    public function profileUpdate(Request $request)
    {
        $email = $request->user_email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:15|unique:users,mobile,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        try {
            // Prepare update data
            $updateData = [
                'name' => $request->name,
                'mobile' => $request->mobile,
            ];

            // Only update password if provided
            if ($request->has('password') && $request->password) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Update user details
            $user->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User update failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // Login
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = JWTToken::generateToken($user->email, $user->id, $user->role);

            return response()
                ->json([
                    'status' => 'success',
                    'message' => 'User login successful',
                    'user' => $user
                ], 200)
                ->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    public function SendOTPCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ], 200);
        } else {

            Mail::to($user->email)->send(new OTPMail($otp));
            User::where('email', $user->email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => '6 Digit OTP sent to your email',
            ], 200);
        }
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp !== $request->otp) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid OTP'
            ], 401);
        }

        // OTP is valid, proceed with login or other actions
        Auth::login($user);
        $token = JWTToken::generateTokenResetPassword($user->email);

        // Optionally, you can clear the OTP after successful verification
        User::where('email', $user->email)->update(['otp' => '0']);

        return response()
            ->json([
                'status' => 'success',
                'message' => 'OTP verified successfully',
                'user' => $user // Optional: include basic user info
            ], 200)
            ->cookie('token', $token, 60 * 24 * 30);
    }

    public function ResetPassword(Request $request)
    {
        try {
            $email = $request->header('email');

            $request->validate([
                'password' => 'required|string|min:8',
            ]);

            $user = User::where('email', $email)->first();


            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User not found'
                ], 404);
            }

            // Update the user's password
            $user->password = Hash::make($request->password);
            $user->save();


            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Password reset failed',
            ], 200);
        }
    }


    public function logout()
    {
        return redirect('/login')->cookie('token', '', -1);
    }
}
