<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class ShortcutApp extends Model
{
    protected $fillable = [
        'name',
        'url',
        'path',
        'order',
    ];
}
