<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    protected $fillable = ['type', 'title', 'file_path', 'video_url', 'sort_order'];
}
