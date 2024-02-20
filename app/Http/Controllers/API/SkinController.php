<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use App\Models\Servers;
use App\Models\User;
use Auth;
use Storage;
use Str;
use Intervention\Image\Facades\Image;
use App\Libraries\SkinLib;

class SkinController extends Controller
{

    public function __construct(){
        // $this->middleware('auth');
    }

    public function previewSkin($uuid, $side = false, $size = null) {
        $storage = Storage::disk('cdn');

        if ($side == 'head') {
            if ($storage->exists('heads/' . $uuid . '.png')) {
                try {
                    $file = Image::make($storage->get('heads/' . $uuid . '.png'));
                    if ($size) $file->resize(null, $size, function ($constraint) { $constraint->aspectRatio(); });
                    $file->mask($storage->get('masks/head-rounded.png'), true);
                    return $file->response('png');
                } catch (FileNotFoundException $exception) {
                    return;
                }
            } else {
                try {
                    $skin = Image::make($storage->get('skins/default.png'));
                    $skin = $skin->encode('png');
                    $head = SkinLib::createHeadImage($skin, 64);
                    $file = Image::make($head);
                    if ($size) $file->resize(null, $size, function ($constraint) { $constraint->aspectRatio(); });
                    $file->mask($storage->get('masks/head-rounded.png'), true);
                    return $file->response('png');
                } catch (FileNotFoundException $exception) {
                    return;
                }
            }
        }

        if ($side == 'file') {
            if ($storage->exists('skins/' . $uuid . '.png')) {
                try {
                    $file = Image::make($storage->get('skins/' . $uuid . '.png'));
                    if ($size) $file->resize(null, $size, function ($constraint) { $constraint->aspectRatio(); });
                    return $file->response('png');
                } catch (FileNotFoundException $exception) {
                    return;
                }
            } else {
                try {
                    $file = Image::make($storage->get('skins/default.png'));
                    if ($size) $file->resize(null, $size, function ($constraint) { $constraint->aspectRatio(); });
                    return $file->response('png');
                } catch (FileNotFoundException $exception) {
                    return;
                }
            }
        }

        if ($side == 'cloak') {
            if ($storage->exists('cloaks/' . $uuid . '.png')) {
                try {
                    $file = Image::make($storage->get('cloaks/' . $uuid . '.png'));
                    if ($size) $file->resize(null, $size, function ($constraint) { $constraint->aspectRatio(); });
                    return $file->response('png');
                } catch (FileNotFoundException $exception) {
                    return;
                }
            } else return;
        }

        if (!$size) $size = 256;

        if ($size < 22 || $size > 1024) {
            return response()->json([
                'success' => false,
                'message' => 'Размер не может быть меньше 22 и больше 1024!'
            ]);
        }

        $filename = 'skins/' . $uuid . '.png';
        if ($storage->exists($filename)) {
            try {
                $image_skin = Image::make($storage->get($filename));
            } catch (FileNotFoundException $e) {
                $image_skin = Image::make($storage->get('skins/default.png'));
            }
        } else $image_skin = Image::make($storage->get('skins/default.png'));

        $filename = 'cloaks/'. $uuid . '.png';
        if ($storage->exists($filename)) {
            try {
                $image_cloak = Image::make($storage->get($filename));
            } catch (FileNotFoundException $e) {
                $image_cloak = false;
            }
        } else $image_cloak = false;

        try{
            $preview = SkinLib::createPreviewImage($image_skin, $image_cloak, $side, $size);

            return $preview->response('png');
        }catch (\Exception $exception){
            return;
        }
    }

    public function deleteSkin(Request $request) {
        $user = Auth::user();
        $filename = 'skins/' . $user->uuid . '.png';
        $storage = Storage::disk('cdn');

        if ($storage->exists($filename)){
            $storage->delete($filename);

            $filename = 'heads/' . $user->uuid . '.png';
            if ($storage->exists($filename)) $storage->delete($filename);

            return response()->json([
                'success' => true,
                'message' => 'Вы успешно удалили свой скин'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'У вас не установлен скин'
            ]);
        }
    }

    public function deleteCloak(Request $request) {
        $user = Auth::user();
        $filename = 'cloaks/' . $user->uuid . '.png';
        $storage = Storage::disk('cdn');

        if ($storage->exists($filename)){
            $storage->delete($filename);

            return response()->json([
                'success' => true,
                'message' => 'Вы успешно удалили свой плащ'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'У вас не установлен плащ'
            ]);
        }
    }

    public function uploadSkin(Request $request) {
        $file = $request->file('file');
        $user = Auth::user();
        $storage = Storage::disk('cdn');

        if ($file) {
            if ($file->getMimeType() == 'image/png'){
                $image = Image::make($file->getRealPath());

                if(!in_array($image->width() . 'x' . $image->height(), array_keys(SkinLib::$sizesAccept['skins']))){
                    return response()->json([
                        'success' => false,
                        'message' => 'Неверный размер картинки!'
                    ]);
                }

                // if(SkinLib::$sizesAccept['skins'][$image->width() . 'x' . $image->height()]){
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'У вас нет прав загружать HD скин!'
                //     ]);
                // }

                $filename = 'skins/' . $user->uuid . '.png';

                $image = $image->encode('png');

                $storage->delete($filename);
                $storage->put($filename, $image);

                try{
                    $head = SkinLib::createHeadImage($image, 64);
                    $filename = 'heads/' . $user->uuid . '.png';
                    $storage->delete($filename);
                    $storage->put($filename, $head->encode('png'));
                } catch (\Throwable $exception) {
                    return response()->json([
                        'success' => false,
                        'message' => $exception->getMessage(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Вы успешно обновили вид персонажа! Ваш скин обновится в течении 5 минут!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Ошибка загрузки! Пожалуйста, выберите PNG файл!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ошибка загрузки файла! Проверьте интернет соединение!'
        ]);
    }

    public function uploadCloak(Request $request) {
        $file = $request->file('file');
        $user = Auth::user();
        $storage = Storage::disk('cdn');

        if ($file) {
            if ($file->getMimeType() == 'image/png'){
                $image = Image::make($file->getRealPath());

                if(!in_array($image->width() . 'x' . $image->height(), array_keys(SkinLib::$sizesAccept['cloaks']))){
                    return response()->json([
                        'success' => false,
                        'message' => 'Неверный размер картинки!'
                    ]);
                }

                // if(SkinLib::$sizesAccept['cloaks'][$image->width() . 'x' . $image->height()]){
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'У вас нет прав загружать HD плащ!'
                //     ]);
                // }

                $filename = 'cloaks/' . $user->uuid . '.png';

                $image = $image->encode('png');

                $storage->delete($filename);
                $storage->put($filename, $image);

                return response()->json([
                    'success' => true,
                    'message' => 'Вы успешно обновили плащ! Ваш плащ обновится в течении 5 минут!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Ошибка загрузки! Пожалуйста, выберите PNG файл!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ошибка загрузки файла! Проверьте интернет соединение!'
        ]);
    }
}
