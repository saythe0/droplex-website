<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;
use App\Models\Server;

class ServerController extends Controller
{
    public function getStatus($serverId) {
        $server = Server::find($serverId);

        if (!$server) {
            return [
                'success' => false,
                'message' => 'Сервер не найден'
            ];
        }

        try {
            $query = new MinecraftQuery();
            $query->Connect($server->ip, $server->default_port);

            $info = $query->GetInfo();
            $info['success'] = true;
            $info['playerList'] = $query->GetPlayers();

            return $info;
        } catch(MinecraftQueryException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }
}
