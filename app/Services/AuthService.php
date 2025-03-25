<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\OtpCodeVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    use HelperTrait;

    public function __construct()
    {
        //
    }

    public function checkUser($request)
    {
        $user_type = $request->user_type ? $request->user_type : "Student";

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email_or_username)
                ->orWhere('username', $request->email_or_username);
            })
            ->where('user_type', $user_type)
            ->first();

        $request_type = $this->identifyInputType($request->email_or_username); 
        $otp = mt_rand(1000, 9999);

        $message = "Your verification code is: " . $otp;

        if (!$user) {
            if($request_type == 'email'){
                $user = User::create([
                    'email' => $request->email_or_username,
                    'user_type' => $user_type ?? "Student",
                    'is_active' => 1,
                ]);
                $mail_body = ['name' => $user->name ?? "Concern", 'subject' => "Verification Code", 'title' => "Verification Code", 'body' => $message];
                $this->sendEmail($mail_body, $request->email_or_username);
            }elseif($request_type == 'phone'){  
                $user = User::create([
                    'username' => $request->email_or_username,
                    'primary_number' => $request->email_or_username,
                    'user_type' => $user_type ?? "Student",
                    'is_active' => 1,
                ]);
                $this->sendSms($request->email_or_username, $message);
            }else{
                throw new \Exception('Enter Valid Phone/Email!');
            }
        }
        else{
            if($request_type == 'email'){
                if($user->email!= $request->email_or_username){
                    throw new \Exception('Invalid Email!');
                }else{
                    $mail_body = ['name' => $user->name ?? "Concern", 'subject' => "Verification Code", 'title' => "Verification Code", 'body' => $message];
                    $this->sendEmail($mail_body, $request->email_or_username);
                }
            }elseif($request_type == 'phone'){  
                if($user->username!= $request->email_or_username){
                    throw new \Exception('Invalid Phone!');
                }else{
                    $this->sendSms($request->email_or_username, $message);
                }
            }
        }

        OtpCodeVerification::create([
            'user_id' => $user->id,
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return true;
    }

    public function checkUserVarification($request)
    {
        $user_type = $request->user_type ? $request->user_type : "Student";

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email_or_username)
                ->orWhere('username', $request->email_or_username);
            })
            ->where('user_type', $user_type)
            ->first();

        if ($user) {
            if($user->is_password_set){
                return response()->json([
                    'data' =>[
                        'is_password_set' => true,
                    ],
                    'message' => 'Enter Password to login!',
                ], 400);
            }
        }

        $request_type = $this->identifyInputType($request->email_or_username); 
        $otp = mt_rand(1000, 9999);

        $message = "Your verification code is: " . $otp;

        if (!$user) {
            if($request_type == 'email'){
                $user = User::create([
                    'email' => $request->email_or_username,
                    'user_type' => $user_type ?? "Student",
                    'is_active' => 1,
                ]);
                $mail_body = ['name' => $user->name ?? "Concern", 'subject' => "Verification Code", 'title' => "Verification Code", 'body' => $message];
                $this->sendEmail($mail_body, $request->email_or_username);
            }elseif($request_type == 'phone'){  
                $user = User::create([
                    'username' => $request->email_or_username,
                    'primary_number' => $request->email_or_username,
                    'user_type' => $user_type ?? "Student",
                    'is_active' => 1,
                ]);
                $this->sendSms($request->email_or_username, $message);
            }else{
                throw new \Exception('Enter Valid Phone/Email!');
            }
        }
        else{
            if($request_type == 'email'){
                if($user->email!= $request->email_or_username){
                    throw new \Exception('Invalid Email!');
                }else{
                    $mail_body = ['name' => $user->name ?? "Concern", 'subject' => "Verification Code", 'title' => "Verification Code", 'body' => $message];
                    $this->sendEmail($mail_body, $request->email_or_username);
                }
            }elseif($request_type == 'phone'){  
                if($user->username!= $request->email_or_username){
                    throw new \Exception('Invalid Phone!');
                }else{
                    $this->sendSms($request->email_or_username, $message);
                }
            }
        }

        OtpCodeVerification::create([
            'user_id' => $user->id,
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return true;
    }

    public function verifyOtpForLogin($request){
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->email_or_username)
            ->orWhere('username', $request->email_or_username);
        })
        ->where('user_type', $request->user_type)
        ->first();
        
        if (!$user) {
            throw new \Exception('User not found.');
        }

        // Ensure user_type matches
        if ($user->user_type !== $request->user_type) {
            return response()->json(['message' => 'User type mismatch'], 400);
        }

        $otpRecord = OtpCodeVerification::where('otp_code', $request->verification_code)->where('user_id', $user->id)
            ->where('expired_at', '>', Carbon::now()->utc())
            ->latest()->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // Generate JWT token
        $token = Auth::guard('api')->login($user);
        // Delete OTP after successful login
        $otpRecord->delete();

        $user = User::where('id', $user->id)->first();
        $role = $user->roles()->first()->name ?? null;
        $permissions = $user->getAllPermissions()->pluck('name');
        $extraPermissions = $user->getDirectPermissions()->pluck('name');
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name');
        $expiresIn = auth()->factory()->getTTL() * 60;

        return [
            'token_type' => 'bearer',
            'token' => $token,
            'expires_in' => $expiresIn,
            'role' => $role,
            'permissions' => $permissions,
            'role_permissions' => $rolePermissions,
            'extra_permissions' => $extraPermissions,
            'user' => auth()->user(),
        ];

    }

    public function identifyInputType($email_or_phone)
    {
        if (filter_var($email_or_phone, FILTER_VALIDATE_EMAIL)) {
            return "email";
        } elseif (preg_match('/^[0-9]{10,15}$/', $email_or_phone)) {
            return "phone";
        } else {
            return "unknown";
        }
    }

    public function register($request)
    {
        try {
            $path = $this->fileUpload($request, 'image', 'users');
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'username' => $request->username,
                'number' => $request->number,
                'image' => $path,
                'organization_id' => $request->organization_id,
                'is_active' => 0,
            ]);

            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sendOtp($request)
    {
        $user = User::where('username', $request->email_or_username)->first();
        if (!$user) {
            throw new \Exception('User not found');
        }

        $otp = mt_rand(1000, 9999);
        OtpCodeVerification::create([
            'user_id' => $user->id,
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return true;

        //$this->sendSms($request->number, "Your OTP is: ". $otp);
    }

    public function login($request)
    {
        try 
        {
            $credentials = ['email' => $request->email_or_username, 'password' => $request->password, 'user_type' => $request->user_type];
            $token = Auth::guard('api')->attempt($credentials);

            // Attempt with username if email fails
            if (!$token) {
                $credentials = ['username' => $request->email_or_username, 'password' => $request->password];
                $token = Auth::guard('api')->attempt($credentials);
            }

            // Throw an exception if both attempts fail
            if (! $token) {          
                throw new \Exception('Invalid credentials');
            }

            $user = User::where('id', auth()->id())->first();
            $role = $user->roles()->first()->name ?? null;
            $permissions = $user->getAllPermissions()->pluck('name');
            $extraPermissions = $user->getDirectPermissions()->pluck('name');
            $rolePermissions = $user->getPermissionsViaRoles()->pluck('name');
            $expiresIn = auth()->factory()->getTTL() * 30 * 24 * 60;

            return [
                'token_type' => 'bearer',
                'token' => $token,
                'expires_in' => $expiresIn,
                'role' => $role,
                'permissions' => $permissions,
                'role_permissions' => $rolePermissions,
                'extra_permissions' => $extraPermissions,
                'user' => auth()->user(),
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function profile()
    {
        try {
            $userData = auth()->user();
            //  $userData = request()->user();

            return [
                'user' => $userData,
                'user_id' => auth()->user()->id,
                'email' => auth()->user()->email,
                // "user_id" => request()->user()->id,
                // "email" => request()->user()->email
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function refreshToken()
    {
        try {
            $token = auth()->refresh();
            $expiresIn = auth()->factory()->getTTL() * 60;

            return [
                'token_type' => 'bearer',
                'token' => $token,
                'expires_in' => $expiresIn,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function changePassword($request)
    {

        try {
            $user = auth()->user();
            if (!Hash::check($request->old_password, $user->password)) {
                throw new \Exception('Old password is incorrect');
            }
            $user->password = bcrypt($request->new_password);
            $user->save();
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function details()
    {
        try {
            $user = auth()->user();
            $role = $user->roles()->first()->name ?? null;
            $permissions = $user->getAllPermissions()->pluck('name');
            $extraPermissions = $user->getDirectPermissions()->pluck('name');
            $rolePermissions = $user->getPermissionsViaRoles()->pluck('name');

            // Retrieve only active menus with their associated active submenus, ordered by 'order'
            $menus = Menu::whereHas('roles', function ($query) use ($user) {
                $query->whereIn('roles.id', $user->roles->pluck('id'));
            })
                ->select('id', 'organization_id', 'name', 'description', 'url', 'icon', 'order', 'is_active')
                ->where('is_active', true) // Filter by active menus
                ->orderBy('order') // Order by 'order' column
                ->with(['subMenus' => function ($query) {
                    $query->select('id', 'menu_id', 'organization_id', 'name', 'description', 'icon', 'url', 'order', 'is_active')
                        ->where('is_active', true) // Filter by active submenus
                        ->orderBy('order'); // Order submenus by 'order' column
                }])
                ->get();

            return [
                'role' => $role,
                'permissions' => $permissions,
                'role_permissions' => $rolePermissions,
                'extra_permissions' => $extraPermissions,
                'menus' => $menus
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setPassword($user, $name = null, $newPassword)
    {
        try {
            $user->password = Hash::make($newPassword);
            $user->name = $name;
            $user->is_password_set = true;
            $user->save();

        // Generate JWT token
        $token = Auth::guard('api')->login($user);

        $user = User::where('id', $user->id)->first();
        $role = $user->roles()->first()->name ?? null;
        $permissions = $user->getAllPermissions()->pluck('name');
        $extraPermissions = $user->getDirectPermissions()->pluck('name');
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name');
        $expiresIn = auth()->factory()->getTTL() * 60;

        return [
            'token_type' => 'bearer',
            'token' => $token,
            'expires_in' => $expiresIn,
            'role' => $role,
            'permissions' => $permissions,
            'role_permissions' => $rolePermissions,
            'extra_permissions' => $extraPermissions,
            'user' => $user,
        ];

        }catch (\Throwable $th) {
            throw $th;
        }
    }

}
