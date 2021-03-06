<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCarpets extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_user_id',
        'id_deals',
        'photo',
        'comment',
        'status',
        'media_group_id',
    ];

}
