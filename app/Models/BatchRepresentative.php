<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchRepresentative extends Model
{
    protected $fillable = [
        'alumni_id',
        'name',
        'image',
        'batch',
        'mobile',
        'address',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
