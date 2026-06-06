<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    protected $table = 'essays';

    protected $fillable = [
        'title',
        'author_name',
        'author_batch',
        'author_phone',
        'author_email',
        'content',
        'status',
    ];
}
