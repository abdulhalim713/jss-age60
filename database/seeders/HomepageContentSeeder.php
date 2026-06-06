<?php

namespace Database\Seeders;

use App\Models\CommitteeMember;
use App\Models\HeroSetting;
use App\Models\MediaGallery;
use Illuminate\Database\Seeder;

class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        // Seed default hero settings (only if none exist)
        if (HeroSetting::count() === 0) {
            HeroSetting::create([
                'title'      => '৬০ বছর উজ্জ্বল দিগন্ত',
                'subtitle'   => '১৯৬৭-২০২৭: শিক্ষার আলো, বন্ধনের হীরকখণ্ড। প্রাক্তন-বর্তমান মিলনমেলা ও সাংস্কৃতিক উৎসব।',
                'bg_image'   => null,
                'sort_order' => 1,
            ]);
            HeroSetting::create([
                'title'      => 'স্মৃতির আঙিনায় ফিরে দেখা',
                'subtitle'   => 'এসো মিলি প্রাণের স্পন্দনে, ফিরে যাই সেই সোনালী দিনগুলোতে। হীরক জয়ন্তীতে আপনার অংশগ্রহণ আমাদের ধন্য করবে।',
                'bg_image'   => null,
                'sort_order' => 2,
            ]);
        }

        // Seed default committee members (only if none exist)
        if (CommitteeMember::count() === 0) {
            $members = [
                ['name' => 'অধ্যক্ষ মোঃ সিরাজুল ইসলাম', 'role' => 'সভাপতি',              'icon' => 'fas fa-user-tie',          'sort_order' => 1, 'mobile' => '০১৭১৩-৯১৩০৭৬', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'মোঃ ফারুক হোসেন',           'role' => 'সদস্য সচিব',           'icon' => 'fas fa-user-graduate',     'sort_order' => 2, 'mobile' => '০১৭১৩-৯১৩০৭৭', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'শামসুন্নাহার বেগম',          'role' => 'প্রাক্তন শিক্ষক প্রতিনিধি', 'icon' => 'fas fa-chalkboard-user', 'sort_order' => 3, 'mobile' => '০১৭১৩-৯১৩০৭৮', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'আলমগীর কবির',                'role' => 'প্র প্রাক্তন সমন্বয়ক',       'icon' => 'fas fa-hand-sparkles',    'sort_order' => 4, 'mobile' => '০১৭১৩-৯১৩০৭৯', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'রোজী আক্তার',                'role' => 'মিডিয়া উপকমিটি',        'icon' => 'fas fa-camera',           'sort_order' => 5, 'mobile' => '০১৭১৩-৯১৩০৮০', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'নাজমুল হুদা',                'role' => 'সাংস্কৃতিক সম্পাদক',    'icon' => 'fas fa-music',            'sort_order' => 6, 'mobile' => '০১৭১৩-৯১৩০৮১', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'মাহবুবা সুলতানা',            'role' => 'অর্থ ও স্পন্সর',         'icon' => 'fas fa-dollar-sign',      'sort_order' => 7, 'mobile' => '০১৭১৩-৯১৩০৮২', 'address' => 'গাংনী, মেহেরপুর'],
                ['name' => 'মঞ্জুরুল ইসলাম',             'role' => 'ক্রীড়া সম্পাদক',        'icon' => 'fas fa-running',          'sort_order' => 8, 'mobile' => '০১৭১৩-৯১৩০৮৩', 'address' => 'গাংনী, মেহেরপুর'],
            ];

            foreach ($members as $member) {
                CommitteeMember::create($member);
            }
        }

        // Seed default gallery items (only if none exist)
        if (MediaGallery::count() === 0) {
            $galleryItems = [
                ['type' => 'image', 'title' => 'বিদ্যালয় প্রাঙ্গণ', 'file_path' => 'https://placehold.co/800x600/0F4C5C/D4AF37?text=JSS+Campus', 'video_url' => null, 'sort_order' => 1],
                ['type' => 'image', 'title' => 'বিজ্ঞান মেলা', 'file_path' => 'https://placehold.co/800x600/1B6B82/D4AF37?text=Science+Fair', 'video_url' => null, 'sort_order' => 2],
                ['type' => 'image', 'title' => 'ক্রীড়া প্রতিযোগিতা', 'file_path' => 'https://placehold.co/800x600/2C7DA0/D4AF37?text=Sports+Day', 'video_url' => null, 'sort_order' => 3],
                ['type' => 'image', 'title' => 'প্রাক্তন মিলনমেলা', 'file_path' => 'https://placehold.co/800x600/4A235A/D4AF37?text=Alumni+Meet', 'video_url' => null, 'sort_order' => 4],
                ['type' => 'video', 'title' => 'ষাট বছরের গৌরবগাথা', 'file_path' => null, 'video_url' => 'https://www.youtube.com/embed/9bZkp7q19f0', 'sort_order' => 5],
                ['type' => 'video', 'title' => 'প্রাক্তন শিক্ষার্থীদের শুভেচ্ছা', 'file_path' => null, 'video_url' => 'https://www.youtube.com/embed/tgbNymZ7vqY', 'sort_order' => 6],
            ];

            foreach ($galleryItems as $item) {
                MediaGallery::create($item);
            }
        }
    }
}
