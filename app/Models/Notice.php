<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'file',
        'file_name',
        'file_size',
        'category',
        'description',
        'show_in',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        $flushNoticeCaches = function () {
            Cache::forget('home.popup_notice');
            Cache::forget('home.marquee_notice');
            Cache::forget('header_marquee_notice');
            Cache::forget('sticky_notices_list');
        };

        static::saved($flushNoticeCaches);
        static::deleted($flushNoticeCaches);
    }

    /**
     * Check if the notice is expired.
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return Carbon::parse($this->expires_at)->endOfDay()->isPast();
    }

    /**
     * Scope query to only active, unexpired notices.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', Carbon::now()->startOfDay());
            });
    }

    /**
     * Scope query to expired notices.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now()->startOfDay());
    }

    /**
     * Check if notice has an attached file.
     */
    public function hasFile(): bool
    {
        return !empty($this->file);
    }

    /**
     * Check if the attached file is a PDF.
     */
    public function isPdf(): bool
    {
        if (empty($this->file)) {
            return false;
        }
        $ext = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));
        return $ext === 'pdf';
    }

    /**
     * Get attached file extension.
     */
    public function getFileExtension(): string
    {
        if (empty($this->file)) {
            return '';
        }
        return strtolower(pathinfo($this->file, PATHINFO_EXTENSION));
    }
}
