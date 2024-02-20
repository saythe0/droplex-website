<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoaderController extends Controller
{
    public function checkUpdates($target, $version) {
        return response()->json([
            "name" => "3.5.2",
            "notes" => "3.5.2",
            "pub_date" => "2023-02-06T23:56:58Z",
            "signature" => "dW50cnVzdGVkIGNvbW1lbnQ6IHNpZ25hdHVyZSBmcm9tIHRhdXJpIHNlY3JldCBrZXkKUlVRQ0I4YllrbFkxZWM1WVlsdkZQVzc5dnNSakxodTMyOEF1ZHhiVjB4QXpFb2ZjWEVVNUJyYng4SU1kLzJxTVAzSERacmlMc1dla2JxdHVvOU5NdmNwbkFZMmI5OU1Jc0FVPQp0cnVzdGVkIGNvbW1lbnQ6IHRpbWVzdGFtcDoxNzA3NjA2NzAwCWZpbGU6RHJvcGxleCBMb2FkZXJfMy41LjJfeDg2X3J1LVJVLm1zaS56aXAKV0VWaGhEZFh4TlBuK0REakYyNG1tVTlCRjhPbUxQOHpuTkl5YkpXODN3U0FIdERyZDVuK05zRXo4VU91SnFVZXBsT0ZYQ2tiSDFCVWxCTW9rNlhLQnc9PQo=",
            "url" => "https://cdn.droplex.fun/loader/Droplex%20Loader_3.5.2_x86_ru-RU.msi.zip"
        ]);
    }
}
