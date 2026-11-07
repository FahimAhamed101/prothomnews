<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();
        $photoUrl = fn (string $keywords, int $width = 900, int $height = 540, int $lock = 1): string => "https://loremflickr.com/{$width}/{$height}/{$keywords}?lock={$lock}";

        Schema::disableForeignKeyConstraints();

        $tables = [
            'reviews',
            'contact_us',
            'video_galleries',
            'photo_galleries',
            'live_tvs',
            'banners',
            'news',
            'subcategories',
            'categories',
            'seos',
            'users',
            'admins',
        ];

        if (DB::getDriverName() === 'pgsql') {
            $existingTables = array_filter($tables, fn ($table) => Schema::hasTable($table));
            if (! empty($existingTables)) {
                DB::statement('TRUNCATE TABLE '.implode(', ', $existingTables).' CASCADE;');
            }
        } else {
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }
        }

        Schema::enableForeignKeyConstraints();

        $admin = Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Site Admin',
                'phone' => '1234567890',
                'address' => 'Newsroom Office',
                'image' => $photoUrl('admin-profile', 512, 512),
                'password' => Hash::make('12345678'),
                'password_hint' => null,
                'status' => true,
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo Reader',
                'phone' => '1234567891',
                'image' => $photoUrl('reader-profile', 512, 512),
                'password' => Hash::make('password'),
            ]
        );

        $categories = [
            [
                'name' => 'রাজনীতি',
                'slug' => 'politics',
                'keywords' => 'government,city,hall',
                'titles' => [
                    'নির্বাচন কমিশনের নতুন রোডম্যাপ নিয়ে রাজনৈতিক অঙ্গনে আলোচনা',
                    'সংসদে জনসেবা সংস্কার পরিকল্পনা নিয়ে বিস্তারিত বিতর্ক',
                    'তরুণ ভোটারদের জন্য শুরু হলো হালনাগাদ নিবন্ধন কার্যক্রম',
                    'জলবায়ু সহনশীল নগর গড়তে নতুন তহবিল অনুমোদন',
                    'সংসদীয় কমিটিতে আরও জবাবদিহি চাইল বিরোধী দল',
                    'অবকাঠামো প্রকল্পে দেরি হলে ব্যয় বাড়বে, বলছেন বিশেষজ্ঞরা',
                ],
            ],
            [
                'name' => 'বিনোদন',
                'slug' => 'entertainment',
                'keywords' => 'cinema,concert,stage',
                'titles' => [
                    'রেকর্ডসংখ্যক দেশি চলচ্চিত্র নিয়ে শুরু হলো উৎসব',
                    'চমকপ্রদ সমাপ্তিতে দর্শকপ্রিয়তার শীর্ষে নতুন ওয়েব সিরিজ',
                    'দেশজুড়ে গ্রীষ্মকালীন কনসার্টের ঘোষণা দিলেন সংগীতশিল্পীরা',
                    'নতুন প্রজন্মের জন্য মঞ্চে ফিরছে কালজয়ী গল্প',
                    'আঞ্চলিক সিনেমায় বিনিয়োগ বাড়ানোর দাবি নির্মাতাদের',
                    'এই মৌসুমের আলোচিত দশ অভিনয়শিল্পীর তালিকা প্রকাশ',
                ],
            ],
            [
                'name' => 'বাংলাদেশ',
                'slug' => 'bangladesh',
                'keywords' => 'bangladesh,city,people',
                'titles' => [
                    'দেশের যোগাযোগব্যবস্থায় যুক্ত হচ্ছে নতুন আধুনিক সেবা',
                    'জেলায় জেলায় নাগরিক সেবা কেন্দ্র চালুর উদ্যোগ',
                    'ঈদযাত্রা নির্বিঘ্ন করতে মহাসড়কে বিশেষ ব্যবস্থাপনা',
                    'রাজধানীর ব্যস্ত সড়কে পরীক্ষামূলক স্মার্ট সংকেত',
                    'গ্রামীণ অর্থনীতিতে নতুন কর্মসংস্থানের সম্ভাবনা',
                    'উপকূলের মানুষের জন্য নির্মিত হচ্ছে নিরাপদ আশ্রয়কেন্দ্র',
                ],
            ],
            [
                'name' => 'বিশ্ব',
                'slug' => 'world',
                'keywords' => 'world,diplomacy,city',
                'titles' => [
                    'জলবায়ু সম্মেলনে নতুন বৈশ্বিক অঙ্গীকারের ঘোষণা',
                    'আঞ্চলিক সহযোগিতা বাড়াতে বৈঠকে বিশ্বনেতারা',
                    'প্রযুক্তি নিরাপত্তায় আন্তর্জাতিক সতর্কতা জারি',
                    'শান্তি আলোচনায় অগ্রগতির আশা প্রকাশ করেছে জাতিসংঘ',
                    'বিশ্ববাজারে খাদ্যপণ্যের দাম নিয়ে নতুন পূর্বাভাস',
                    'দুর্যোগ মোকাবিলায় যৌথ সহায়তা তহবিল গঠনের সিদ্ধান্ত',
                ],
            ],
            [
                'name' => 'জীবনযাপন',
                'slug' => 'lifestyle',
                'keywords' => 'health,fitness,hospital',
                'titles' => [
                    'ঋতু বদলে সুস্থ থাকতে চিকিৎসকদের পাঁচ পরামর্শ',
                    'সপ্তাহান্তে শরীরচর্চা ও পুষ্টি শেখাবে কমিউনিটি ক্লিনিক',
                    'প্রতিদিন হাঁটলে হৃদ্‌যন্ত্র ভালো থাকে, বলছে নতুন গবেষণা',
                    'বর্ষার আগে জরুরি বিভাগ প্রস্তুত করছে হাসপাতালগুলো',
                    'অফিসে দীর্ঘ সময় কাজ করেন? পুষ্টিবিদের সহজ পরামর্শ',
                    'শিক্ষার্থী ও অভিভাবকদের মানসিক স্বাস্থ্য নিয়ে নতুন উদ্যোগ',
                ],
            ],
            [
                'name' => 'খেলা',
                'slug' => 'sports',
                'keywords' => 'sports,stadium,football',
                'titles' => [
                    'গুরুত্বপূর্ণ বাছাইপর্বের জন্য জাতীয় দল ঘোষণা',
                    'সেমিফাইনালে তরুণ তারকার জোড়া গোলে দুর্দান্ত জয়',
                    'ঘরোয়া ক্রিকেটের নতুন সূচি ঘোষণা করল বোর্ড',
                    'ঢাকা ম্যারাথনে এবার রেকর্ড অংশগ্রহণের আশা',
                    'শৃঙ্খলাবদ্ধ রক্ষণেই সাফল্য, বললেন কোচ',
                    'ঐতিহাসিক জয়ে গ্যালারিজুড়ে সমর্থকদের উল্লাস',
                ],
            ],
            [
                'name' => 'বাণিজ্য',
                'slug' => 'business',
                'keywords' => 'business,market,economy',
                'titles' => [
                    'রপ্তানি আয়ে নতুন সম্ভাবনা দেখছেন উদ্যোক্তারা',
                    'ক্ষুদ্র ব্যবসার জন্য সহজ ঋণের নতুন কর্মসূচি',
                    'বাজারে নিত্যপণ্যের সরবরাহ স্বাভাবিক রাখতে উদ্যোগ',
                    'তরুণ উদ্যোক্তাদের উদ্ভাবনী পণ্যে বাড়ছে বিনিয়োগ',
                    'ডিজিটাল লেনদেনে নতুন মাইলফলক ছুঁয়েছে দেশ',
                    'শেয়ারবাজারে বিনিয়োগকারীদের আস্থা ফেরার ইঙ্গিত',
                ],
            ],
            [
                'name' => 'প্রযুক্তি',
                'slug' => 'technology',
                'keywords' => 'technology,computer,innovation',
                'titles' => [
                    'বাংলা ভাষার জন্য নতুন কৃত্রিম বুদ্ধিমত্তা সেবা',
                    'দেশি তরুণদের তৈরি অ্যাপ পেল আন্তর্জাতিক স্বীকৃতি',
                    'অনলাইনে নিরাপদ থাকতে যে অভ্যাসগুলো জরুরি',
                    'শিক্ষাপ্রতিষ্ঠানে চালু হচ্ছে আধুনিক প্রযুক্তি ল্যাব',
                    'স্মার্টফোনের ব্যাটারি ভালো রাখার সহজ কৌশল',
                    'স্টার্টআপদের জন্য নতুন উদ্ভাবন কেন্দ্রের যাত্রা',
                ],
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $categoryRecords = DB::table('categories')->whereIn('slug', array_column($categories, 'slug'))->get()->keyBy('slug');

        foreach ($categoryRecords as $category) {
            foreach (['সর্বশেষ', 'বিশ্লেষণ'] as $label) {
                DB::table('subcategories')->updateOrInsert(
                    ['slug' => Str::slug($category->slug.'-'.$label)],
                    [
                        'category_id' => $category->id,
                        'name' => $label,
                        'slug' => Str::slug($category->slug.'-'.$label),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        $subcategories = DB::table('subcategories')->get()->groupBy('category_id');
        $categorySeedData = collect($categories)->keyBy('slug');
        $newsCounter = 1;

        foreach ($categoryRecords as $category) {
            $seedData = $categorySeedData[$category->slug];

            for ($index = 1; $index <= 6; $index++) {
                $title = $seedData['titles'][$index - 1];
                $articleSlug = Str::slug($title) ?: $category->slug.'-'.$index;
                $subcategory = $subcategories[$category->id]->first();

                DB::table('news')->updateOrInsert(
                    ['slug' => $articleSlug],
                    [
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'admin_id' => $admin->id,
                        'title' => $title,
                        'slug' => $articleSlug,
                        'image' => $photoUrl($seedData['keywords'], 900, 540, $newsCounter),
                        'details' => implode("\n\n", [
                            'প্রতিদিন সংবাদের প্রতিবেদকেরা দিনভর ঘটনাটি অনুসরণ করেছেন। সংশ্লিষ্ট কর্মকর্তা, স্থানীয় মানুষ ও বিশেষজ্ঞদের সঙ্গে কথা বলে পরিবর্তনের কারণ এবং এর প্রভাব বোঝার চেষ্টা করা হয়েছে।',
                            'প্রাথমিক তথ্যে ধারণার চেয়েও বিস্তৃত প্রভাবের ইঙ্গিত পাওয়া যাচ্ছে। সংশ্লিষ্টরা বলছেন, এই সিদ্ধান্ত দৈনন্দিন সেবা ও মানুষের জীবনে গুরুত্বপূর্ণ পরিবর্তন আনতে পারে।',
                            'বিশেষজ্ঞদের মতে, পরবর্তী ধাপের সাফল্য নির্ভর করবে স্বচ্ছ বাস্তবায়ন, সময়মতো তথ্য প্রকাশ এবং মানুষের বাস্তব প্রশ্নগুলোর জবাব দেওয়ার ওপর।',
                        ]),
                        'tags' => strtolower($category->slug).',news,featured',
                        'breaking_news' => $index <= 2 ? 1 : 0,
                        'top_slider' => $newsCounter <= 18 ? 1 : 0,
                        'first_section_three' => $newsCounter <= 3 ? 1 : 0,
                        'first_section_nine' => $newsCounter <= 9 ? 1 : 0,
                        'post_date' => $now->format('d-m-Y'),
                        'post_month' => $now->format('F'),
                        'status' => true,
                        'view_count' => 100 - $newsCounter,
                        'created_at' => $now->copy()->subMinutes($newsCounter),
                        'updated_at' => $now,
                    ]
                );

                $newsCounter++;
            }
        }

        DB::table('banners')->updateOrInsert(
            ['id' => 1],
            [
                'home_one' => $photoUrl('newspaper,office', 725, 100, 201),
                'home_two' => $photoUrl('city,night', 725, 100, 202),
                'home_three' => $photoUrl('press,conference', 725, 100, 203),
                'home_four' => $photoUrl('newsroom,journalist', 725, 100, 204),
                'news_category_one' => $photoUrl('newspaper,desk', 725, 100, 205),
                'news_details_one' => $photoUrl('media,camera', 725, 100, 206),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('live_tvs')->updateOrInsert(
            ['id' => 1],
            [
                'image' => $photoUrl('television,studio', 784, 436, 301),
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'post_date' => $now->format('d-m-Y'),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('seos')->updateOrInsert(
            ['id' => 1],
            [
                'meta_title' => 'প্রতিদিন সংবাদ | সর্বশেষ বাংলা সংবাদ',
                'meta_author' => 'প্রতিদিন সংবাদ',
                'meta_keyword' => 'বাংলা সংবাদ, বাংলাদেশ, রাজনীতি, বিশ্ব, খেলা, বিনোদন',
                'meta_description' => 'দেশ, বিশ্ব, রাজনীতি, বাণিজ্য, খেলা, বিনোদন ও জীবনযাপনের সর্বশেষ বিশ্বস্ত বাংলা সংবাদ।',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        for ($index = 1; $index <= 6; $index++) {
            DB::table('photo_galleries')->updateOrInsert(
                ['photo_gallery' => $photoUrl('journalism,city,people', 640, 420, 400 + $index)],
                [
                    'photo_gallery' => $photoUrl('journalism,city,people', 640, 420, 400 + $index),
                    'post_date' => $now->format('d-m-Y'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        for ($index = 1; $index <= 5; $index++) {
            DB::table('video_galleries')->updateOrInsert(
                ['title' => 'Video Report '.$index],
                [
                    'image' => $photoUrl('camera,interview,news', 640, 360, 500 + $index),
                    'title' => 'Video Report '.$index.': Field Update',
                    'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'post_date' => $now->format('d-m-Y'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        DB::table('contact_us')->updateOrInsert(
            ['email' => 'reader@example.com'],
            [
                'name' => 'Interested Reader',
                'email' => 'reader@example.com',
                'phone' => '1234567892',
                'comments' => 'Please send me updates from the newsroom.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $firstNews = DB::table('news')->first();

        if ($firstNews) {
            DB::table('reviews')->updateOrInsert(
                ['user_id' => $user->id, 'news_id' => $firstNews->id],
                [
                    'comments' => 'Helpful story with clear context.',
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
