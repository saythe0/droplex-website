<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentOrder;
use Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function replenishment() {
        $this->middleware('auth');

        $user = Auth::user();
        $method = request()->input('method');
        $amount = request()->input('amount');

        if (!$method) {
            return response()->json([
                'success' => false,
                'message' => 'Выберите метод пополнения!',
            ]);
        }
        if (!$amount) {
            return response()->json([
                'success' => false,
                'message' => 'Напишите сумму пополнения!',
            ]);
        }

        switch ($method) {
            case 'freekassa':
                $url = $this->freekassa($amount, $user);
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Данный метод отключен!',
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Переадресация на страницу оплаты',
            'url' => $url
        ]);
    }

    public function freekassa($amount, User $user) {
		$merchant_id = config('settings.freekassa_merchant_id');
		$apiKey = config('settings.freekassa_api_key');
		$secret1 = config('settings.freekassa_secret_1');
		$secret2 = config('settings.freekassa_secret_2');

        $order = PaymentOrder::create([
            'method' => 'freekassa',
            'amount' => $amount,
            'user_id' => $user->id,
            'data' => null,
        ]);

        $data = [
            'm' => $merchant_id,
            'oa' => $amount,
            'currency' => 'RUB',
			'o' => $order->id,
			's' => md5($merchant_id . ':' . $amount . ':' . $secret1 . ':RUB:' . $order->id),
            'em' => $user->email,
            'lang' => 'ru',
        ];

        return 'https://pay.freekassa.ru/?' . http_build_query($data);
    }
    public function freekassaSuccess() {
        $merchant_id = config('settings.freekassa_merchant_id');
		$api = config('settings.freekassa_api_key');
		$secret1 = config('settings.freekassa_secret_1');
		$secret2 = config('settings.freekassa_secret_2');

        $amount = request()->input('AMOUNT');
        $order_id = request()->input('MERCHANT_ORDER_ID');

        if (request()->input('SIGN') == md5($merchant_id.':'.$amount.':'.$secret2.':'.$order_id)) {
            $order = PaymentOrder::findOrFail($order_id);

            if ($order) {
                if ($order->amount == $amount) {
                    $user = User::find($order->user_id);

                    if ($user) {
                        $data = [
                            'payment_account' => 'ssss',
                            'email' => 'dds',
                            'order_id' => $order->id,
                            'method' => 'freekassa',
                        ];

                        Log::info('replenishment with freekassa');
                        Log::info(request()->all());
                        $this->paymentSuccess($user->id, $data);
                    } else {
                        return response()->make('Error #4', 403);
                    }
                } else {
                    return response()->make('Error #3', 403);
                }
            } else {
                return response()->make('Error #2', 403);
            }
        }

        return response()->make('Error #1', 403);
    }

    public function paymentSuccess($user_id, $data) {
        $order = PaymentOrder::find($data['order_id']);
        $user = User::find($user_id);

        $order->update([
            'data' => [
                'payment_account' => $data['payment_account'],
                'email' => $data['email'],
            ]
        ]);

        $user->update(['money' => $user->money + $order->amount]);

        Log::info('replenishment good');
        return response()->make('YES');
    }
}
