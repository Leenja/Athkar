<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dhikr;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DhikrSeeder extends Seeder
{
    public function run(): void
    {
        $shortcuts = [
            'morning'      => ['ar' => 'أذكار الصباح', 'starts_at' => '04:00', 'ends_at' => '11:59'],
            'evening'      => ['ar' => 'أذكار المساء', 'starts_at' => '15:00', 'ends_at' => '18:59'],
            'before-sleep' => ['ar' => 'أذكار قبل النوم', 'starts_at' => '21:00', 'ends_at' => '23:59'],
            'waking-up'    => ['ar' => 'أذكار الاستيقاظ', 'starts_at' => '00:00', 'ends_at' => '03:59'],
            'after-prayer' => ['ar' => 'أذكار بعد الصلاة', 'starts_at' => null, 'ends_at' => null],
            'prayer'       => ['ar' => 'أدعية الصلاة', 'starts_at' => null, 'ends_at' => null],
            'mosque'       => ['ar' => 'أدعية المسجد', 'starts_at' => null, 'ends_at' => null],
            'travel'       => ['ar' => 'أدعية السفر', 'starts_at' => null, 'ends_at' => null],
            'food'         => ['ar' => 'أدعية الطعام', 'starts_at' => null, 'ends_at' => null],
            'home'         => ['ar' => 'أدعية المنزل', 'starts_at' => null, 'ends_at' => null],
            'anxiety'      => ['ar' => 'القلق والضيق', 'starts_at' => null, 'ends_at' => null],
            'protection'   => ['ar' => 'أدعية الحماية', 'starts_at' => null, 'ends_at' => null],
            'forgiveness'  => ['ar' => 'الاستغفار', 'starts_at' => null, 'ends_at' => null],
            'hajj'         => ['ar' => 'الحج والعمرة', 'starts_at' => null, 'ends_at' => null],
        ];

        $order = 0;

        foreach ($shortcuts as $slug => $meta) {
            $response = Http::get("https://api.islamic.app/v1/dhikr/{$slug}")->json();

            $duas = $response['data']['duas'] ?? [];

            if (empty($duas)) {
                $this->command->warn("No data for {$slug}");
                continue;
            }

            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name_ar' => $meta['ar'],
                    'starts_at' => $meta['starts_at'],
                    'ends_at' => $meta['ends_at'],
                    'order' => $order++,
                ]
            );

            foreach ($duas as $index => $dua) {
                Dhikr::firstOrCreate(
                    [
                        'category_id' => $category->id,
                        'text_ar' => $dua['ar']['text'] ?? '',
                    ],
                    [
                        'repeat_count' => $dua['repeatCount'] ?? 1,
                        'source' => 'حصن المسلم - سعيد بن علي القحطاني',
                        'order' => $index
                    ]
                );
            }
        }
    }
}
