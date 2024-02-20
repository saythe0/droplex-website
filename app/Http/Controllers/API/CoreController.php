<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Setting;
use App\Models\GiftCode;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Intervention\Image\Facades\Image;
use Storage;
use Str;


class CoreController extends Controller
{

    public function auth() {
        $user = Auth::user();

        return response()->json([
            'auth' => $user ? true : false,
        ]);
    }

    public function loadHD() {
        $skin = config('settings.hd_skin_price');
        $cloak = config('settings.hd_cloak_price');
        $price = config('settings.hd_price');
        $discount = round((($skin + $cloak) - $price) / ($skin + $cloak) * 100, 0, PHP_ROUND_HALF_DOWN);

        return response()->json([
            'cloak_price' => $skin,
            'skin_price' => $cloak,
            'discount' => $discount,
            'hd_price' => $price,
        ]);
    }

    public function loadInfo() {
        $user = Auth::user();
        $cart = [
            'count' => 0,
            'items' => [],
        ];
        $servers = Server::where("active", 1)->get();
        $serverList = [];

        foreach ($servers as $server) {
            $statusServer = $server->getStatus();

            if ($statusServer['success'] == true) {
                $online = $statusServer['Players'];
                $maxOnline = $statusServer['MaxPlayers'];
                $onlinePercent = ($online / $maxOnline) * 100;
            } else {
                $online = 0;
                $maxOnline = 0;
                $onlinePercent = 100;
            }

            $serverList[] = [
                'id' => $server->id,
                'name' => $server->name,
                'alt_name' => $server->alt_name,
                'description' => $server->description,
                'version' => $server->version,
                'icon' => $server->icon,
                'image' => $server->image,
                'donate' => $server->donate,
                'group_vk_id' => $server->group_vk_id,

                'online' => $online,
                'online_percent' => $onlinePercent,
                'max_online' => $maxOnline,
            ];
        }

        if ($user) {
            $cart = [
                'count' => 0,
                'items' => [],
            ];
        }

        return response()->json([
            'success' => true,

            'user' => $user,
            'bans' => '',
            'donates' => '',
            'permissions' => '',
            'servers' => $serverList,
            'cart' => $cart,
            'hwid' => false,
            'authorized' => $user ? true : false
        ]);
    }
}
