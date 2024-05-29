<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AuthController extends Controller
{

    public function user() {
        try {
            $user = User::find(auth()->user()->id);
            if(empty($user)) throw new Error('User not found');
            return response()->json(['status' => true, 'message' => 'User found', 'user' => new UserResource($user)]);
        } catch (Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            if (auth()->attempt($request->only('email', 'password'))) {
                $user = auth()->user()->load('role');
                return new LoginResource(['token' => $user->createToken($user->email)->accessToken, 'user' => $user]);
            }
            throw new Error('Invalid Credentials', 412);
        } catch (Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate(['email' => 'required|email|exists:users,email']);
            $user = User::where('email', $request->email)->first();
            $otp = rand(100000, 999999);
            $token = DB::select("SELECT * FROM password_reset_tokens WHERE email = ?", [$user->email]);
            if (isset($token[0])) {
                DB::update('update password_reset_tokens set token = ? where email = ?', [$otp, $user->email]);
            } else {
                DB::insert("insert into password_reset_tokens (email, token) values (?, ?)", [$user->email, $otp]);
            }

            Mail::send('mail.reset-password', ['otp' => $otp, 'email' => $user->email], function ($message) use ($user) {
                $message->to($user->email, $user->name);
                $message->subject('Reset Password - OTP');
                $message->priority(3);
            });
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "OTP has been to " . $user->email,
            ]);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            User::where('email', $request->email)->update(['password' => bcrypt($request->password)]);
            DB::delete('DELETE FROM password_reset_tokens WHERE email = ?', [$request->email]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Password has been reset for " . $request->email,
            ]);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
