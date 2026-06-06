<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumni;

class AlumniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumni = [
            [
                'name' => 'মোঃ আব্দুল মতিন',
                'batch' => '১৯৮৫',
                'phone' => '01712345678',
                'email' => 'matin@example.com',
                'address' => 'গাংনী, মেহেরপুর',
                'status' => 'approved',
            ],
            [
                'name' => 'ফাতেমা খাতুন',
                'batch' => '১৯৯২',
                'phone' => '01711223344',
                'email' => 'fatema@example.com',
                'address' => 'মুজিবনগর, মেহেরপুর',
                'status' => 'approved',
            ],
            [
                'name' => 'হাসান আহমেদ',
                'batch' => '২০০০',
                'phone' => '01819876543',
                'email' => 'hasan@example.com',
                'address' => 'ঢাকা',
                'status' => 'approved',
            ],
            [
                'name' => 'শাহিনুর রহমান',
                'batch' => '১৯৯৫',
                'phone' => '01911224455',
                'email' => 'shahinor@example.com',
                'address' => 'গাংনী, মেহেরপুর',
                'status' => 'approved',
            ],
            [
                'name' => 'তৌফিক আহমেদ',
                'batch' => '২০১০',
                'phone' => '01799887766',
                'email' => 'toufik@example.com',
                'address' => 'কুষ্টিয়া',
                'status' => 'pending',
            ],
            [
                'name' => 'মোসাঃ কামরুন্নাহার',
                'batch' => '২০০৫',
                'phone' => '01511223344',
                'email' => 'kamrun@example.com',
                'address' => 'মেহেরপুর',
                'status' => 'pending',
            ],
            [
                'name' => 'রফিকুল ইসলাম',
                'batch' => '১৯৯৮',
                'phone' => '01311223344',
                'email' => 'rafiq@example.com',
                'address' => 'খুলনা',
                'status' => 'rejected',
            ]
        ];

        foreach ($alumni as $a) {
            Alumni::create($a);
        }
    }
}
