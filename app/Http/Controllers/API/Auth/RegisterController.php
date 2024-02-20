<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request as Request;
use Request as RequestData;
use Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct() {
        $this->middleware('guest');
    }

    public function register(Request $request) {
        $name = $request->input('name');
        $email = $request->input('email');
        $pass = $request->input('password');
        $referral = $request->input('referral');
        $rules = $request->boolean('rules');

        if (!$rules) {
            return response()->json(['success' => false, 'message' => 'Для создания аккаунта на проекте, вы должны принять правила']);
        }

        if (!$name) {
            return response()->json(['success' => false, 'message' => 'Введите логин']);
        }

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Введите почтовый адрес']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Введите корректный почтовый адрес']);
        }

        if (!$pass) {
            return response()->json(['success' => false, 'message' => 'Введите пароль']);
        }

        if (strlen($name) < 3) {
            return response()->json(['success' => false, 'message' => 'Логин не может быть менее 3 символов']);
        }

        if (strlen($name) > 16) {
            return response()->json(['success' => false, 'message' => 'Логин не может быть более 16 символов']);
        }

        if (strlen($pass) < 8) {
            return response()->json(['success' => false, 'message' => 'Пароль не может быть менее 8 символов']);
        }

        if (preg_match( '/\p{Cyrillic}/u', $pass)) {
            return response()->json(['success' => false, 'message' => 'Пароль может содержать только латинские буквы и символы!']);
        }

        $user = User::where('name', $name)->first();
        if ($user) {
            return response()->json(['success' => false,'message' => 'Такой логин уже используется']);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            return response()->json(['success' => false,'message' => 'Такой почтовый адрес уже используется']);
        }

        if ($referral) {
            $referralUser = User::where('name', $referral)->first();
            if ($referralUser) $referral = $referralUser->id;
        }

        $array = [
            'name' => $name,
            'email' => $email,
            'password' => $pass,
            'referral' => $referral ? $referral : null,
        ];

        event(new Registered($user = $this->create($array)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return response()->json([
            'success' => true,
            'message' => 'Поздравляем с успешной регистрацией!',
        ]);
    }

    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'referral' => $data['referral'] ? $data['referral'] : null,
            'registration_ip' => RequestData::ip(),
            'last_auth_ip' => RequestData::ip(),
            'last_auth_date' => now(),
            'uuid' => Str::uuid(),
        ]);
    }
}
