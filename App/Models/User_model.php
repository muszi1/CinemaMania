<?php
namespace App\Models;

use App\Model;
use App\Controllers\SessionController;

class User_model extends Model
{
    public string $table = "user";

    public array $attributes = [
        'id' => 'int',
        'full_name' => 'string',
        'password' => 'string',
        'type' => 'string',
        'genre_id' => 'int',
        'email' => 'string'
        
    ];
}