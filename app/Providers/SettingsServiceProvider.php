<?php

namespace App\Providers;

use App\Models\Setting as Setting;
use Config;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider{

    public function boot()
    {

        if (!$this->is_run_in_console()){
            if (\Cache::has('laravel_settings')){
                $settings = \Cache::get('laravel_settings');
            }else {
                $settings = Setting::all();
                \Cache::set('laravel_settings', $settings, 60);
            }

            $rewrite = [
                'smtp_host' => 'mail.host',
                'smtp_port' => 'mail.port',
                'smtp_user' => 'mail.username',
                'smtp_pass' => 'mail.password',
                'smtp_email' => 'mail.from.address',

                's3_key_id' => 'AWS_ACCESS_KEY_ID',
                's3_secret_access_key' => 'AWS_SECRET_ACCESS_KEY',
                's3_region' => 'AWS_DEFAULT_REGION',
                's3_bucket_name' => 'AWS_BUCKET',
                's3_url' => 'AWS_URL',
                's3_endpoint' => 'AWS_ENDPOINT',

                'vk_group_id' => 'VK_GROUP_ID',
                'youtube_url' => 'YOUTUBE_URL',
                'discord_url' => 'DISCORD_URL',
            ];

            foreach ($settings as $key => $setting) {
                Config::set('settings.' . $setting->key, $setting->value);

                if(in_array($setting->key, array_keys($rewrite))){
                    Config::set($rewrite[$setting->key], $setting->value);
                }
            }
        }

        $this->publishes([__DIR__.'/database/migrations/' => database_path('migrations')], 'migrations');

        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/backpack')], 'lang');
    }

    function is_run_in_console(): bool
    {
        if (true === (bool) getenv('RR_HTTP')) {
            return false;
        }
        return \App::runningInConsole();
    }
}
