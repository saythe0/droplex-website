<?php

namespace App\Http\Controllers\Launcher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function authMethod() {
        $login = request()->input('login');
        $password = request()->input('password');
        // $totpCode = request()->input('totpCode');

        if (!$login) return 'Введите логин';
        if (!$password) return 'Введите пароль';
        if (strlen($login) < 3) return 'Логин не может быть менее 3 символов';
        if (strlen($login) > 16) return 'Логин не может быть более 16 символов';
        if (strlen($password) < 8) return 'Пароль не может быть менее 8 символов';
        // if (strlen($totpCode) < 6 && $totpCode) return 'Код не может быть менее 6 символов';
        // if (strlen($totpCode) > 6 && $totpCode) return 'Код не может быть более 6 символов';

        $user = User::where('name', $login)->first();
        if (!$user) {
            return 'Пользователь не найден';
        }

        if (!Hash::check($password, $user->password)) {
            return 'Неверный пароль';
        }

        // if ($user->two_factor && $totpCode) {
        //     $google2fa = new Google2FA();

        //     if (!$google2fa->verifyKey($user->two_factor_secret, $totpCode)) {
        //         return 'Неверный 2FA код!';
        //     }
        // }

        // if ($user->two_factor && !$totpCode) {
        //     return 'Введите 2FA код';
        // }

        return 'OK:' . $login;
    }
}
