<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use hisorange\BrowserDetect\Parser as Browser;
use App\Models\Setting;
use App\Models\User;
use App\Models\Server;
use App\Models\GiftCode;
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Cache;


class UserController extends Controller
{

    public function __construct() {
        $this->middleware("auth");
    }

    public function codeActivate() {
        $userCode = request()->input('code');

        if (!$userCode) {
            return response()->json([
                'success' => false,
                'message' => 'Введите промокод'
            ]);
        }

        $user = Auth::user();
        $giftCode = GiftCode::where('code', $userCode)->first();

        if (!$giftCode) {
            return response()->json([
                'success' => false,
                'message' => 'Данный промокод не существует'
            ]);
        }

        if (Cache::has('use_code_' . $giftCode->id . '_' . $user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Вы уже использовали данный промокод'
            ]);
        }

        if ($giftCode->count <= $giftCode->uses) {
            return response()->json([
                'success' => false,
                'message' => 'Количество использований данного промокода закончилось'
            ]);
        }

        $data = [];

        switch ($giftCode->data['type']) {
            case 'ruble':
                $message = 'рубли';
                $user->update([
                    'money' => $user->money + $giftCode->data['count'],
                ]);
                break;

            case 'coin':
                $message = 'койны';
                $user->update([
                    'coins' => $user->coins + $giftCode->data['count'],
                ]);
                break;

            case 'donate':
                $message = 'донат';
                break;

            case 'block':
                $message = 'блок';
                break;

            case 'item':
                $message = 'предмет';
                break;

            case 'hd':
                $message = 'HD комплект';
                switch ($giftCode->data['mode']) {
                    case 'all':
                        $user->update([
                            'hd_skin' => 1,
                            'hd_cloak' => 1,
                        ]);
                        break;

                    case 'skin':
                        $user->update([
                            'hd_skin' => 1,
                        ]);
                        break;

                    case 'cloak':
                        $user->update([
                            'hd_cloak' => 1,
                        ]);
                        break;
                }
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Не известный тип промокода'
                ]);
        }

        $giftCode->update([
            'uses' => $giftCode->uses + 1,
        ]);

        return response()->json([
            'success' => false,
            'message' => $message
        ]);

        Cache::forever('use_code_' . $giftCode->id . '_' . $user->id, 1);
    }

    public function buySkin(Request $request) {
        $user = Auth::user();
        $price = config('settings.hd_skin_price');

        if ($user->hd_skin == 1) {
            return response()->json([
                'success' => false,
                'message' => 'У вас уже приобретён HD скин',
            ]);
        }

        if ($user->money < $price) {
            return response()->json([
                'success' => false,
                'message' => 'У вас недостаточно денег для покупки HD скина',
            ]);
        }

        $user->update([
            "money" => $user->money - $price,
            "hd_skin" => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно купили HD скин',
        ]);
    }

    public function buyCloak(Request $request) {
        $user = Auth::user();
        $price = config('settings.hd_cloak_price');

        if ($user->hd_cloak == 1) {
            return response()->json([
                'success' => false,
                'message' => 'У вас уже приобретён HD плащ',
            ]);
        }

        if ($user->money < $price) {
            return response()->json([
                'success' => false,
                'message' => 'У вас недостаточно денег для покупки HD плаща',
            ]);
        }

        $user->update([
            "money" => $user->money - $price,
            "hd_cloak" => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно купили право на установку HD плаща',
        ]);
    }

    public function buyHD(Request $request) {
        $user = Auth::user();
        $price = config('settings.hd_price');

        if ($user->hd_skin == 1) {
            return response()->json([
                'success' => false,
                'message' => 'У вас уже приобретён HD скин',
            ]);
        }

        if ($user->hd_cloak == 1) {
            return response()->json([
                'success' => false,
                'message' => 'У вас уже приобретён HD плащ',
            ]);
        }

        if ($user->money < $price) {
            return response()->json([
                'success' => false,
                'message' => 'У вас недостаточно денег для покупки HD скина и плаща',
            ]);
        }

        $user->update([
            "money" => $user->money - $price,
            "hd_skin" => true,
            "hd_cloak" => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно купили право на установку HD скина и плаща',
        ]);
    }

    public function totpLoad() {
        $user = Auth::user();
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        $qrCode = $google2fa->getQRCodeUrl(config('settings.title'), $user->name, $secretKey);
        $qrCode = QrCode::create($qrCode)
            ->setSize(199)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $writer = new PngWriter();
        $dataUri = $writer->write($qrCode)->getDataUri();

        return response()->json([
            'success' => true,
            'code' => rtrim(chunk_split($secretKey, 4, ' ')),
            'qrCode' => $dataUri,
        ]);
    }

    public function totpEnable(Request $request) {
        $secretKey = trim(str_replace(' ', '', $request->input('totp')));
        $code = trim(str_replace(' ', '', $request->input('code')));
        $google2fa = new Google2FA();
        $user = Auth::user();

        if ($user->two_factor){
            return [
                'success' => false,
                'message' => 'У вас уже включена двухэтапная авторизация!'
            ];
        }

        if (!$secretKey) {
            return response()->json([
                'success' => false,
                'message' => 'Подождите 5 минут и попробуйте еще раз',
            ]);
        }

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Введите код из приложения',
            ]);
        }

        if (strlen($code) != 6) {
            return response()->json([
                'success' => false,
                'message' => 'Код должен состоять из 6 цифр!',
            ]);
        }

        if (!$google2fa->verifyKey($secretKey, $code)) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный код! Попробуйте еще раз',
            ]);
        }

        $user->update([
            'two_factor_secret' => $secretKey,
            'two_factor' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно включили двухэтапную авторизацию!',
        ]);
    }

    public function totpDisable(Request $request) {
        $code = trim(str_replace(' ', '', $request->input('code')));
        $google2fa = new Google2FA();
        $user = Auth::user();

        if (!$user->two_factor){
            return [
                'success' => false,
                'message' => 'У вас не подключена двухэтапная авторизация!'
            ];
        }

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Введите код из приложения',
            ]);
        }

        if (strlen($code) != 6) {
            return response()->json([
                'success' => false,
                'message' => 'Код должен состоять из 6 цифр!',
            ]);
        }

        if (!$google2fa->verifyKey($user->two_factor_secret, $code)) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный код! Попробуйте еще раз',
            ]);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно отключили двухэтапную авторизацию!',
        ]);
    }

    public function teleport(Request $request) {
        $user = Auth::user();

        if (Cache::has('teleport_cooldown_' . $user->id)){
            return response()->json([
                'success' => false,
                'message' => 'Вы не можете пользоваться этой функцией чаще чем 1 раз в 5 минут!'
            ]);
        }

        try{
            $server = Server::findOrFail($request->input('server'));
            $server->sendCommand('spawn ' . $user->name);

            Cache::set('teleport_cooldown_' . $user->id, 1, 5 * 60);
        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Сервер не отвечает :( Попробуйте вновь через 5 минут'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно телепортировались на спавн на сервере '. $server->name,
        ]);
    }

    public function change(Request $request) {
        $type = $request->type;

        switch ($type) {
            case 'password':
                return response()->json([
                    'success' => true,
                    'message' => 'Подтвердите действия, перейдя по ссылке, которая отравлена на вашу текущую почту'
                ]);

            case 'email':
                return response()->json([
                    'success' => false,
                    'message' => 'Изменить почту можно только через группу ВК'
                ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'У вас нет прав для изменения этих типов данных'
        ]);
    }

    public function activities(Request $request) {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'activities' => $user->authentications
        ]);
    }
}
