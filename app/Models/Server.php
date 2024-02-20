<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\API\ServerController;
use xPaw\SourceQuery\SourceQuery;
use DB;
use Config;

class Server extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'alt_name',
        'description',
        'version',
        'icon',
        'image',
        'active',
        'donate',
        'group_vk_id',
        'ip',
        'default_port',
        'rcon_port',
        'rcon_password',
        'database',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'database' => 'json',
    ];

    public function getStatus(){
        $status = new ServerController();
        $response = $status->getStatus($this->id);

        return $response;
    }

    public function getDB() {
        Config::set('database.connections.' . $this->database['name'], [
            'driver' => 'mysql',

            'port' => $this->database['port'],
            'host' => $this->database['host'],
            'database' => $this->database['name'],
            'username' => $this->database['username'],
            'password' => $this->database['password'],

            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict' => false,
            'prefix' => '',
        ]);

        $connection = DB::connection($this->database['name']);

        return $connection;
    }

    public function sendCommand($command){
        try {
            $query = new SourceQuery();
            $query->Connect($this->ip, $this->rcon_port, 3, SourceQuery::SOURCE );
            $query->SetRconPassword($this->rcon_password);
            $response = $query->Rcon($command);
        } finally {
            $query->Disconnect();
        }
        return $response;
    }
}
