<?php

namespace App\Models;

use Config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'key',
        'value',
        'name',
        'description',
        'active',
        'created_at',
        'updated_at',
    ];

    public static function get($key) {
        $setting = new self();
        $entry = $setting->where('key', $key)->first();

        if (!$entry) {
            return;
        }

        return $entry->value;
    }

    public static function set($key, $value = null) {
        $prefixed_key = 'settings.'.$key;
        $setting = new self();
        $entry = $setting->where('key', $key)->firstOrFail();

        $entry->value = $value;
        $entry->saveOrFail();

        Config::set($prefixed_key, $value);

        if (Config::get($prefixed_key) == $value) {
            return true;
        }

        return false;
    }
}
