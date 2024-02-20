<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopItem;
use App\Models\ShopCategory;
use App\Models\Server;
use Auth;

class ShopController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function load(Request $request) {
        $user = Auth::user();
        $type = $request->input('type');
        $server = Server::findOrFail($request->input('server'));
        $server_db = $server->getDB();

        switch ($type) {
            case 'items':
                $items = $server_db->table($server->database['tables']['shop_items'])->where("type", 'item')->get();
                $categories = $server_db->table($server->database['tables']['shop_categories'])->where("type", 'item')->get();

                return response()->json([
                    'success' => true,
                    'items' => $items,
                    'categories' => $categories,
                    'group_vk_id' => $server->group_vk_id,
                ]);

            case 'blocks':
                $blocks = $server_db->table($server->database['tables']['shop_items'])->where("type", 'block')->get();
                $categories = $server_db->table($server->database['tables']['shop_categories'])->where("type", 'block')->get();

                return response()->json([
                    'success' => true,
                    'blocks' => $blocks,
                    'categories' => $categories,
                    'group_vk_id' => $server->group_vk_id,
                ]);

            case 'donates':
                return response()->json([
                    'success' => true,
                    'donates' => [],
                ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Произошла неизвестная ошибка :( Попробуйте вновь через 5 минут'
        ]);
    }
}
