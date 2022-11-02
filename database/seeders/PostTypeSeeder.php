<?php

namespace Database\Seeders;

use App\Models\PostType;
use Illuminate\Database\Seeder;

class PostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostType::create(['id' => 1, 'name' => ['ar' => 'عرض مشكلة', 'en' => 'Problem']]);
        PostType::create(['id' => 2, 'name' => ['ar' => 'طلب منتج', 'en' => 'Ordering Product']]);
        PostType::create(['id' => 3, 'name' => ['ar' => 'استفسار', 'en' => 'Inquiry']]);
        PostType::create(['id' => 4, 'name' => ['ar' => 'عمل', 'en' => 'Business']]);
        PostType::create(['id' => 5, 'name' => ['ar' => 'قصة', 'en' => 'Story']]);

    }
}
