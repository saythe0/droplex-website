<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно вышли из аккаунта',
        ]);
    }

    protected function validateLoginTotp(Request $request) {
        $this->validateLogin($request);

        $code = $request->input('totp');
        if (!$code) return response()->json(['success' => false, 'message' => 'Введите шестизначный код из приложения']);
        if (strlen($code) < 6) return response()->json(['success' => false, 'message' => 'Код не может быть менее 6 символов']);
        if (strlen($code) > 6) return response()->json(['success' => false, 'message' => 'Код не может быть более 6 символов']);
    }

    protected function validateLogin(Request $request) {
        $name = $request->input('name');
        $pass = $request->input('password');

        if (!$name) return response()->json(['success' => false, 'message' => 'Введите логин']);
        if (!$pass) return response()->json(['success' => false, 'message' => 'Введите пароль']);
        if (strlen($name) < 3) return response()->json(['success' => false, 'message' => 'Логин не может быть менее 3 символов']);
        if (strlen($name) > 16) return response()->json(['success' => false, 'message' => 'Логин не может быть более 16 символов']);
        if (strlen($pass) < 8) return response()->json(['success' => false, 'message' => 'Пароль не может быть менее 8 символов']);
    }

    public function loginTotp(Request $request) {
        $this->validateLoginTotp($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('name', $request->input($this->username()))->first();

        if ($user) {
            if ($user->two_factor) {
                $google2fa = new Google2FA();
                $code = $request->input('totp');

                if (!$google2fa->verifyKey($user->two_factor_secret, $code)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Неверный код! Попробуйте еще раз',
                    ]);
                }

                if ($this->attemptLogin($request)) {
                    if ($request->hasSession()) {
                        $request->session()->put('auth.password_confirmed_at', time());
                    }

                    return $this->sendLoginResponse($request);
                }
            } elseif ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                return $this->sendLoginResponse($request);
            }
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function sendLockoutResponse(Request $request) {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json([
            'success' => false,
            'message' => 'Слишком много попыток входа. Пожалуйста, попробуйте еще раз через '. $seconds.' сек.',
        ]);
    }

    public function login(Request $request) {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('name', $request->input($this->username()))->first();

        if ($user) {
            if ($user->two_factor) {
                return response()->json([
                    'success' => false,
                    'totp' => true,
                ]);
            } elseif ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                return $this->sendLoginResponse($request);
            }
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request) {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        $user = User::where('name', $request->input($this->username()))->first();
        $user->update([
            'last_auth_ip' => $request->ip(),
            'last_auth_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно авторизовались под аккаунтом ' . $request->input($this->username()),
        ]);
    }

    protected function sendFailedLoginResponse(Request $request) {
        return response()->json([
            'success' => false,
            'message' => 'Неверный логин или пароль!',
        ]);
    }

    public function username() {
        return 'name';
    }
}
