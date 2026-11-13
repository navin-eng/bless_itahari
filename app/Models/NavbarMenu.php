<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NavbarMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'type',
        'order',
        'status',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('navbar_menus_active'));
        static::deleted(fn () => Cache::forget('navbar_menus_active'));
    }
}
