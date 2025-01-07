<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpStudentRequest;
use App\Http\Requests\SignUpSuperAdminRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use \App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use \App\Models\SuperAdmin;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\StudentResource;
use Google\Client as GoogleClient;

class AuthController extends Controller
{
    public function googleCallback(Request $request)
    {
        $token = $request->input('credential'); // Este es el token que recibes de tu frontend (React)

        // Verificar el token con la API de Google
        $client = new GoogleClient(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($token);

        if ($payload) {
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];

            $student = Student::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'dni' => null]
            );

            auth()->login($student);

            return response()->json(['message' => 'Authenticated successfully']);
        } else {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }

    public function signup(SignUpStudentRequest $request)
    {
        try {


            $data = $request->validated();
            $profilePhotoPath = null;

            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->storeOnCloudinary()->getPublicId();
            }

            $user = Student::create([
                'name' => $data['name'],
                'dni' => $data['dni'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'career' => $data['career'],
                'profile_photo' => $profilePhotoPath,
            ]);

            $token = $user->createToken('main')->plainTextToken;

            $userResource = new StudentResource($user);

            return response()->json([
                'user' => $userResource,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            error_log($e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function signupSuperAdmin(SignUpSuperAdminRequest $request)
    {
        try {
            $data = $request->validated();
            $user = SuperAdmin::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'career' => $data['career'],
            ]);

            $token = $user->createToken('main')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            error_log($e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            $user = Student::where('email', $request->email)->first() ?? SuperAdmin::where('email', $request->email)->first();

            if (optional($user)->email !== $request->email) {
                return response()->json(['messageEmail' => 'Email incorrecto para la contraseña ingresada'], 422);
            }

            if (!password_verify($request->password, $user->password)) {
                return response()->json(['messagePassword' => 'Contraseña incorrecta para el email ingresado'], 422);
            }

            if ($user instanceof Student && !$user->approved) {
                return response()->json(['messageVerification' => 'Su cuenta aún no ha sido verificada. Por favor, revise su casilla de correo electrónico o comuníquese con la institución.'], 422);
            }

            $profilePhotoPath = null;

            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->storeOnCloudinary()->getPublicId();
            }

            Auth::login($user);
            $request->session()->regenerate();
            $token = $user->createToken('main')->plainTextToken;
            $userResource = new StudentResource($user);

            return response([
                'user' => $userResource,
                'token' => $token,
                'notifications' => $user instanceof SuperAdmin ? $user->notifications : [],
            ]);

        } catch (\Exception $e) {
            \Log::error($e);
            error_log($e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = Student::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No se encontró un usuario con este correo electrónico'], 404);
        }
        if ($user instanceof Student && !$user->approved) {
            return response()->json(['messageVerification' => 'Su cuenta aún no ha sido verificada. Por favor, revise su casilla de correo electrónico o comuníquese con la institución.'], 422);
        }

        $user->update(['reset_password_used' => false]);

        $token = app('auth.password.broker')->createToken($user);
        $user->update(['reset_password_token' => $token]);

        $frontendResetLink = env('FRONTEND_URL') . '/reset-password/' . $token;
        $name = $user->name;

        Mail::to($user->email)->send(new ResetPasswordMail($frontendResetLink, $name));

        return response()->json(['message' => 'Enlace de restablecimiento de contraseña enviado al correo electrónico.']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        try {
            $user = Student::where('reset_password_token', $request->token)
                ->where('reset_password_used', false)
                ->first();

            if (!$user) {
                return response()->json(['messageReset' => 'Email de restablecimiento ya utilizado. Por favor, vuelva a enviar la solicitud'], 404);
            }

            $user->update([
                'password' => bcrypt($request->password),
                'reset_password_token' => null,
                'reset_password_used' => true,
            ]);

            if (!$user->wasChanged()) {
                return response()->json(['error' => 'No se pudo actualizar la contraseña'], 500);
            }

            return response()->json(['message' => 'Contraseña restablecida con éxito']);
        } catch (\Exception $e) {
            \Log::error($e);
            error_log($e->getMessage());
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }


    public function verifyResetToken($token)
    {
        try {
            $user = Student::where('reset_password_token', $token)
                ->where('reset_password_used', false)
                ->first();

            if (!$user) {
                return response()->json(['messageReset' => 'Token de restablecimiento de contraseña no válido'], 404);
            }

            $tokenRepository = Password::getRepository();
            if (!$tokenRepository->exists($user, $token)) {
                return response()->json(['messageReset' => 'Token de restablecimiento de contraseña no válido'], 404);
            }

            return response()->json(['messageReset' => 'Token de restablecimiento de contraseña válido'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            error_log($e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function logout(Request $request)
    {
        $student = $request->user();
        $student->currentAccessToken()->delete();
        return response('', 204);
    }
}
