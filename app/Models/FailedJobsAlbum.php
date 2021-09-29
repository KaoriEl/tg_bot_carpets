<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedJobsAlbum extends Model
{
    use HasFactory;
    protected $table = 'failed_jobs_album';
    protected $fillable = [
        'media_group_id',
    ];

}
