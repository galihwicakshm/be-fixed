<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Jobs\VerifyEmailJob;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'first_name' => [
                    'required',
                    'string',
                    "regex:/^['\p{L}\s-]+$/u",
                ],
                'college' => [
                    'required',
                    'string',
                    "regex:/^['\p{L}\s-]+$/u",
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    Rule::unique(User::class),
                ],
                'phone_number' => [
                    'required',
                    'string',
                    'numeric',
                    Rule::unique(UserProfile::class),
                ],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(),
                ],
                'password_confirmation' => [
                    'required'
                ]
            ]
        );

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors()
            ];

            return ResponseFormatter::validation_error('validation_errors', $data);
        }

        try {
            $user = User::create([
                'role' => 5,
                'email' => $request->email,
                //'plain_password' => $request->password,
                'password' => Hash::make($request->password),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'college' => $request->college,
            ]);

            $token = $user->createToken($user->email . '_token', ['server:user_external'])->plainTextToken;

            dispatch(new VerifyEmailJob($user));

            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return ResponseFormatter::success('Registered Successfully', $data);
        } catch (QueryException $error) {
            $data = [
                'error' => $error
            ];

            return ResponseFormatter::error(500, 'Query Error', $data);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseFormatter::validation_error('Authentication Failed');
        }

        if ($user->role === 1) {
            $token = $user->createToken($user->email . '_token', ['server:admin_content'])->plainTextToken;
        } else if ($user->role === 2) {
            $token = $user->createToken($user->email . '_token', ['server:admin_proposal_submission'])->plainTextToken;
        } else if ($user->role === 3) {
            $token = $user->createToken($user->email . '_token', ['server:admin_super'])->plainTextToken;
        } else if ($user->role === 4) {
            $token = $user->createToken($user->email . '_token', ['server:user_internal'])->plainTextToken;
        } else if ($user->role === 5) {
            $token = $user->createToken($user->email . '_token', ['server:user_external'])->plainTextToken;
        }

        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ];

        return ResponseFormatter::success('Login Success', $data);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return ResponseFormatter::success('Token Revoked');
    }

    public function profile(Request $request)
    {
        if ($request->user()->tokenCan('server:admin_content') || $request->user()->tokenCan('server:admin_proposal_submission') || $request->user()->tokenCan('server:admin_super')) {
            $profile = User::where('id', auth()->user()->id)
                ->with('admin_profile')
                ->first();

            $data = [
                'profile' => $profile,
            ];

            return ResponseFormatter::success('Profile', $data);
        } else {
            $profile = User::where('id', auth()->user()->id)
                ->with('user_profile')
                ->first();
            $data = [
                'profile' => $profile
            ];

            return ResponseFormatter::success('Profile', $data);
        }
    }

    public function verify($id)
    {
        try {
            $user = User::findOrFail($id);

            if (!$user->hasVerifiedEmail()) {
                $userProfile = UserProfile::where('user_id', $user->id)
                    ->first();

                $checkLastName = $userProfile->last_name === null ? "" : " " . $userProfile->last_name;

                /*$curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => env('SECOND_URL').'/ldap',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'username' => $user->email,
                        'password' => $user->plain_password,
                        'mail' => $user->email,
                        'telephoneNumber' => $userProfile->phone_number,
                        'givenName' => $userProfile->first_name . $checkLastName
                    ),
                ));

                $response = curl_exec($curl);
                $resInfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);


                if ($resInfo != 200 || $response == false) {
                    return request()->wantsJson()
                        ? new JsonResponse('', 204)
                        : redirect(url(env('SANCTUM_STATEFUL_DOMAINS') . '/verifikasi?verified=false'));
    	}*/

                $user->markEmailAsVerified();
                event(new Verified($user));

                /*User::where('id', $user->id)
                    ->update([
                        'plain_password' =>  null
    	    ]);*/

                return request()->wantsJson()
                    ? new JsonResponse('', 204)
                    // : redirect(url(env('SANCTUM_STATEFUL_DOMAINS') . '/verifikasi?verified=true'));
                    : redirect(url('http://localhost:3000/verifikasi?verified=true'));
            }

            return request()->wantsJson()
                ? new JsonResponse('', 204)
                // : redirect(url(env('SANCTUM_STATEFUL_DOMAINS') . '/verifikasi?verified=true'));
                : redirect(url('http://localhost:3000/verifikasi?verified=true'));
        } catch (ModelNotFoundException $e) {
            return request()->wantsJson()
                ? new JsonResponse('', 204)
                // : redirect(url(env('SANCTUM_STATEFUL_DOMAINS') . '/verifikasi?verified=false'));
                : redirect(url('http://localhost:3000//verifikasi?verified=false'));
        }
    }
    // public function verify($user_id, Request $request)
    // {
    //     if (!$request->hasValidSignature()) {
    //         return response()->json(["msg" => "Invalid/Expired url provided."], 401);
    //     }

    //     $user = User::findOrFail($user_id);

    //     if (!$user->hasVerifiedEmail()) {
    //         $user->markEmailAsVerified();
    //     }

    //     return redirect()->to('/');
    // }

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
        );
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }



    public function resend()
    {
        dispatch(new VerifyEmailJob(request()->user()));

        return response([
            'data' => [
                'message' => 'Request has been sent!',
            ]
        ]);
    }
}
