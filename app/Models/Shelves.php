<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelves extends Model
{
    use HasFactory;
    protected $fillable = [
        'tg_user_id',
        'id_deals',
        'comment',
        'status',
    ];
}
