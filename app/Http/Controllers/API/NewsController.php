<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function show($count) {

        if ($count < 1 || $count > 50) {
            return response()->json([
                'success' => false,
                'message' => 'Количество постов запрашиваемых не может быть меньше 1 и больше 50',
            ]);
        }

        $response = Http::withOptions(['verify' => false])->get('https://api.vk.com/method/wall.get', [
                "v" => "5.154",
                "count" => $count,
                "owner_id" => "-149913512",
                "extended" => "1",
                "access_token" => "685fff81685fff81685fff8187682c0cf16685f685fff81373db9e51b09f30449e9a465"
        ]);

        $news = [];
        $linkSource = "https://vk.com/" . $response->object()->response->groups[0]->screen_name ."?w=wall-" . $response->object()->response->groups[0]->id;

        $id = 1;
        foreach($response->object()->response->items as $item) {
            $photoUrl = '/assets/images/news-image.png';

            foreach ($item->attachments as $photo) {
                if ($photo->type == 'photo') {
                    foreach ($photo->photo->sizes as $value) {
                        if ($value->type == 'w') {
                            $photoUrl = $value->url;
                        }
                    }
                }
            }

            $news[] = [
                'id' => $id,
                'photo' => $photoUrl,
                'views' => $item->views->count,
                'likes' => $item->likes->count,
                'comments' => $item->comments->count,
                'reposts' => $item->reposts->count,
                'title' => mb_strimwidth($item->text,0,24,"..."),
                'text' => mb_strimwidth($item->text,0,300,"..."),
                'link' => $linkSource . '_' . $item->id,
            ];

            $id++;
        }

        return response()->json([
            'success' => true,
            'news' => $news,
        ]);
    }
}
