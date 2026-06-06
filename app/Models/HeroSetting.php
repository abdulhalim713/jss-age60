<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = ['title', 'subtitle', 'bg_image', 'sort_order'];

    /**
     * Return the single hero settings row (singleton pattern).
     */
    public static function getSettings(): self
    {
        return self::firstOrCreate([], [
            'title'    => '৬০ বছর উজ্জ্বল দিগন্ত',
            'subtitle' => '১৯৬৭-২০২৭: শিক্ষার আলো, বন্ধনের হীরকখণ্ড। প্রাক্তন-বর্তমান মিলনমেলা ও সাংস্কৃতিক উৎসব।',
            'bg_image' => null,
        ]);
    }
}
