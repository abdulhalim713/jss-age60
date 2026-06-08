<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = ['name', 'role', 'icon', 'sort_order', 'image', 'mobile', 'address', 'batch'];
}
