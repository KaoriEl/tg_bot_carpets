<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RugsBeforeWashing extends Model
{
    use HasFactory;
    protected $table = 'rugs_before_washing';
    protected $fillable = [
        'tg_user_id',
        'id_deals',
        'photo',
        'status',
    ];


}
